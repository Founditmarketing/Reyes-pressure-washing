(function(){
	let bodyEditor = ace.edit($("#outputted-body-contents-html").get(0));
	let headEditor = ace.edit($("#outputted-meta-contents-html").get(0));
	let installDirectory = $("#cms-install-directory").val();
	let pluginDirectory = $("#plugin-uri").val();
	let breadcrumbCounter = 0;

	$("#flex-content-fetch-form").on("submit", function(e){
		e.preventDefault();
		var formData = new FormData(this);
		$.ajax({
			type:"post",
			url:pluginDirectory + "/Controllers/FetchFlexSite.php",
			contentType:false,
			processData:false,
			cache:false,
			data:formData,
			success:function(r){
				bodyEditor.setValue(r.html, -1);
				headEditor.setValue(r.meta, -1);
				$("#new-uri").val(r.newUri);

				if (r.images.length > 0){
					$("#content-images").removeClass("d-none").find("div").html('');
					$.each(r.images, function(i,source){
						var img = $(document.createElement("img"));
						img.attr('src', source);
						img.css({
							"max-width":"150px",
							"max-height":"150px"
						}).addClass("m-2 p-1");
						img.appendTo($("#content-images div"));
					});
				}else{
					$("#content-images").addClass("d-none");
				}


				// If it is checkmarked as being a city page, build the content
				if ($("#parsing-city-page").prop("checked")){
					let definedCityName = $("#def-city-name").val();
					let definedStateName = $("#def-state-name").val();
					let definedStateNameShorthand = $("#def-state-name-shorthand").val();
					let definedServiceAreasUri = $("#def-service-areas-uri").val();

					$("#page-name").val(definedCityName + ", " + definedStateNameShorthand);
					$("#page-type").val("City");
					$("#page-layout").val("City");
					$("#city-name").val(definedCityName);
					$("#state-name").val(definedStateName);
					$("#state-name-shorthand").val(definedStateNameShorthand);

					if ($("#breadcrumbs-container").children().length == 1){
						addBreadcrumb("Service Areas", definedServiceAreasUri);
						addBreadcrumb(definedCityName + ", " + definedStateNameShorthand, r.newUri);
					}else if ($("#breadcrumbs-container").children().length == 2){
						$($("#breadcrumbs-container").children()[1]).find(".label").val("Service Areas");
						$($("#breadcrumbs-container").children()[1]).find(".uri").val(definedServiceAreasUri);
						console.log("YES");
						addBreadcrumb(definedCityName + ", " + definedStateNameShorthand, r.newUri);
					}else if ($("#breadcrumbs-container").children().length == 3){
						$($("#breadcrumbs-container").children()[1]).find(".label").val("Service Areas");
						$($("#breadcrumbs-container").children()[1]).find(".uri").val(definedServiceAreasUri);
						$($("#breadcrumbs-container").children()[2]).find(".label").val(definedCityName + ", " + definedStateNameShorthand);
						$($("#breadcrumbs-container").children()[2]).find(".uri").val(r.newUri);
					}
				}

				
			}
		});

	});

	function addBreadcrumb(label, uri){
		let item = $("#crumb-template").clone().removeAttr('id').show();
		++breadcrumbCounter;

		if (typeof(label) === "string"){
			item.find(".label").val(label);
		}

		if (typeof(uri) === "string"){
			item.find(".uri").val(uri);
		}

		item.find(".uri").attr("name", "breadcrumbs[" + breadcrumbCounter + "][uri]");
		item.find(".label").attr("name", "breadcrumbs[" + breadcrumbCounter + "][label]");

		item.find(".remove").on("click", function(){
			--breadcrumbCounter;
			item.remove();
		});

		item.appendTo($("#breadcrumbs-container"));
	}

	$("#page-type").on("change", function(){
		if ($(this).val() == "City"){
			$("#city-page-options").show();
		}else{
			$("#city-page-options").hide();
		}
	});

	$("#add-crumb").on("click", function(){
		addBreadcrumb();
	});

	$("#submit-page-build").on("submit", function(e){
		e.preventDefault();

		let bodyEditor = ace.edit($("#outputted-body-contents-html").get(0));
		let headEditor = ace.edit($("#outputted-meta-contents-html").get(0));
		let formData = new FormData(this);

		// Add the head and body results to the packet
		formData.append("page-head", headEditor.getValue());
		formData.append("page-body", bodyEditor.getValue());
		formData.append("externally-fetched-url", $("#external-url").val());

		if ($("#submit-build-request-button").hasClass("disabled")){
			return;
		}

		$("#error").hide();
		$("#submit-build-request-button").addClass("disabled");
		$("#view-in-editor-button").hide();
		$.ajax({
			type:"post",
			url:pluginDirectory + "/Controllers/BuildPageFromFlexParser.php",
			contentType:false,
			processData:false,
			cache:false,
			data:formData,
			success:function(r){
				$("#submit-build-request-button").removeClass("disabled");
				if (typeof(r) === "object"){
					if (r.status === 1){
						$("#view-in-editor-button").show().attr('href', installDirectory + "fbm-cms/page-editor/edit/" + r.pageID);
					}else{
						$("#error").show().html(r.error);
					}
				}else{
					$("#error").show().html("A fatal error has occurred and the response was not JSON. Please use your browser's network tab before submitting another request to inspect the error.");
				}
			}
		});
	});

	$("#parsing-city-page").on("change", function(){
		if ($(this).prop("checked")){
			$("#city-page-parameters").show();
		}else{
			$("#city-page-parameters").hide();
		}
	});

	addBreadcrumb("Home", "/");
})();
