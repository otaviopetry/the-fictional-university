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
			button.addEventListener('click', this.editNote.bind(this));
		})
	}

	// Methods will go below
	editNote (event) {
		const thisNote = event.target.closest('li');

		if (thisNote.dataset.state == 'editable') {
			this.makeNoteReadOnly(thisNote);
		} else {
			this.makeNoteEditable(thisNote);
		}
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

	makeNoteEditable (thisNote) {
		thisNote.querySelector('.edit-note').innerHTML = `
			<i class="fa fa-times" aria-hidden="true"></i> Cancel
		`;
		thisNote.querySelectorAll('.note-title-field, .note-body-field').forEach(field => {
			field.removeAttribute('readonly');
			field.classList.add('note-active-field');
		});
		thisNote.querySelector('.update-note').classList.add('update-note--visible');
		thisNote.setAttribute('data-state', 'editable');
	}

	makeNoteReadOnly (thisNote) {
		thisNote.querySelector('.edit-note').innerHTML = `
			<i class="fa fa-pencil" aria-hidden="true"></i> Edit
		`;
		thisNote.querySelectorAll('.note-title-field, .note-body-field').forEach(field => {
			field.setAttribute('readonly', 'readonly');
			field.classList.remove('note-active-field');
		});
		thisNote.querySelector('.update-note').classList.remove('update-note--visible');
		thisNote.removeAttribute('data-state');
	}
}

export default MyNotes;