<?php
	/**
	* @author Garet C. Green
	*/

	$coreDirectory = __DIR__ . "/../../../../fbm-core";

	require_once($coreDirectory . "/Classes/HttpResponse.php");
	require_once($coreDirectory . "/Classes/HttpRequest.php");
	require_once($coreDirectory . "/Classes/UserAccounts.php");
	require_once($coreDirectory . "/Classes/CMSInternals.php");
	require_once($coreDirectory . "/Classes/Pages.php");
	require_once($coreDirectory . "/Classes/Logger.php");
	require_once($coreDirectory . "/Classes/Router.php");
	require_once($coreDirectory . "/Classes/Settings.php");

	$response = new HttpResponse();
	$request = new HttpRequest();

	$account = UserAccounts::getSessionUser();

	if (empty($account)){
		$response->jsonError("You must be logged in.");
	}

	$response->setHeaderContentTypeToJSON();

	$pageLayout = $request->getPostValue("page-layout", false);
	$pageName = $request->getPostValue("page-name", false);
	$pageType = $request->getPostValue("page-type", false);
	$pageHead = $request->getPostValue("page-head", false);
	$fetchedFromUrl = $request->getPostValue("externally-fetched-url", false);
	$pageContent = $request->getPostValue("page-body", false);
	$pageUri = $request->getPostValue("page-uri", false);
	$disableGeneratedMap = isset($_POST['disable-generated-map']) ? true : false;
	$customPageFields = [];

	$availableLayouts = CMSInternals::getAvailableLayouts();

	// Verify that the provided pageLayout is valid and a part of the available layouts
	if (array_search($pageLayout, $availableLayouts) === false){
		$response->jsonError("The layout you have chosen is not valid or does not exist.");
	}

	// Verify pagename isn't blank
	if (strlen($pageName) == 0){
		$response->jsonError("The page name is too short.");
	}

	// Verify pagename isn't in use
	if (!empty(Pages::getPageByName($pageName))){
		$response->jsonError($pageName . " is already an existing page.");
	}

	if (!$pageUri){
		$response->jsonError("You must define a page URL.");
	}

	$pageUri = trim($pageUri);

	if (preg_match("/https*:\/\//", $pageUri) !== 0){
		$response->jsonError("The page URI (in the URL routing box) cannot be an absolute link. Please use relative links.");
	}

	// $pageUri cannot be lacking a starting forward slash
	if (substr($pageUri, 0, 1) != "/"){
		$response->jsonError("The page URI must start with a /");
	}

	if (strlen($pageUri) == 0){
		$response->jsonError("You must define a page URI.");
	}

	// Verify the routed URI isn't already in use
	$pageUsingUri = Pages::getPageByRoute($pageUri);
	if (!empty($pageUsingUri)){
		$response->jsonError("The routed URI you want to use is already in use by page " . $pageUsingUri['pageName']);
	}

	// Make the breadcrumbs
	$breadcrumbs = $request->getPostValue("breadcrumbs", []);
	$parsedBreadcrumbs = [];

	foreach($breadcrumbs as $position=>$crumb){
		$parsedBreadcrumbs[] = [
			"position"=>$position,
			"label"=>$crumb['label'],
			"uri"=>$crumb['uri'],
		];
	}

	// Handle custom page fields
	if ($pageType === "City"){
		// City fields
		$customPageFields['cityName'] = $request->getPostValue("city-name", "");
		$customPageFields['stateName'] = $request->getPostValue("state-name", "");
		$customPageFields['stateNameShorthand'] = $request->getPostValue("state-name-shorthand", "");
		$customPageFields['cityUrl'] = "";//$request->getPostValue("city-url", "");
		$customPageFields['disableGeneratedMap'] = $disableGeneratedMap ? 1 : 0;
	}elseif ($pageType === "Project"){
		// Projects expect a good handful of fields. Ye
		$customPageFields['featuredImage'] = "";
		$customPageFields['featuredImageThumb'] = "";
		$customPageFields['cityName'] = "";
		$customPageFields['stateName'] = "";
		$customPageFields['stateNameShorthand'] = "";
		$customPageFields['brandProducts'] = "";
		$customPageFields['customerFirstName'] = "";
		$customPageFields['customerLastName'] = "";
		$customPageFields['customerTestimonialBody'] = "";
		$customPageFields['customerTestimonialCheck'] = 0;
	}elseif ($pageType === "Blog"){
		$customPageFields['featuredImage'] = "";
		$customPageFields['featuredImageThumb'] = "";
		$customPageFields['categoryID'] = "-1";
	}

	$pageID = Pages::createNewPage($pageName, $pageType);
	Pages::saveBreadcrumbsForPage($pageID, $parsedBreadcrumbs);
	Pages::savePage($pageID, $pageName, $pageUri, false, $pageLayout, $pageHead, $pageContent, false, $customPageFields, $account['id']);
	Pages::setPublicationStatus($pageID, 1);
	Logger::logPageCreation($pageID, $pageName, $account['id']);

	// Add the old URI to redirects if it doesn't have a redirection
	// Remove FQD
	$fetchedUri = preg_replace("/https*:\/\/.*?\//", "/", $fetchedFromUrl);

	if ($fetchedUri != $pageUri){
		if (empty(Router::doesRedirectExist($fetchedUri))){
			Router::createRedirect($fetchedUri, $pageUri, false, 301);
		}
	}

	$response->jsonSuccess(["pageID"=>$pageID]);
