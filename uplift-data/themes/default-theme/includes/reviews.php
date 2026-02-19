<?php
	/********
	* Basic skeleton for reviews.php include.
	* 
	* The code below will fetch the two most recent reviews.
	* 
	* $revOutput holds the reviews. If both reviews are missing,
	* the $revPh content will be displayed.
	* 
	* If one review is present, it will show the same text on both variables.
	* 
	* Character length of reviews will be determined by $revLength
	* 
	********/

	$revPh = "Reviews coming soon! [...]";
	$revOutput = [];
	$revLength = 250;
	
	$reviews = array_reverse(Reviews::getReviews());
	if (count($reviews) > 1) {
		$revOutput[0] = trim(substr($reviews[0]['body'], 0, $revLength)) . " [...]";
		$revOutput[1] = trim(substr($reviews[1]['body'], 0, $revLength)) . " [...]";
	} else if (count($reviews) > 0) {
		$revOutput[0] = trim(substr($reviews[0]['body'], 0, $revLength)) . " [...]";
		$revOutput[1] = trim(substr($reviews[0]['body'], 0, $revLength)) . " [...]";
	}
	
?>
<?= ($revOutput[0] ?? $revPh); ?>
<?= ($revOutput[1] ?? $revPh); ?>
