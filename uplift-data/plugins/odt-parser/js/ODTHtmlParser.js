let breadcrumbCounter = 0;
let pluginFolderUri = $("#plugin-folder-uri").val();

function showSpinner(){
	$(".non-spinner").hide();
	$(".spinner").show();
}

function hideSpinner(){
	$(".non-spinner").show();
	$(".spinner").hide();
}

function renderContents(obj){
	$("#parsed-content-file-stuff").show();
	$("#odt-file-box").hide();

	let headEditor = ace.edit("head-ace-editor");
	let bodyEditor = ace.edit("body-ace-editor");

	headEditor.setValue(obj.headContent, -1);
	bodyEditor.setValue(obj.parsedContent, -1);

	$("#page-uri").val(obj.filePathToCreate);

	// Let's try and guess the page name based on the URI

	// Remove /text/ if the URI is like -> /text/othertext
	let multiBranchExpression = /\/.+\/.+/; // Tests for /sad/sdfs/dfsd/keepsMe
	if (multiBranchExpression.test(obj.filePathToCreate) === true){
		// Remove that crap
		obj.filePathToCreate = obj.filePathToCreate.replace(/\/.+\/(.+)/, "$1");
	}

	let pageName = obj.filePathToCreate.replace(/\//g, "");
	pageName = pageName.replace(/\-/g, " ");
	pageName = pageName.toLowerCase().replace(/\b[a-z]/g, function(firstWordLetter){
		return firstWordLetter.toUpperCase();
	});

	$("#page-name").val(pageName);
}

function backToUploader(){
	$("#page-build-success").hide();
	$("#parsed-content-file-stuff").hide();
	$("#odt-file-box").show();
}

$(".back-to-uploader").on("click", backToUploader);

function uploadContentFile(file){
	let fData = new FormData();
	fData.append("content-file", file, file.name);

	$.ajax({
		type:"post",
		data:fData,
		url:pluginFolderUri + "/Controllers/parseODTFile.php",
		cache:false,
		contentType:false,
		processData:false,
		success:function(r){
			hideSpinner();
			if (typeof(r) == "object"){
				if (r.status == 1){
					// Yay cool it worked...
					// Is the content right tho? idk man
					$("#odt-file-box").hide();
					renderContents(r.fileData);
				}else if (r.status == -1){
					showError(r.error);
				}else{
					showError("This file contained malformed content. Please review the ODT file to determine if there is a writer error.");
				}
			}else{
				showError("This file contained malformed content. Please review the ODT file to determine if there is a writer error.");
			}
		},
		error:function(r){
			showError("This file contained malformed content. Please review the ODT file to determine if there is a writer error.");
		}
	})
}

$("#odt-file-box").on("drop", function(e){
	e.preventDefault(); // Do not open the file in the browser

	let originalEvent = e.originalEvent;

	if (originalEvent.dataTransfer.items){
		$.each(originalEvent.dataTransfer.items, function(index, item){
			if (item.kind === "file"){
				let file = item.getAsFile();
				let match = file.name.match(/\.([\w]+)$/);
				let fileExtension = match[1];
				if (fileExtension){
					if (fileExtension == "odt"){
						showSpinner();
						uploadContentFile(file);
					}else{
						alert("Can only upload ODT files.");
					}
				}
			}
		});
	}

	$(this).removeClass("drag-over");
});

// Prevent browser default drag over behavior
$("#odt-file-box").on("dragover", function(e){
	e.preventDefault(); // Do not open the file in the browser
	$(this).addClass("drag-over");
});

$("#odt-file-box").on("dragleave", function(e){
	e.preventDefault();

	// Do not remove the drag-over if the client "dragleaves" on a child of this box
	if ($(e.target).closest($(this)).length > 0 && e.target !== this){
		return;
	}

	$(this).removeClass("drag-over");
});

function showError(msg){
	$("#modal-error").html(msg);
	$("#error-modal").modal("show");
}

function showWarning(msg){
	$("#modal-warning").html(msg);
	$("#warning-modal").modal("show");
}

function checkFields(){
	let pageName = $("#page-name").val();

	if (pageName.trim().length == 0){
		showError("You must provide a page name");
		return false;
	}

	return true;
}

function submitForm(form){
	if (checkFields() === true){
		$("#page-head").val(ace.edit("head-ace-editor").getValue());
		$("#page-content").val(ace.edit("body-ace-editor").getValue());
		let formData = new FormData(form);
		$.ajax({
			type:"post",
			data:formData,
			url:pluginFolderUri + "/Controllers/buildPage.php",
			cache:false,
			contentType:false,
			processData:false,
			success:function(r){
				if (typeof(r) == "object"){
					if (r.status == 1){
						$("#view-in-editor").attr('href', r.editUri);
						$("#parsed-content-file-stuff").hide();
						$("#page-build-success").show();
					}else{
						if ("overwrite" in r) {
							// If overwrite is set in the json error, we ask if the user wishes to overwrite a page
							showWarning(r.error);
						} else {
							// If no extra parameters are sent with the json error, simply display the error message
							showError(r.error);
						}
					}
				}
			}
		});
	}
}

$("#parsed-content-file-stuff").on("submit", function(e){
	e.preventDefault();
	submitForm(this);
});

$("#page-overwrite-confirm").on("click", function(e){
	//console.log("Overwrite confirm clicked. the hidden input allow-overwrites set to: " + $("#allow-overwrites").val());
	$("#allow-overwrites").val("1");
	submitForm($("#parsed-content-file-stuff").get(0));
	$("#allow-overwrites").val("0");
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

	// Connect event to prefill button
	item.find(".prefill-crumb-data").on("click", () => {
		const label = item.find(".label");
		const uri = item.find(".uri");

		label.val($("#page-name").val().trim());
		uri.val($("#page-uri").val().trim());
	});
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

addBreadcrumb("Home", "/");
