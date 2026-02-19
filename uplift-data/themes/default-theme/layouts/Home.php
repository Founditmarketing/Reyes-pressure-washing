<?php
	/** @var mixed $schemaType */
	/** @var mixed $headContents */
	/** @var mixed $bodyContents */

	$h3 = CMSInternals::getLastH3($bodyContents);
	//CMSInternals::deleteLastH3($bodyContents);
?>
<!DOCTYPE html>
<html lang="en" itemscope itemtype="https://schema.org/<?= $schemaType; ?>">
	<head>
		<?php include(__DIR__  . "/../includes/meta.php"); ?>
		<?= $headContents; ?>
	</head>
	<body class="<?= $_SERVER['REQUEST_URI'] !== "/" ? "interior-page" : ""; ?>">
		<?php include(__DIR__ . "/../includes/navigation.php"); ?>
		<?php include(__DIR__ . "/../includes/banner.php"); ?>
		<main class="container-fluid" id="content">
			<?= $bodyContents; ?>
			<?php include(__DIR__ . "/../includes/projects.php"); ?>
			<?php include(__DIR__ . "/../includes/articles.php"); ?>
			<?php include(__DIR__ . "/../includes/cta.php"); ?>
		</main>
		<?php include(__DIR__ . "/../includes/footer.php"); ?>
	</body>
</html>
