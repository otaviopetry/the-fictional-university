import $ from 'jquery';

class Search {
	// 1. The constructor is where we describe and create/initiate our object
    constructor() {
		this.addSearchOverlayHTML();
		// Select the DOM objects
		this.openButton = $(".js-search-trigger");
		this.closeButton = $(".search-overlay__close");
		this.searchOverlay = $(".search-overlay");
		this.searchField = $("#search-term");
		
		this.isOpen = false;

		this.typingTimer;
		this.resultsDiv = $("#search-overlay__results");
		this.isSpinnerVisible = false;
		this.previousValue;

		// Fire event listeners when the Search object is created
		this.events();
	}
	
	// 2. Secondly, we list all events that will trigger actions
	events() {
		this.openButton.on('click', this.openOverlay.bind(this));
		this.closeButton.on('click', this.closeOverlay.bind(this));

		$(document).on('keydown', this.keyPressDispatcher.bind(this));

		this.searchField.on('keyup', this.typingLogic.bind(this));
	}

	
	// 3. Finally, the methods that execute the actions
	openOverlay() {
		this.searchOverlay.addClass("search-overlay--active");
		$("body").addClass("body-no-scroll");
		this.searchField.val('');
		setTimeout(() => document.querySelector("#search-term").focus(), 600);
		console.log('Our open method just ran.');
		this.isOpen = true;
	}

	closeOverlay() {
		this.searchOverlay.removeClass("search-overlay--active");
		$("body").removeClass("body-no-scroll");
		console.log('Our close method just ran.');
		this.isOpen = false;
	}

	keyPressDispatcher(event) {
		if (event.keyCode === 83 && !this.isOpen && !$("input, textarea").is(':focus')) {
			this.openOverlay();
		}

		if (event.keyCode == 27 && this.isOpen) {
			this.closeOverlay();
		}
	}

	getResults() {
		$.when(
			// Each request will be outputed to its correspondent parameter in the anonymous function on .then(), in order
			$.getJSON(`${universityData.root_url}/wp-json/wp/v2/posts?search=${this.searchField.val()}`), 
			$.getJSON(`${universityData.root_url}/wp-json/wp/v2/pages?search=${this.searchField.val()}`)
		).then((posts, pages) => {
			// The when method passes along with JSON data ([0]) information about the requests
			const combinedResponse = posts[0].concat(pages[0]);
			this.resultsDiv.html(`
				<h2 class="search-overlay__section-title">General Information</h2>
				${combinedResponse.length ? '<ul class="link-list min-list">' : '<p>No results found.</p>'  }				
					${combinedResponse.map(thePost => `
						<li>
							<a href="${thePost.link}">${thePost.title.rendered}</a>
							${thePost.authorName ? ` by ` + thePost.authorName : ''}
						</li>
					`).join('')}
				${combinedResponse.length ? '</ul>' : ''}
			`);
			this.isSpinnerVisible = false;
		}, () => {
			// Second parameter is an error handling function
			this.resultsDiv.html('<p>Unexpected error. Please try again.</p>');
		});
	}

	typingLogic() {
		if (this.searchField.val() != this.previousValue) {
            clearTimeout(this.typingTimer);
            
            if (this.searchField.val()) {
                if (!this.isSpinnerVisible) {
                    this.resultsDiv.html('<div class="spinner-loader"></div>');
                    this.isSpinnerVisible = true;
                }
                this.typingTimer = setTimeout(this.getResults.bind(this), 600);                
            } else {
                this.resultsDiv.html('');
                this.isSpinnerVisible = false;
            }						
		}

		this.previousValue = this.searchField.val();
	}

	addSearchOverlayHTML() {
		$("body").append(`
			<div class="search-overlay">
				<div class="search-overlay__top">
					<div class="container">
						<i class="fa fa-search search-overlay__icon" aria-hidden="true"></i>
						<input type="text" class="search-term" placeholder="What are you looking for?" id="search-term" />
						<i class="fa fa-window-close search-overlay__close" aria-hidden="true" autocomplete="off"></i>
					</div>
				</div>
				
				<div class="container">
					<div id="search-overlay__results"></div>
				</div>
			</div>
		`);
	}
}

export default Search;