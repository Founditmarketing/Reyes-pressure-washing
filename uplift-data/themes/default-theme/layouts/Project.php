<?php
	/** @var mixed $schemaType */
	/** @var mixed $headContents */
	/** @var mixed $bodyContents */
	/** @var mixed $breadcrumbs */

	/** @var mixed $featuredImage */
	/** @var mixed $featuredImageThumb */
	/** @var mixed $cityName */
	/** @var mixed $stateName */
	/** @var mixed $stateNameShorthand */
	/** @var mixed $brandProducts */
	/** @var mixed $customerFirstName */
	/** @var mixed $customerLastName */
	/** @var mixed $customerTestimonialBody */
	/** @var mixed $customerTestimonialCheck */ // Is 1 or 0. 1 = There is a customer review. 0 = No review
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
					</div>
				</div>
				<div class="row">
					<div class="col-12 col-xl-8 mx-auto">
						<?php
							if (trim($brandProducts) != ""){
								?>
								<div class="my-3">
									<p>
										<strong>Products Used:</strong> <?= $brandProducts; ?>
									</p>
								</div>
								<?php
							}
						?>
						<?php
							if ($customerTestimonialCheck == 1){
								?>
								<div class="my-3">
									<p>
										<strong>Client Review:</strong> <?= $customerTestimonialBody; ?>
									</p>
									<?php
										if (!empty(trim($customerFirstName))){
											?>
											<p>
												- <strong><?= $customerFirstName; ?> <?= $customerLastName; ?></strong>
											</p>
											<?php
										}
									?>
								</div>
								<?php
							}
						?>
						<?php
							if (trim($cityName) != ""){
								?>
								<hr>
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
