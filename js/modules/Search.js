import $ from 'jquery';

class Search {
	// 1. The constructor is where we describe and create/initiate our object
    constructor() {
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
		this.resultsDiv.html("Imagine real search results here...");
		this.isSpinnerVisible = false;
	}

	typingLogic() {
		if (this.searchField.val() != this.previousValue) {
            clearTimeout(this.typingTimer);
            
            if (this.searchField.val()) {
                if (!this.isSpinnerVisible) {
                    this.resultsDiv.html('<div class="spinner-loader"></div>');
                    this.isSpinnerVisible = true;
                }
                this.typingTimer = setTimeout(this.getResults.bind(this), 1200);                
            } else {
                this.resultsDiv.html('');
                this.isSpinnerVisible = false;
            }						
		}

		this.previousValue = this.searchField.val();
	}
}

export default Search;