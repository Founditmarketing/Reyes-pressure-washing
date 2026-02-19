<div class="align-items-center mx-auto mb-4" paginate elements-per-page="{{ PER_PAGE_LIMIT }}" page-container-classes="row" paginator-controls-classes="text-start" paginator-controls-button-classes="btn btn-sm btn-primary mx-1 mx-lg-3" paginator-controls-location="top" paginator-controls-input-classes="me-2 mr-2 form-control form-control-sm text-center d-inline-block" paginator-controls-div-classes="d-inline-block">
    {{ begin ProjectsItem }}
	<article>
		<div class="row align-items-center p-3">
			<div class="col-md-4 col-12">
				<div class="text-center p-3">
					<a href="{{ PAGE_URI }}">
						<img class="" alt="{{ PROJECT_TITLE }}" src="{{ FEATURED_IMAGE_NOTHUMB }}" />
					</a>
				</div>
			</div>
			<div class="col-md-8 col-12">
				<h2><a href="{{ PAGE_URI }}">{{ PROJECT_TITLE }}</a></h2>
				<p>{{ PROJECT_PREVIEW }}</p>
				<a href="{{ PAGE_URI }}" class="btn btn-more">Read More</a>
			</div>
		</div>
	</article>
	{{ end ProjectsItem }}
</div>