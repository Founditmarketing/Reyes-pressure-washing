<div class="gallery">
	{{ begin GalleryImageItem }}
	<a href="{{ ImageUri }}" data-lightbox="{{ GalleryName }}">
		<img alt="{{ ImageAlt }}" src="{{ ThumbUri }}">
	</a>
	{{ end GalleryImageItem }}
</div>