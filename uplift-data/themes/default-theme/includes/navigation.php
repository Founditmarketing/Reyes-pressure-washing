
	<a class="skip-main" href="#content">Skip to main content</a>
	<!-- START SMALL NAVIGATION -->
<nav class="d-block d-xl-none container-fluid" id="small-navigation-container-1">
	<div class="row py-2" id="small-navigation-top-bar">
		<div class="col">
			<a href="/contact-us" class="btn btn-cta">
				Get an Estimate! <!-- More specific messaging is better! -->
			</a>
		</div>
		<div class="col">
			<div class="text-center">
				<a id="small-navigation-menu-opener-1">
					<img alt="Hamburger Menu" src="/uplift-data/images/layout/menu_hamburger_white.svg" width="36">
				</a>
			</div>
		</div>
	</div>
	<div class="row align-items-center">
		<div class="col-12 text-center pt-2">
			<a href="/">
				<picture>
					<source type="image/webp" srcset="/uplift-data/images/layout/logo-m.webp">
					<img src="/uplift-data/images/layout/logo.png" alt="<?= $companyName ?> Logo">
				</picture>
			</a>
		</div>
		<div class="col-12 py-2 px-sm-8 px-sm-5">
			<p class="text-center h5 text-primary mb-2 text-white">
				Get Your Free Estimate Today!<!-- More specific messaging is better! -->
			</p>
			<a onClick="gtag('event', 'click', { event_category: 'PhoneEvent', event_action: 'Tap', event_label: 'PhoneCall'});" href="tel:+1-<?= $phoneNumbers[0] ?>" class="btn btn-more w-100 d-flex align-items-center justify-content-center text-white">
				<svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" fill="currentColor" class="bi bi-hand-index-thumb-fill mr-1" viewBox="0 0 16 16">
					<path d="M8.5 1.75v2.716l.048-.002c.311-.012.74-.016 1.05.046.28.056.543.18.738.288.274.152.456.385.56.642l.132-.012c.312-.024.794-.038 1.158.108.37.148.689.487.88.716.075.09.141.175.195.248h.582a2 2 0 0 1 1.99 2.199l-.272 2.715a3.5 3.5 0 0 1-.444 1.389l-1.395 2.441A1.5 1.5 0 0 1 12.42 16H6.118a1.5 1.5 0 0 1-1.342-.83l-1.215-2.43L1.07 8.589a1.517 1.517 0 0 1 2.373-1.852L5 8.293V1.75a1.75 1.75 0 0 1 3.5 0z"/>
				</svg>
				Click to Call: <br class="d-sm-none"><?= $phoneNumbers[0] ?>
			</a>
		</div>
	</div>
	<div class="pullout-menu-backdrop position-fixed d-none flex-direction-column justify-content-end">
		<div>
			<div class="menu-heading">
				<span>Menu</span>
			</div>
			<div class="navigation-links">
				<div>
					<a href="/">Home</a>
				</div>
				<div>
					<a href="/reviews">Reviews</a>
				</div>
				<div>
					<a href="/pressure-washing-company">
						Services <span class="float-right"><i class="fas fa-plus"></i></span>
					</a>
					<div class="tap-dropdown">
						<div>
							<a href="/pressure-washing-company">Services</a>
						</div>
						<div>
							<a href="/house-washing">House Washing</a>
						</div>
						<div>
							<a href="/roof-cleaning">Roof Cleaning</a>
						</div>
						<div>
							<a href="/driveway-washing">Driveway Washing</a>
						</div>
						<div>
							<a href="/sidewalk-cleaning">Sidewalk Cleaning</a>
						</div>
						<div>
							<a href="/patio-washing">Patio Washing</a>
						</div>
						<div>
							<a href="/gutter-cleaning">Gutter Cleaning</a>
						</div>
						<div>
							<a href="/parking-lot-striping">Parking Lot Striping</a>
						</div>
						<div>
							<a href="/pressure-washing-company/fence-staining-and-sealing">Fence Staining and Sealing</a>
						</div>
					</div>
				</div>
				<div>
					<a href="/about-us">
						About <span class="float-right"><i class="fas fa-plus"></i></span>
					</a>
					<div class="tap-dropdown">
						<div>
							<a href="/about-us">About</a>
						</div>
						<div>
							<a href="/faq">FAQ</a>
						</div>
						<div>
							<a href="/near-me">Service Area</a>
						</div>
					</div>
				</div>
				<div>
					<a href="/projects">Photos</a>
				</div>
				<div>
					<a href="/pressure-washing-tips">Tips</a>
				</div>
				<div>
					<a href="/contact-us">Contact</a>
				</div>
			</div>
		</div>
	</div>
