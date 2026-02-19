<?php
	
	require_once("./fbm-core/Classes/SQLDatabase.php");
	require_once("./fbm-core/Classes/Reviews.php");
	if (SQLDatabase::hasCMSBeenInstalled() === true){
		$reviews = Reviews::getReviews();
	}
