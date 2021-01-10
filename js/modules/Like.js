import axios from 'axios';

class Like {
	constructor () {
		axios.defaults.headers.common["X-WP-Nonce"] = universityData.nonce
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

		if (currentLikeBox.getAttribute('data-exists') == 'yes') {
			this.deleteLike(currentLikeBox);
		} else {
			this.createLike(currentLikeBox);
		}
	}

	async createLike (currentLikeBox) {
		try {
			const response = await axios.post(universityData.root_url + '/wp-json/university/v1/manageLike', {
				'professorId': currentLikeBox.dataset.professor
			});
			console.log(response);

			// Change HTML attribute to turn the heart red
			currentLikeBox.dataset.exists = 'yes';
			
			// Get the ammount of current likes and ++
			const likeCountField = currentLikeBox.querySelector('.like-count');
			let likeCount = parseInt(Number(likeCountField.innerHTML), 10);
			likeCount++;
			likeCountField.innerHTML = likeCount;

			// Inform HTML the ID of the created like post
			currentLikeBox.dataset.like = response.data;
		} catch (error) {
			console.log(error);
		}
	}

	async deleteLike (currentLikeBox) {
		try {
			const response = await axios.delete(universityData.root_url + '/wp-json/university/v1/manageLike', {
				params: {
					'like': currentLikeBox.dataset.like
				}
			});
			console.log(response);

			// Change HTML attribute to turn the heart red
			currentLikeBox.dataset.exists = 'no';
			
			// Get the ammount of current likes and ++
			const likeCountField = currentLikeBox.querySelector('.like-count');
			let likeCount = parseInt(Number(likeCountField.innerHTML), 10);
			likeCount--;
			likeCountField.innerHTML = likeCount;

			// Inform HTML the ID of the created like post
			currentLikeBox.dataset.like = '';
		} catch (error) {
			console.log(error);
		}
	}
}

export default Like;