</nav>
<!-- END SMALL NAVIGATION -->
<!-- START LARGE NAVIGATION -->
<nav class="container-fluid" id="lg-nav-v2-1">
	<section class="row bigger-wrap align-items-center mx-auto nav-lg">
		<div class="col" id="nav-v2-1-logo-container">
			<a href="/">
				<picture>
					<source type="image/webp" srcset="/uplift-data/images/layout/logo.webp">
					<img src="/uplift-data/images/layout/logo.png" alt="<?= $companyName ?> Logo" width="300">
				</picture>
			</a>
		</div>
		<div class="col-8" id="nav-v2-1-contents">
			<div class="row">
				<div class="col-12 d-flex justify-content-end align-items-center mx-auto pr-4 py-2">
					<div class="text-right">
						<p class="mb-0 mr-3">
							<a href="https://m.facebook.com/pages/category/Home-Improvement/Reyes-Pressure-Washing-343263389641227/" target="_blank">
								<i class="fab fa-facebook fa-2x m-2 text-primary"></i>
							</a>
							<a href="https://www.instagram.com/armando_reyes_rpw/" target="_blank">
								<i class="fab fa-instagram fa-2x m-2 text-primary"></i>
							</a>
							<!--<a href="#">-->
							<!--	<i class="fab fa-youtube fa-2x m-2 text-primary"></i>-->
							<!--</a>-->
						</p>
					</div>
					<div class="mr-3">
						<span class="f-prime h1 text-dark text-uppercase mb-0 pb-0">
							<a href="tel:+1-<?= $phoneNumbers[0] ?>" class="call-num">
								<?= $phoneNumbers[0] ?>
							</a>
						</span>
					</div>
					<div class="mr-0">
						<a href="/contact-us" class="btn btn-cta">
							Get a Free Quote
						</a>
					</div>
				</div>
			</div>
			<div class="row" id="nav-v2-1-links">
				<div class="col nav-v2-1-link">
					<div class="nav-v2-1-load-line" role="presentation"></div>
					<a href="/">
						<span>Home</span>
					</a>
				</div>
				<div class="col nav-v2-1-link">
					<div class="nav-v2-1-load-line" role="presentation"></div>
					<a href="/reviews">
						<span>Reviews</span>
					</a>
				</div>
				<div class="col nav-v2-1-link">
					<div class="nav-v2-1-load-line" role="presentation"></div>
					<a href="/pressure-washing-company">
						<span>Services</span>
						<span class="nav-v2-1-spin-chevron">
							<i class="fas fa-sort-down"></i>
						</span>
					</a>
					<div class="nav-v2-1-dropdown">
						<div class="nav-v2-1-dropdown-1-link">
							<div class="nav-v2-1-border-animator" role="presentation"></div>
							<a href="/house-washing">House Washing</a>
						</div>
						<div class="nav-v2-1-dropdown-1-link">
							<div class="nav-v2-1-border-animator" role="presentation"></div>
							<a href="/roof-cleaning">Roof Cleaning</a>
						</div>
						<div class="nav-v2-1-dropdown-1-link">
							<div class="nav-v2-1-border-animator" role="presentation"></div>
							<a href="/driveway-washing">Driveway Washing</a>
						</div>
						<div class="nav-v2-1-dropdown-1-link">
							<div class="nav-v2-1-border-animator" role="presentation"></div>
							<a href="/sidewalk-cleaning">Sidewalk Cleaning</a>
						</div>
						<div class="nav-v2-1-dropdown-1-link">
							<div class="nav-v2-1-border-animator" role="presentation"></div>
							<a href="/patio-washing">Patio Washing</a>
						</div>
						<div class="nav-v2-1-dropdown-1-link">
							<div class="nav-v2-1-border-animator" role="presentation"></div>
							<a href="/gutter-cleaning">Gutter Cleaning</a>
						</div>
						<div class="nav-v2-1-dropdown-1-link">
							<div class="nav-v2-1-border-animator" role="presentation"></div>
							<a href="/parking-lot-striping">Parking Lot Striping</a>
						</div>
						<div class="nav-v2-1-dropdown-1-link">
							<div class="nav-v2-1-border-animator" role="presentation"></div>
							<a href="/pressure-washing-company/fence-staining-and-sealing">Fence Staining and Sealing</a>
						</div>
					</div>
				</div>
				<div class="col nav-v2-1-link">
					<div class="nav-v2-1-load-line" role="presentation"></div>
					<a href="/about-us">
						<span>About</span>
						<span class="nav-v2-1-spin-chevron">
							<i class="fas fa-sort-down"></i>
						</span>
					</a>
					<div class="nav-v2-1-dropdown">
						<div class="nav-v2-1-dropdown-1-link">
							<div class="nav-v2-1-border-animator" role="presentation"></div>
							<a href="/near-me">Service Area</a>
						</div>
						<div class="nav-v2-1-dropdown-1-link">
							<div class="nav-v2-1-border-animator" role="presentation"></div>
							<a href="/faq">FAQ</a>
						</div>
					</div>
				</div>
				<div class="col nav-v2-1-link">
					<div class="nav-v2-1-load-line" role="presentation"></div>
					<a href="/projects">
						<span>Photos</span>
					</a>
				</div>
				<div class="col nav-v2-1-link">
					<div class="nav-v2-1-load-line" role="presentation"></div>
					<a href="/pressure-washing-tips">
						<span>Tips</span>
					</a>
				</div>
				<div class="col nav-v2-1-link">
					<div class="nav-v2-1-load-line" role="presentation"></div>
					<a href="/contact-us">
						<span>Contact</span>
					</a>
				</div>
			</div>
		</div>
	</section>
</nav>
<!-- END LARGE NAVIGATION -->
