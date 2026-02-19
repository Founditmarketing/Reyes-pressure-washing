<?php
	/**
	* @author Garet C. Green
	*/
	require_once(__DIR__ . "/../../../../fbm-core/Classes/HttpResponse.php");
	require_once(__DIR__ . "/../../../../fbm-core/Classes/HttpRequest.php");
	require_once(__DIR__ . "/../../../../fbm-core/Classes/CMSInternals.php");
	require_once(__DIR__ . "/../../../../fbm-core/Classes/UserAccounts.php");
	require_once(__DIR__ . "/../Classes/FlexParser.php");

	$response = new HttpResponse();
	$request = new HttpRequest();

	$account = UserAccounts::getSessionUser();

	if (empty($account)){
		$response->jsonError("No account logged in.");
	}

	$response->setHeaderContentTypeToJSON();

	$externalUrl = $request->getPostValue("external-url", false);

	if (!$externalUrl || !filter_var($externalUrl, FILTER_VALIDATE_URL)){
		$response->jsonError("Please provide a valid URL.");
	}

	$parser = new FlexParser($externalUrl);
	$parser->parseIntoDOM();

	$mainContent = $parser->getCleanedMainContent();
	$head = $parser->getCleanedMetaContent();
	$images = $parser->getImagesFromContent();
	$newURI = $parser->getRecommendedNewUri();

	if (isset($_POST['convert-url-cases'])){
		// Convert all content ULRs with thios base domain to lowercase
		preg_match("/https*:\/\/([a-zA-Z\.0-9]+)\//i", $externalUrl, $matches);
		if (isset($matches[1])){
			$baseUrl = $matches[1];
			$mainContent = preg_replace_callback("/href=\"(.+?)\"/is", function($cMatches) use ($baseUrl){
				if (mb_strpos($cMatches[1], $baseUrl) || mb_substr($cMatches[1], 0, 1) === "/"){
					return "href=\"" . mb_strtolower($cMatches[1]) . "\"";
				}else{
					return $cMatches[0];
				}
			}, $mainContent);
		}
	}

	$response->jsonSuccess(["html"=>$mainContent, "meta"=>$head, "images"=>$images, "newUri"=>$newURI]);
