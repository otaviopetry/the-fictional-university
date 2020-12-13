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
		$.getJSON(`${universityData.root_url}/wp-json/university/v1/search?term=${this.searchField.val()}`, (response) => {
			this.resultsDiv.html(`
				<div class="row">
					<div class="one-third">
						<h2 class="search-overlay__section-title">General Information</h2>
						${response['generalInfo'].length ? '<ul class="link-list min-list">' : '<p>No general information matches that search.</p>'  }				
							${response['generalInfo'].map(thePost => `
								<li>
									<a href="${thePost.permalink}">${thePost.title}</a>
									${thePost.postType == 'post' ? ` by ` + thePost.author : ''}
								</li>
							`).join('')}
						${response['generalInfo'].length ? '</ul>' : ''}
					</div>
					<div class="one-third">
						<h2 class="search-overlay__section-title">Programs</h2>
						${response['programs'].length ? '<ul class="link-list min-list">' : `<p>No program matches that search. <a href="${universityData.root_url}/programs">View all programs</a></p>`  }				
							${response['programs'].map(thePost => `
								<li><a href="${thePost.permalink}">${thePost.title}</a></li>
							`).join('')}
						${response['programs'].length ? '</ul>' : ''}

						<h2 class="search-overlay__section-title">Professors</h2>
						${response['professors'].length ? '<ul class="link-list min-list">' : '<p>No professor matches that search.</p>'  }				
							${response['professors'].map(thePost => `
								<li><a href="${thePost.permalink}">${thePost.title}</a></li>
							`).join('')}
						${response['professors'].length ? '</ul>' : ''}
						
					</div>
					<div class="one-third">
						<h2 class="search-overlay__section-title">Campuses</h2>
						${response['campuses'].length ? '<ul class="link-list min-list">' : `<p>No campuses matches that search. <a href="${universityData.root_url}/campuses">View all campuses</a></p>`  }				
							${response['campuses'].map(thePost => `
								<li><a href="${thePost.permalink}">${thePost.title}</a></li>
							`).join('')}
						${response['campuses'].length ? '</ul>' : ''}
						

						<h2 class="search-overlay__section-title">Events</h2>
						${response['events'].length ? '<ul class="link-list min-list">' : '<p>No events matches that search.</p>'  }				
							${response['events'].map(thePost => `
								<li><a href="${thePost.permalink}">${thePost.title}</a></li>
							`).join('')}
						${response['events'].length ? '</ul>' : ''}
					</div>
				</div>
			`);		
		});
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