<?php
	/**
	* @author Garet C. Green
	*/

	require_once("WebsiteFetcher.php");
	require_once("Exceptions/NoContentElement.php");
	require_once(__DIR__ . "/../FlexParser_Filters.php");
	require_once(__DIR__ . "/../../../../fbm-core/Dependencies/Dindent/Exception/DindentException.php");
	require_once(__DIR__ . "/../../../../fbm-core/Dependencies/Dindent/Exception/RuntimeException.php");
	require_once(__DIR__ . "/../../../../fbm-core/Dependencies/Dindent/Exception/InvalidArgumentException.php");
	require_once(__DIR__ . "/../../../../fbm-core/Dependencies/Dindent/Indenter.php");

	class FlexParser extends WebsiteFetcher{

		/**
		* The default name of the element being fetched that will have the main page content in it
		* @var string
		*/
		const ID_OF_CONTENT_CONTAINER = "content";

		/**
		* @var string
		*/
		protected $reparsedMeta = "";
		/**
		* @var string
		*/
		protected $reparsedContent = "";

		/**
		* @var array
		*/
		private $gatheredImages = []; // Images gathered from the content

		/**
		* Returns the HTML of the DOMDocument at whatever current state it is. The result will be beautified
		* @return string
		*/
		public function getHTML(){
			$indenter = new \Gajus\Dindent\Indenter();
			$html = $this->dom->saveHTML();
			$indentedHTML = $indenter->indent($html);
			return $indentedHTML;
		}

		/**
		* Returns the outer HTML of the provided node, instead of the full document's HTML. The result will be beautified
		* @param DOMNode $node The node to return the HTML of
		* @return string
		*/
		public function getNodeOuterHTML($node){
			$indenter = new \Gajus\Dindent\Indenter();
			$html = $this->dom->saveHTML($node);
			$indentedHTML = $indenter->indent($html);
			return $indentedHTML;
		}

		/**
		* Returns the inner HTML of the provided node, instead of the full document's HTML. The result will be beautified
		* @param DOMNode $node The node to return the HTML of
		* @return string
		*/
		public function getNodeInnerHTML($node){

			$html = "";
			$children = $node->childNodes;

			foreach($children as $childNode){
				$html .= $this->dom->saveHTML($childNode);
			}

			$indenter = new \Gajus\Dindent\Indenter();
			$indentedHTML = $indenter->indent($html);
			return $indentedHTML;
		}

		/**
		* Returns the URLs of the images found in the DOMDocument content
		* @return array
		*/
		public function getImagesFromContent(){
			return $this->gatheredImages;
		}

		/**
		* Returns a newly recommended URI based on the current fetched URI
		* @return array
		*/
		public function getRecommendedNewUri(){
			$currentUri = $this->uri;
			$newUri = strtolower($currentUri);
			$newUri = preg_replace("/\..+$/", "", $newUri);

			return $newUri;
		}

		/**
		* Returns the parsed and processed HTML of the main content of the page
		* @return string
		*/
		public function getCleanedMainContent(){
			$contentElement = $this->dom->getElementById(self::ID_OF_CONTENT_CONTAINER);

			if (!$contentElement){
				// Attempt to just get the body
				$contentElement = $this->dom->getElementByTagName("body");
				if (!$contentElement){
					throw new NoContentElement("No element with id #content nor a body element was found.");
				}
			}

			$this->runInternalPreprocessOnParent($contentElement);

			// Run the user-defined preprocessing
			$contentElement = FlexParser_Filters::preprocessDocument($contentElement);
			$contentHtml = $this->getNodeInnerHTML($contentElement);

			// Run the user-defined postprocessing
			$contentHtml = FlexParser_Filters::postprocessDocument($contentHtml);

			return $contentHtml;
		}

		/**
		* Traverses recursively through the entire node tree of the provided parent node
		* Runs internal processes like converting flex classes to Bootstrap
		* @param DOMDocument $parentNode
		* @return void
		*/
		public function runInternalPreprocessOnParent(&$parentNode){
			$queueOfRemovals = [];
			if (isset($parentNode->childNodes)){
				foreach($parentNode->childNodes as $node){


					if (method_exists($node, "getAttribute")){

						// Translate any classes to Bootstrap equivalents
						$attributes = $node->getAttribute("class");
						if ($attributes !== ""){
							$attributes = $this->translateWhitelistedFlexClasses(explode(" ", $attributes), strtolower($node->nodeName));
							if (!empty($attributes)){
								$node->setAttribute("class", implode(" ", $attributes));
							}
						}

						// There's never an instance where the builder wants to keep the breadcrumbs
						if ($node->getAttribute("id") === "breadcrumb"){
							$queueOfRemovals[] = $node;
						}

						// Remove any BR elements with a class of clearboth
						if ($node->nodeName == "br" && strpos($node->getAttribute("class"), "clearboth") !== false){
							$queueOfRemovals[] = $node;
						}

					}

					$this->runInternalPreprocessOnParent($node);
				}
			}

			foreach($queueOfRemovals as $node){
				$parentNode->removeChild($node);
			}
		}

		/**
		* Returns the parsed and processed HTML of the head content of the page
		* @return string
		*/
		public function getCleanedMetaContent(){
			$headElement = $this->dom->getElementsByTagName("head")->item(0);

			if (!$headElement){
				throw new NoContentElement("No element with tag name 'head' exists on the fetched page.");
			}

			// By default, only get description and title
			// NOTE You must not removeChild in the same loop as going through childNodes, it will mess up the iterator and make it seem like the code isn't going through the entire list
			$toRemoveQueue = [];
			foreach($headElement->childNodes as $node){
				if ($node instanceof DOMElement){
					if ($node->getAttribute("name") !== "description" && $node->nodeName !== "title"){
						$toRemoveQueue[] = $node;
					}else{
						// Determine if the description has itemprop and property for og:description
						if ($node->tagName !== "title"){
							if ($node->getAttribute("itemprop") !== "description"){
								$node->setAttribute("itemprop", "description");
							}

							if ($node->getAttribute("property") !== "og:description"){
								$node->setAttribute("property", "og:description");
							}
						}
					}
				}else{
					$toRemoveQueue[] = $node;
				}
			}

			foreach($toRemoveQueue as $node){
				$headElement->removeChild($node);
			}

			// Run the user-defined preprocessing
			$headElement = FlexParser_Filters::preprocessDocument($headElement);
			$headHtml = $this->getNodeInnerHTML($headElement);

			// Run the user-defined postprocessing
			$headHtml = FlexParser_Filters::postprocessDocument($headHtml);

			return $headHtml;
		}

		public function parseChildren($parentElement, $ignoreTextNodes = false, $options = []){
			$resultHTML = "";
			foreach($parentElement->childNodes as $currentNode){
				$resultHTML .= $this->parseNode($currentNode, $ignoreTextNodes, $options);
			}
			return $resultHTML;
		}

		/**
		* Used to take a list of classes on a flex node and keep white-listed classes, then translate them to their Bootstrap equivalent
		*
		* @param array $classes List of classes in Flex
		* @param string $tagName The lowercase tagname of the element the classes are on
		*/
		private function translateWhitelistedFlexClasses($classes, $tagName){
			// Classes is an array
			foreach($classes as $index=>$class){
				if ($class === "align-center" || $class === "center"){
					$classes[$index] = "text-center";
				}elseif ($class === "float-img-right"){
					$classes[$index] = "img-r-dynamic";
				}elseif ($class === "float-img-left"){
					$classes[$index] = "img-l-dynamic";
				}elseif ($class === "make-button"){
					$classes[$index] = "btn btn-primary";
				}
			}

			return $classes;
		}

		/**
		* Attempts to convert all phone numbers in the HTML into respective shortcode equivalents
		* @param string $html The final HTML of the document after calling the getHTML() method
		* @return string
		*/
		private function convertPhoneNumbersToShortcodes($html){
			$html = preg_replace("@\s.-.{3}-.{3}-.{4}@", " {{ phoneNumber which=1 }}", $html);
			$html = preg_replace("@\s.{3}-.{3}-.{4}@", " {{ phoneNumber which=1 }}", $html);
			return $html;
		}



	}
