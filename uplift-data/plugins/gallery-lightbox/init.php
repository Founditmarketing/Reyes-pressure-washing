<?php

	FilterManager::addFilter("view-head", function(&$pageHead){
		global $subDirectoryInstall;
		$pluginFolderUri = substr($subDirectoryInstall, 0, strlen($subDirectoryInstall) - 1) . CMSInternals::getUriFromPath(__DIR__);
		$baseUrl = CMSInternals::getBaseUrl();
		$pageHead .= "\n<script defer src=\"" . $pluginFolderUri . "/lightbox/js/lightbox.min.js\"></script>\n<link rel=\"preload\" as=\"style\" href=\"" . $pluginFolderUri . "/lightbox/css/lightbox.min.css\" onload=\"this.rel='stylesheet'\">\n";
	});
