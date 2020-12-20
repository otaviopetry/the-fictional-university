import axios from 'axios';
class MyNotes {
	constructor () {
		axios.defaults.headers.common["X-WP-Nonce"] = universityData.nonce
		this.deleteButtons = document.querySelectorAll('.delete-note');
		this.editButtons = document.querySelectorAll('.edit-note');

		this.events();			
	}

	events () {
		this.deleteButtons.forEach((button) => {
			button.addEventListener('click', this.deleteNote);
		})
		this.editButtons.forEach((button) => {
			button.addEventListener('click', this.editNote);
		})
	}

	// Methods will go below
	editNote (event) {
		const thisNote = event.target.closest('li');

		thisNote.querySelectorAll('.note-title-field, .note-body-field').forEach(field => {
			field.removeAttribute('readonly');
			field.classList.add('note-active-field');
		});
		thisNote.querySelector('.update-note').classList.add('update-note--visible')
	}

	async deleteNote (event) {
		const thisNote = event.target.closest('li');

		await axios.delete(`${universityData.root_url}/wp-json/wp/v2/note/${thisNote.dataset.id}`)
			.then(response => {
				console.log('Note deleted successfully.');
				console.log(response);
				setTimeout(() => {
					thisNote.classList.add('fade-out');
				}, 100)
			}).catch(error => {
				console.log('Something went wrong.');
				console.log(error);
			})
	}
}

export default MyNotes;