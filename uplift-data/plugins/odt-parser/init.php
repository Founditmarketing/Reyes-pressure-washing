<?php

	ActionManager::addAction("sidebar-button-addition", function(){

		// Only add this if a user is logged in
		// This is moreso to describe how to setup a permission check if you wanted
		$currentUser = UserAccounts::getSessionUser();
		if (!empty($currentUser)){

			ob_start();
			include(__DIR__ . "/Views/SidebarButton.php");
			$newSidebarButton = ob_get_contents();
			ob_end_clean();

			print($newSidebarButton);
		}
	});

	ActionManager::addAction("internal-routes-loaded", function(&$internalRoutes){
		$internalRoutes[] = [
			"uri"=>"/fbm-cms/odt-parser",
			"view"=>__DIR__ . "/Views/ParserPage.php",
			"layout"=>"/Layouts/_Backend.php",
			"isRegex"=>false,
			"data"=>[

			]
		];

	});
