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
	$pageUri = $request->getPostValue("page-uri", false);
	$pageContent = $request->getPostValue("page-content", false);
	$excludeFromSitemap = isset($_POST['exclude-from-sitemap']) ? true : false;
	$disableGeneratedMap = isset($_POST['disable-generated-map']) ? true : false;
	$customPageFields = [];
	$allowOverwrites = $request->getPostValue("allow-overwrites", false);

	$availableLayouts = CMSInternals::getAvailableLayouts();

	// Verify that the provided pageLayout is valid and a part of the available layouts
	if (array_search($pageLayout, $availableLayouts) === false){
		$response->jsonError("The layout you have chosen ($pageLayout) is not valid or does not exist.");
	}

	// Verify pagename isn't blank
	if (strlen($pageName) == 0){
		$response->jsonError("The page name is too short.");
	}

	// Verify pagename isn't in use
	//if (!empty(Pages::getPageByName($pageName))){
	//	$response->jsonError($pageName . " is already an existing page.");
	//}

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
	//$pageUsingUri = Pages::getPageByRoute($pageUri);
	//if (!empty($pageUsingUri)){
	//	$response->jsonError("The routed URI you want to use is already in use by page " . $pageUsingUri['pageName']);
	//}

	$pageUsingUri = Pages::getPageByRoute($pageUri);
	$pageUsingName = Pages::getPageByName($pageName);

	if (!empty($pageUsingName) || !empty($pageUsingUri)) {

		if ($allowOverwrites) {
			if (!empty($pageUsingName)) {
				Pages::deletePage($pageUsingName['id']);
			} elseif (!empty($pageUsingUri)){
				Pages::deletePage($pageUsingUri['id']);
			} else {
				$response->jsonError("There is a logic error. This code should never run.");
			}
		} else {
			if (!empty($pageUsingName) && empty($pageUsingUri)) {
				$response->jsonError("The Pagename is already in use. Do you wish to overwrite this page?", ['overwrite'=>true]);
			} elseif (!empty($pageUsingUri) && empty($pageUsingName)) {
				$response->jsonError("The uri is already in use. Do you wish to overwrite this page?", ['overwrite'=>true]);
			} elseif ($pageUsingUri['id'] === $pageUsingName['id']) {
				$response->jsonError("The Pagename and URI are already in use. Do you wish to overwrite this page?", ['overwrite'=>true]);
			} else {
				$response->jsonError("The Pagename and URI are already taken, but by different pages. Please change one.");
			}
		}
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
		$customPageFields['cityUrl'] = $request->getPostValue("city-url", "");
		$customPageFields['disableGeneratedMap'] = $disableGeneratedMap ? 1 : 0;
	}elseif ($pageType === "Blog"){
		$customPageFields['featuredImage'] = "";
		$customPageFields['featuredImageThumb'] = "";
		$customPageFields['categoryID'] = "-1";
	}

	$pageID = Pages::createNewPage($pageName, $pageType);
	Pages::saveBreadcrumbsForPage($pageID, $parsedBreadcrumbs);
	Pages::savePage($pageID, $pageName, $pageUri, false, $pageLayout, $pageHead, $pageContent, $excludeFromSitemap, $customPageFields, $account['id']);
	Pages::setPublicationStatus($pageID, 1);
	Logger::logPageCreation($pageID, $pageName, $account['id']);

	$subDirectoryInstall = (string) Settings::getSetting("install_directory");

	$editUri = $subDirectoryInstall . "fbm-cms/page-editor/edit/$pageID";
	$response->jsonSuccess(["pageID"=>$pageID, "editUri"=>$editUri]);
