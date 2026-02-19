<?php
	/**
	* @author Garet C. Green
	*/

	require("Exceptions/NonOkResponse.php");

	class WebsiteFetcher{
		protected $html;
		protected $dom;
		protected $uri;
		protected $baseUrl;
		protected $url;

		public function __construct($url){
			$curl = curl_init();
			curl_setopt_array($curl, [
				CURLOPT_URL => $url,
				CURLOPT_RETURNTRANSFER => true
			]);
			$response = curl_exec($curl);
			$responseCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
			curl_close($curl);

			// Fix byte 239 (first order mark of a UTF-8 character)
			// Being incorrectly placed at the start of some documents.
			// This happens on old websites where the silly support team uses
			// a bad encoding on their editor
			if (ord($response[0]) === 239){
				$response = mb_substr($response, 1);
			}

			if ($responseCode == 200){
				$response = $response;
				$this->html = $response;
				$this->uri = preg_replace("/https*:\/\/.*?\//", "/", $url);
				$this->baseUrl = preg_replace("/(https*:\/\/.*?)\/.*/", "$1", $url);
				$this->url = $url;
			}else{
				throw new NonOkResponse("URL fetch returned status code $responseCode. Fetching did not complete.");
			}

		}

		public function parseIntoDOM(){
			if (!$this->dom){
				libxml_use_internal_errors(true);
				$this->dom = new DOMDocument();
				$this->dom->preserveWhiteSpace = false;
				$this->dom->formatOutput = false;
				$this->dom->loadHTML(mb_convert_encoding($this->html, "HTML-ENTITIES", "UTF-8"));
			}
		}

		/**
		* Removes the TLD and leaves the URI from a URL
		*
		* @param string $url The fully qualified URL
		* @return string
		*/
		public function removeBaseUrlFromString($url){
			// Used mainly for HREF attributes to remove the internal base domain
			return str_replace($this->baseUrl, "", $url);
		}

		/**
		*
		* @param string $name
		* @param DOMNode $node
		* @return string
		*/
		public function getAttributeFromNode($name, $node){
			if (property_exists($node, "attributes")){
				if (isset($node->attributes)){
					foreach($node->attributes as $attribute){
						$attributeName = $attribute->name;
						if (strtolower($attributeName) === strtolower($name)){
							return $attribute->textContent;
						}
					}
				}else{
					return "";
				}
			}else{
				return "";
			}
		}

	}
