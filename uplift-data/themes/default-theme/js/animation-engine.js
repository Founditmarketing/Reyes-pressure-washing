/*
	This file relies on jQuery, animeJS, and inView.jquery.js
*/

if ($(".animated-expand-container").length > 0){
	$(".animated-expand-container").each(function(index, element){
		$(element).on("in-view", function(){
			anime({
				targets:$(this).children(".cover").get(0),
				width:["100%", "0%"],
				duration:450,
				easing:"easeInOutSine",
				autoplay:true
			});
		}).inViewWatcher();
	});
}

if ($(".animated-pop-in").length > 0){
	$(".animated-pop-in").each(function(index, element){
		$(element).on("in-view", function(){
			anime({
				targets:$(this).get(0),
				scale:[0, 1],
				duration:600,
				easing:"easeInOutSine",
				autoplay:true
			});
		}).inViewWatcher();
	});
}

if ($(".animated-fade-in-left").length > 0){
	$(".animated-fade-in-left").each(function(index, element){
		$(element).on("in-view", function(){
			anime({
				targets:$(this).get(0),
				translateX:[-100, 0],
				opacity:[0,1],
				duration:600,
				easing:"easeInOutSine",
				autoplay:true
			});
		}).inViewWatcher();
	});
}

if ($(".animated-fade-in-right").length > 0){
	$(".animated-fade-in-right").each(function(index, element){
		$(element).on("in-view", function(){
			anime({
				targets:$(this).get(0),
				translateX:[100, 0],
				opacity:[0,1],
				duration:600,
				easing:"easeInOutSine",
				autoplay:true
			});
		}).inViewWatcher();
	});
}
