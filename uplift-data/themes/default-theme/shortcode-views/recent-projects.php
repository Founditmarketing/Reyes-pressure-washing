<div class="card-deck">
	{{ begin RecentProjectItem }}
	<div class="card mx-auto">
		<picture>
			<img src="{{ FEATURED_IMAGE_NOTHUMB }}" class="card-img-top" alt="{{ PROJECT_TITLE }}">
		</picture>
		<div class="card-body text-primary">
			<h2 class="h4">
				<a href="{{ PAGE_URI }}">{{ PROJECT_TITLE }}</a>
			</h2>
			<p>{{ PROJECT_PREVIEW }}</p>
		</div>
		<div class="card-footer">
			<div class="mb-0">
				<a href="{{ PAGE_URI }}" class="btn btn-cta">
					Find Out More
				</a>
			</div>
		</div>
	</div>
	{{ end RecentProjectItem }}
</div>
