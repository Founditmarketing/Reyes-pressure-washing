$(document).on("click", "a", function(e){
	e.preventDefault();
	e.stopPropagation();
	if ($(this).hasClass("carousel-control-prev-icon")){
		// Do not show the popup for carousel control arrows
		return;
	}
	$("#preview-only-notice").modal("show");
});
