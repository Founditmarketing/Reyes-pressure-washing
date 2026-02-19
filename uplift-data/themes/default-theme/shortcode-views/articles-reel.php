<div class="align-items-center mx-auto mb-4" paginate elements-per-page="{{ PER_PAGE_LIMIT }}" page-container-classes="row" paginator-controls-classes="text-start" paginator-controls-button-classes="btn btn-sm btn-primary mx-1 mx-lg-3" paginator-controls-location="top" paginator-controls-input-classes="me-2 mr-2 form-control form-control-sm text-center d-inline-block" paginator-controls-div-classes="d-inline-block">
    {{ begin PageItem }}
	<article>
		<div class="row align-items-center p-3">
			<div class="col-md-4 col-12">
				<div class="text-center p-3">
					<a href="{{ ARTICLE_URI }}">
						<img class="" alt="{{ ARTICLE_TITLE }}" src="{{ FEATURED_IMAGE_NOTHUMB }}" />
					</a>
				</div>
			</div>
			<div class="col-md-8 col-12">
				<h2 class=""><a class="" href="{{ ARTICLE_URI }}">{{ ARTICLE_TITLE }}</a></h2>
				<p>{{ ARTICLE_PREVIEW }}</p>
				<p class="">
					<a class="btn btn-more" href="{{ ARTICLE_URI }}">Read More</a>
				</p>
			</div>
		</div>
	</article>
	{{ end PageItem }}
</div>