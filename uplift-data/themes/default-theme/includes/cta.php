<?php $h3 = CMSInternals::addClassesToElement($h3, [""]); ?>

<!-- START CTA -->
<section class="row bg-secondary overno">
	<div class="col-xl-10 col-lg-11 col-12 text-center mx-auto p-5">
		<div>
			<h2 class="text-white display-4">
				CALL REYES PRESSURE WASHING TODAY FOR TOP-QUALITY PRESSURE WASHING IN HOUSTON!
			</h2>
			<div class="row align-items-center justify-content-center">
				<div class="m-1">
					<a href="/contact-us" class="btn btn-more animated-fade-in-left">
						Get a Free Estimate
					</a>
				</div>
				<div class="m-1">
					<a onClick="gtag('event', 'click', { event_category: 'PhoneEvent', event_action: 'Tap', event_label: 'PhoneCall'});" href="tel:+1-<?= $phoneNumbers[0] ?>" class="btn btn-more animated-fade-in-right">
						<i class="fas fa-phone"></i> Give us a call today!
					</a>
				</div>
			</div>
		</div>
	</div>
</section>
<!-- END CTA -->
