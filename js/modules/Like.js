class Like {
	constructor () {
		this.likeBox = document.querySelector('.like-box');

		this.events();		
	}

	events () {
		this.likeBox.addEventListener('click', this.ourClickDispatcher.bind(this));
	}

	// Methods
	ourClickDispatcher (event) {
		console.log(event.target);
		const currentLikeBox = event.target.closest('.like-box');

		if (currentLikeBox.dataset.exists == 'yes') {
			this.deleteLike();
		} else {
			this.createLike();
		}
	}

	createLike () {
		alert('Uhul');
	}

	deleteLike () {
		alert('Oh no');
	}
}

export default Like;