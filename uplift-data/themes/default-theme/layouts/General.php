<?php
	/** @var mixed $schemaType */
	/** @var mixed $headContents */
	/** @var mixed $bodyContents */
	/** @var mixed $breadcrumbs */

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
		<div id="page">
			<main class="container-fluid" id="content">
				<div class="row">
					<div class="col-12 col-xl-8 mx-auto py-5">
						<?= $breadcrumbs; ?>
						<?= $bodyContents; ?>
					</div>
				</div>

				<?php include(__DIR__ . "/../includes/projects.php"); ?>
				<?php include(__DIR__ . "/../includes/articles.php"); ?>
				<?php include(__DIR__ . "/../includes/cta.php"); ?>
			</main>
		</div>
		<?php include(__DIR__ . "/../includes/footer.php"); ?>
	</body>
</html>

