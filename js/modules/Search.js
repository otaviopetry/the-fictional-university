import $ from 'jquery';

class Search {
	// 1. The constructor is where we describe and create/initiate our object
    constructor() {
		// Select the DOM objects
		this.openButton = $(".js-search-trigger");
		this.closeButton = $(".search-overlay__close");
		this.searchOverlay = $(".search-overlay");

		// Fire event listeners when the Search object is created
		this.events();
	}
	
	// 2. Secondly, we list all events that will trigger actions
	events() {
		this.openButton.on('click', this.openOverlay.bind(this));
		this.closeButton.on('click', this.closeOverlay.bind(this));
	}

	
	// 3. Finally, the methods that execute the actions
	openOverlay() {
		this.searchOverlay.addClass("search-overlay--active");
	}

	closeOverlay() {
		this.searchOverlay.removeClass("search-overlay--active");
	}
}

export default Search;