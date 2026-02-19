(function(){
	let elements = $("[paginate]");

	/**
	* Paginates children
	*
	* @param jQuery parent element
	*/
	function paginate(parent){
		let columns = parseInt(parent.attr("columns"), 10);
		let colClasses = parent.attr("classes");
		let paginatorClasses = parent.attr("paginator-classes");
		let buttonClasses = parent.attr("paginator-button-classes");
		let maxPerPage = parseInt(parent.attr("elements-per-page"), 10);
		let paginatorLocation = parent.attr("paginator-location");
		let elements = parent.children();
		let articlesContainer = $(document.createElement("div"));
		let paginatorCurrentPage = 1;

		if (buttonClasses == undefined || buttonClasses == ""){
			buttonClasses = "btn btn-primary btn-sm";
		}

		if (colClasses == undefined || colClasses == ""){
			colClasses = "";
		}

		articlesContainer.addClass("paginator-pages-container");

		if (isNaN(columns)){
			columns = 1
		}

		if (isNaN(maxPerPage)){
			maxPerPage = 10;
		}

		let totalElements = parent.children().length

		if (totalElements == 0){
			return;
		}

		let columnClasses = "";
		let columnClassAppend = "";
		let columnBreakpoint = "xl";

		if (12 % columns === 0){
			columnClassAppend = "-" + String(12 / columns);
		}

		let neededPages = Math.ceil(totalElements / maxPerPage);
		let currentPageContainers = [];

		for (let i = 1; i <= neededPages; i++){
			let newPage = $(document.createElement("div"));

			if (i > 1){
				newPage.hide();
			}

			let row = $(document.createElement("div"));
			row.addClass("row");
			newPage.append(row);
			currentPageContainers.push(newPage);
			articlesContainer.append(newPage);
		}

		let currentPageIndex = 0;
		let counter = 0;

		for (let i = 0; i < totalElements; i++){
			if (counter >= maxPerPage){
				counter = 0;
				++currentPageIndex;
			}

			let columnItem = $(document.createElement("div"));
			let classString = "col-" + columnBreakpoint + columnClassAppend + " " + colClasses;
			columnItem.addClass(classString.trim());
			columnItem.appendTo(currentPageContainers[currentPageIndex].children(".row"));
			$(elements[i]).appendTo(columnItem);
			++counter;
		}

		articlesContainer.appendTo(parent);

		let pageButtonsContainer = $(document.createElement("div"));
		pageButtonsContainer.addClass("paginator-button-controls-container d-flex align-items-center");
		if (paginatorClasses !== undefined){
			pageButtonsContainer.addClass(paginatorClasses);
		}

		let prevButton = $(document.createElement("button"));
		prevButton.attr("type", "button");
		prevButton.addClass(buttonClasses); //addClass("btn btn-primary btn-sm")
		prevButton.html("Prev");
		pageButtonsContainer.append(prevButton);

		let pageIdentifierContainer = $(document.createElement("div"));
		pageIdentifierContainer.addClass("d-flex align-items-center mx-2");
		pageButtonsContainer.append(pageIdentifierContainer);

		let pageInput = $(document.createElement("input"));
		pageInput.val(paginatorCurrentPage);
		pageInput.addClass("form-control form-control-sm text-center");
		pageInput.css({
			"max-width":"45px",
			"width:":"45px"
		});
		pageIdentifierContainer.append(pageInput);

		let pageNumberSpan = $(document.createElement("span"));
		pageNumberSpan.addClass("ml-1");
		pageNumberSpan.html("of " + neededPages);
		pageIdentifierContainer.append(pageNumberSpan);

		let nextButton = $(document.createElement("button"));
		nextButton.attr("type", "button");
		nextButton.addClass(buttonClasses);//.addClass("btn btn-primary btn-sm")
		nextButton.html("Next");
		pageButtonsContainer.append(nextButton);

		nextButton.on("click", function(){
			if (paginatorCurrentPage < neededPages){
				currentPageContainers[paginatorCurrentPage-1].hide();
				++paginatorCurrentPage;
			}
			currentPageContainers[paginatorCurrentPage-1].show();
			pageInput.val(paginatorCurrentPage);
		});

		prevButton.on("click", function(){
			if (paginatorCurrentPage > 1){
				currentPageContainers[paginatorCurrentPage-1].hide();
				--paginatorCurrentPage;
			}
			currentPageContainers[paginatorCurrentPage-1].show();
			pageInput.val(paginatorCurrentPage);
		});

		pageInput.on("keydown", function(e){
			if (e.keyCode == 13){
				let attemptedPage = parseInt($(this).val().trim(), 10);
				if (!isNaN(attemptedPage)){
					if (attemptedPage >= 1 && attemptedPage <= neededPages){
						currentPageContainers[paginatorCurrentPage-1].hide();
						paginatorCurrentPage = attemptedPage;
						currentPageContainers[paginatorCurrentPage-1].show();
					}
				}
				e.stopPropagation();
				e.preventDefault();
				return false;
			}
		});

		if (paginatorLocation == undefined || paginatorLocation == "" || paginatorLocation == "top"){
			parent.prepend(pageButtonsContainer);
		} else if (paginatorLocation == "both"){
			let bottomContainer = pageButtonsContainer;
			let topContainer = bottomContainer.clone(1);
			
			parent.append(bottomContainer);
			topContainer.prependTo(articlesContainer);

			let bottomContainerInput = bottomContainer.children("div").children("input");
			let topContainerInput = topContainer.children("div").children("input");
			
			bottomContainer.children("button").on("click",function(){
				topContainerInput.val(bottomContainerInput.val());
			});
			topContainer.children("button").on("click",function(){
				topContainerInput.val(bottomContainerInput.val());
			});

			// Update containers if a value is manually entered
			bottomContainerInput.on("keydown",function(){
				topContainerInput.val(bottomContainerInput.val());
			});
			topContainerInput.on("keydown",function(){
				bottomContainerInput.val(topContainerInput.val());
			});

		} else if (paginatorLocation == "bottom"){
			parent.append(pageButtonsContainer);
		}
		
	}

	elements.each(function(){
		paginate($(this));
	});
})();
