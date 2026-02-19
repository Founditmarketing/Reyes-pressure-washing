<?php

	FilterManager::addFilter("view-head-body", function(&$pageHead, &$pageBody){
		$baseUrl = CMSInternals::getBaseUrl();
		$defaultOg = $baseUrl . "/images/facebook-og-1.jpg";

		// Determine if there is an H1 in the content, and if so then inject it into the pageHead
		preg_match("/<\s*h1[\w\"\-\s\=\']*>(.+?)<\s*\/\s*h1\s*>/ism", $pageBody, $h1Matches);
		if (isset($h1Matches[1])){
			// Trim any tags that may be in the H1
			$h1Content = trim(strip_tags($h1Matches[1]));

			// Remove newlines and returns and replace them with a single space
			$h1Content = preg_replace("/[\r\n]/ism", " ", $h1Content);

			// Remove multiple spaces and replace them with a single space
			$h1Content = preg_replace("/\s{2,}/ism", " ", $h1Content);

			// Inject the h1 contents into the og:title
			$pageHead .= "\n<meta property=\"og:title\" content=\"$h1Content\">";
		}

		// Check if the head already has og:image somewhere before trying to make one
		if (mb_stripos($pageHead, "property=\"og:image\"") === false){


			// Check all img elements.
			// 1) First determine if there are any, if not default to facebook-og-1.jpg
			// 2) Next determine if any of the img elements has og-image anywhere, if so then use that source
			// 3) Else, default to the first image found
			$numMatches = preg_match_all("/<img ([\w\"\:\-\s\=\'\.\/\{\}]*)\/*>/i", $pageBody, $imgMatches);

			// Here we're checking if there are any possible images
			if ($numMatches === 0 || $numMatches === false){
				$pageHead .= "\n<meta property=\"og:image\" content=\"$defaultOg\">";
			}else{
				$ogSource = "";
				// This loop is checking if there are any images with the "og-image" attribute
				foreach($imgMatches[1] as $imageAttributesString){
					if (mb_strpos($imageAttributesString, "og-image") !== false){
						preg_match("/src=\"(.+?)\"/i", $imageAttributesString, $imageSourceMatch);
						if (isset($imageSourceMatch[1])){
							$ogSource = $imageSourceMatch[1];
							break;
						}
					}
				}

				//If we do have an image or images on the page, but none have the og-image attribute, we need the first image
				if (empty($ogSource)){
					foreach($imgMatches[1] as $imageAttributesString){
						preg_match("/src=\"(.+?)\"/i", $imageAttributesString, $imageSourceMatch);
						if (isset($imageSourceMatch[1])){
							$ogSource = $imageSourceMatch[1];
							break;
						}
					}
				}

				if (!empty($ogSource)){
					// We need to check to see if the string for the image contains the base URL
					if (mb_strpos($ogSource, $baseUrl) === false){
						// Next, we need to check if the string for the image contains a URL at all
						preg_match("/<*(https|http):\/\//i", $ogSource, $protocolCheck);
						if (!isset($protocolCheck[0])){
							if (mb_substr($ogSource, 0, 1) != "/"){
								$ogSource = $baseUrl . "/$ogSource";
							}else{
								$ogSource = $baseUrl . "$ogSource";
							}
						}
					}

					$pageHead .= "\n<meta property=\"og:image\" itemprop=\"image\" content=\"$ogSource\">";
				}else{
					$pageHead .= "\n<meta property=\"og:image\" itemprop=\"image\" content=\"$defaultOg\">";
				}
			}
		}

	});
