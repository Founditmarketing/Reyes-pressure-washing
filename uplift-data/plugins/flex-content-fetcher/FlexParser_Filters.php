<?php

	// PHP.net manual for DOCDocument properties and methods to use -> https://www.php.net/manual/en/class.domdocument.php

	class FlexParser_Filters{

		/**
		* This method is called on the DOMDocument after content is fetched (could be the <head> or could be the content like <body>)
		* You can loop through the children of the document or use DOMDocument methods like getElementById, getElementByTagName to edit the inner nodes or those nodes themselves. For example, you could get all divs and check if they have the service-box in their class attribute and change that class via a string replace to bg-light.
		* @param DOMDocument $document The DOMDocument (or could also be DOMNode, but they have the same inheritence)
		* @return DOMDocument
		*/
		public static function preprocessDocument($document){

			return $document;
		}

		/**
		* This method is called on the final HTML string, not a document.
		* This method happens after preprocessDocument() and after any internal flex parsing has happened.
		* @param string $html
		* @return string
		*/
		public static function postprocessDocument($html){


			return $html;
		}
	}
