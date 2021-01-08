import axios from 'axios';

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

	async createLike () {
		try {
			const response = await axios.post(universityData.root_url + '/wp-json/university/v1/manageLike');

			console.log(response);
		} catch (error) {
			console.log(error);
		}
	}

	async deleteLike () {
		try {
			const response = await axios.delete(universityData.root_url + '/wp-json/university/v1/manageLike');

			console.log(response);
		} catch (error) {
			console.log(error);
		}
	}
}

export default Like;