<?php


	FilterManager::addFilter("view-head-body", function(&$pageHead, &$pageBody){
		global $subDirectoryInstall;
		$pluginFolderUri = substr($subDirectoryInstall, 0, strlen($subDirectoryInstall) - 1) . CMSInternals::getUriFromPath(__DIR__);
		$pageHead .= "<script defer src=\"" . $pluginFolderUri . "/js/paginator.min.js\"></script>";
	});
