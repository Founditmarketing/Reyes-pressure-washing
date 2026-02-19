<?php
	/** @var mixed $schemaType */
	/** @var mixed $headContents */
	/** @var mixed $bodyContents */
	/** @var mixed $breadcrumbs */
	/** @var mixed $ShortcodeParser */

	/** @var mixed $cityName */
	/** @var mixed $stateName */
	/** @var mixed $stateNameShorthand */
	/** @var mixed $cityUrl */
	/** @var mixed $mapKey */

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
						<div class="mt-3">
							<?= $ShortcodeParser->parse("{{ get-reviews columns=2 truncate-body='false' limit='4' offset='0' city='$cityName' state='$stateNameShorthand' schema='true' show-empty-message='true' show-header='true' }}"); ?>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-12 col-xl-8 mx-auto">
						<?php
							if (!$disableGeneratedMap){
								if ($cityUrl != ""){
									?>
									<div class="text-center">
										<div class="my-3">
											<a class="btn btn-more" target="_blank" href="<?= $cityUrl; ?>"><?= $cityName; ?>, <?= $stateName; ?></a>
										</div>
									</div>
									<?php
								}
								?>
								<div class="row">
									<div class="col-xl-6 mx-auto pb-5">
										<div class="embed-responsive embed-responsive-16by9">
											<iframe class="embed-responsive-item" src="//www.google.com/maps/embed/v1/place?key=<?= $mapKey ?>&q=<?= preg_replace("/[\s]/i", "+", $cityName); ?>+<?= preg_replace("/[\s]/i", "+", $stateName); ?>"></iframe>
										</div>
									</div>
								</div>
								<?php
							}
						?>
					</div>
				</div>

				<?php include(__DIR__ . "/../includes/articles.php"); ?>
				<?php include(__DIR__ . "/../includes/cta.php"); ?>
			</main>
		</div>
		<?php include(__DIR__ . "/../includes/footer.php"); ?>
	</body>
</html>
