
<!-- START BANNER -->
<section id="banner">
	<div class="carousel slide carousel-fade" data-ride="carousel" id="heroCarousel">
		<div class="carousel-inner">
			<div class="carousel-item active p-0">
				<div class="overlay1"></div>
				<div class="bg-picture-container">
					<picture>
						<source media="(max-width:568px)" type="image/webp" srcset="/uplift-data/images/layout/banners/banner-1-m.webp">
						<source type="image/webp" srcset="/uplift-data/images/layout/banners/banner-1.webp">
						<img src="/uplift-data/images/layout/banners/banner-1.jpg" class="bg-picture" alt="pressure washing company">
					</picture>
				</div>
				<div class="carousel-caption row align-items-center">
					<div class="text-center col-12 ">
						<h5 class="text-uppercase display-2">
							look better than the neighbors!<br>
							REYES PRESSURE WASHING
						</h5>
						<p class="h4">
							CALL US TODAY FOR YOUR FREE QUOTE!
						</p>
						<div class="row justify-content-center align-items-center">
							<div class="m-1">
								<a onClick="gtag('event', 'click', { event_category: 'PhoneEvent', event_action: 'Tap', event_label: 'PhoneCall'});" href="tel:+1-<?= $phoneNumbers[0] ?>" class="btn btn-cta">
									<i class="fas fa-phone"></i> Call Us: <br class="d-block d-sm-none"><?= $phoneNumbers[0] ?>
								</a>
							</div>
							<div class="m-1 d-lg-none">
								<a href="/contact-us" class="btn btn-cta">
									Get A Free Online Quote
								</a>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<svg viewBox="0 0 600 49.2" class="wave-1">
		<path>
			<animate 
				attributeName="d" 
      			dur="6000ms" 
      			repeatCount="indefinite"
				values="
						M600,49.2H0V33.9c0,0,82.7-28,150-28s79.8,28,150,28s64.4,0,150,0s150,0,150,0V49.2z;
						
						M600,49.2H0V33.9c0,0,82.7-1,150-1s79.8-18,150-18s64.4,19,150,19s150,0,150,0V49.2z;
						
						M600,49.2H0V33.9c0,0,82.7-1,150-1s79.8,1,150,1s64.4-31,150-31s150,31,150,31V49.2z;
						
						M600,49.2H0V33.9c0,0,82.7-1,150-1s79.8-18,150-18s64.4,19,150,19s150,0,150,0V49.2z;
						
						M600,49.2H0V33.9c0,0,82.7-28,150-28s79.8,28,150,28s64.4,0,150,0s150,0,150,0V49.2z;"
					 /> 
		</path>
	</svg>
</section>
<!-- END BANNER -->
