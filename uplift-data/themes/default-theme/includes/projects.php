<?php
	/********
	* Basic skeleton for projects.php include.
	*
	* The section code below will allow dynamic project content on pages based on the recent-projects shortcode.
	* Use on a page with the following:
	*
	* {% begin projectSection %}
	* {{ get-recent-projects num-projects="2" columns="1" included-project-tags='[]' autofill="0" }}
	* {% end projectSection %}
	*
	* $projSec, if used as a fallback, holds the recent projects shortcode. Defaults to projects from any tag.
	*
	********/

	$projSec = "{{ get-recent-projects num-projects=\"2\" columns=\"1\" included-project-tags='[]' autofill=\"0\" }}";
	if (isset($sections['projectSection'])) {
		$projSec = $sections['projectSection'];
	}
?>

	<!-- START PROJECTS -->
	<section class="row bg-picture-container">
		<picture>
			<source media="(max-width:568px)" type="image/webp" srcset="/uplift-data/images/layout/banners/bg-water-m.webp">
			<source type="image/webp" srcset="/uplift-data/images/layout/banners/bg-water.webp">
			<img src="/uplift-data/images/layout/banners/bg-water.jpg" class="bg-picture watermark" alt="watermark">
		</picture>
		<div class="col-12 align-self-center mx-auto p-2 p-lg-5 text-center">
			<div class="p-0 p-lg-5 text-center">
				<h2 class="text-center f-prime h1">
					VIEW OUR RECENT PROJECTS
				</h2><br><br>
				<?= $ShortcodeParser->parse($projSec); ?>
		  </div>
		  <a href="/projects"><p class="btn btn-more f-prime">View More Projects</p></a>
		</div>
	</section>
	<!-- END PROJECTS -->