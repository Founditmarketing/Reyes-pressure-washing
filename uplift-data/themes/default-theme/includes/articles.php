<?php
	/********
	* Basic skeleton for articles.php include.
	* 
	* The section code below will allow dynamic article content on pages based on article categories.
	* 
	********/

	$artSec = "{{ get-recent-articles num-articles=\"2\" columns=\"1\" included-article-categories='[]' autofill=\"0\" }}";
	if (isset($sections['articleSection'])) {
		$artSec = $sections['articleSection'];
	}
?>

	<!-- START LATEST ARTICLES -->
	<section class="row bg-primary bgcover align-items-center overno">
			<div class="col-12 col-md-7 order-2 py-5 px-xl-5 text-white order-2 order-md-1">
				<h2 class="f-prime text-uppercase text-white">
					EXTERIOR CLEANING TIPS
				</h2>
				<?= $ShortcodeParser->parse($artSec); ?>
				<div class="mb-0">
					<a href="/pressure-washing-tips" class="btn btn-cta animated-fade-in-right">
						Read More
					</a>
				</div>
			</div>
			<div class="col-12 col-md-5 col-xl-5 p-0 align-self-stretch bgcover order-1 order-md-2">
				<div class="bg-picture-container h-100">
					<svg viewBox="0 0 49.2 600" class="wave-v left prime">
						<path class="st0" d="M-1,600V0h15.3c0,0,30,82.7,30,150s-34,79.8-34,150s20,64.4,20,150s-16,150-16,150H-1z"/>
					</svg>
					<svg viewBox="0 0 600 49.2" class="wave-h prime">
						<path class="st0" d="M600,49.2H0V33.9c0,0,82.7-30,150-30s79.8,34,150,34s64.4-20,150-20s150,16,150,16V49.2z"/>
					</svg>
					<picture>
						<source media="(max-width:568px)" type="image/webp" srcset="/uplift-data/images/layout/banners/article-m.webp">
						<source type="image/webp" srcset="/uplift-data/images/layout/banners/article.webp">
						<img src="/uplift-data/images/layout/banners/article.jpg" class="bg-picture" alt="articles">
					</picture>
				</div>
			</div>
	</section>
	<!-- END LATEST ARTICLES -->	