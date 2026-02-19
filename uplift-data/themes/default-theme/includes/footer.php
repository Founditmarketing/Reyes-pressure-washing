
<!-- START FOOTER -->
<footer class="fb-ftr-1">
	<div class="container-fluid mx-auto">
		<div class="row mx-auto p-3 p-md-5">
			<div class="col-lg-3 col-sm-6 col-12 align-self-center text-center mx-auto p-3">
				<a href="/">
					<picture>
						<source media="(max-width:568px)" type="image/webp" srcset="/uplift-data/images/layout/logo-m.webp">
						<source type="image/webp" srcset="/uplift-data/images/layout/logo.webp">
						<img src="/uplift-data/images/layout/logo.png" alt="<?= $companyName ?> Logo">
					</picture>
				</a>
				<div class="mt-4">
   
</div>
			</div>
			<div class="col-lg-3 col-sm-6 col-12 text-center text-md-left mx-auto p-3">
				<h4>Contact</h4>
				<div class="contact">
					<p itemprop="legalName">
						<?= $companyName ?>
					</p>
					<div itemprop="address" itemscope itemtype="http://schema.org/PostalAddress">
						<p itemprop="streetAddress">
							<?= $companyStreet ?>
						</p>
						<p>
							<span itemprop="addressLocality"><?= $companyCity ?></span>,
							<span itemprop="addressRegion"><?= $companyState ?></span>
							<span itemprop="postalCode"><?= $companyPostal ?></span>
						</p>
					</div>
					<p class="mt-3 mb-0">
						Phone: <a onClick="gtag('event', 'click', { event_category: 'PhoneEvent', event_action: 'Tap', event_label: 'PhoneCall'});" href="tel:+1-<?= $phoneNumbers[0] ?>" itemprop="telephone" content="<?= $phoneNumbers[0] ?>"><?= $phoneNumbers[0] ?></a>
					</p>
				</div>
			</div>
			<div class="col-lg-3 col-sm-6 col-12 text-center text-md-left mx-auto p-3">
				<h4>Quick Links</h4>
				<div class="quick-links">
					<a href="https://www.google.com/maps/place/Reyes+Pressure+Washing/@29.7692404,-94.8051145,9z/data=!3m1!4b1!4m6!3m5!1s0xa9b5dd556a31bcc7:0x69be63460edc9942!8m2!3d29.7692404!4d-94.8051144!16s%2Fg%2F11rs01nqxp?entry=ttu" target="_blank" rel="noopener">Google Maps</a>
					<a href="https://birdeye.com/reyes-pressure-washing-163112606222133/review-us?dashboard=1" target="_blank" rel="noopener">Leave a Review</a>
					<a href="/pressure-washing-tips">Articles</a>
					<a href="/privacy">Privacy Policy</a>
					<a href="/terms">Terms of Use</a>
					<a href="/sitemap">Sitemap</a>
					<a href="/rss/all.xml">RSS Feed</a>
				</div>
			</div>
			<div class="col-lg-3 col-sm-6 col-12 text-center text-md-left mx-auto p-3">
				<h4>Service Areas</h4>
				<div class="service-areas">
					<a href="/near-me/friendswood-pressure-washing">Friendswood, TX</a>
					<a href="/near-me/pressure-washing-kingwood">Kingwood, TX</a>
					<a href="/near-me/league-city-pressure-washing">League City, TX</a>
					<a href="/near-me/pressure-washing-pearland">Pearland, TX</a>
					<a href="/near-me/humble-pressure-washing">Humble, TX</a>
					<a href="/near-me/pressure-washing-baytown">Baytown, TX</a>
					<a href="/near-me/mont-belvieu-pressure-washing">Mont Belvieu, TX</a>
					<a href="/near-me/pressure-washing-alvin">Alvin, TX</a>
					<a href="/near-me/atascocita-pressure-washing">Atascocita, TX</a>
					<a href="/near-me/pressure-washing-houston">Houston, TX</a>
				</div>
				<div class="mt-3 text-right">
					<a href="/near-me">View All</a>
				</div>
			</div>
		</div>
		<div class="row p-0" style="background-color: rgba(0, 0, 0, 0.1);">
			<div class="col-12 big-wrap text-center text-md-left mx-auto p-3">
				<p class="mb-0">
					&copy;
					<?= date("Y"); ?>
					<span itemprop="name"><a itemprop="url" href="/"><?= $companyName ?></a></span>, Rights Reserved
				</p>
			</div>
		</div>
	</div>
</footer>
<!-- END FOOTER -->	
