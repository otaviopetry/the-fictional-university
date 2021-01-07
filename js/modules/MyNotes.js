import axios from 'axios';
class MyNotes {
	constructor () {
		axios.defaults.headers.common["X-WP-Nonce"] = universityData.nonce
		this.deleteButtons = document.querySelectorAll('.delete-note');
		this.editButtons = document.querySelectorAll('.edit-note');
		this.saveButtons = document.querySelectorAll('.update-note');
		this.submitButton = document.querySelector('.submit-note');

		this.events();			
	}

	events () {
		document.querySelector('#my-notes').addEventListener('click', (event) => {
			if (event.target.classList.contains('delete-note')) {
				this.deleteNote(event);
			}
			if (event.target.classList.contains('edit-note')) {
				this.editNote(event);
			}
			if (event.target.classList.contains('update-note')) {
				this.updateNote(event);
			}
		})
		
		this.submitButton.addEventListener('click', this.createNote.bind(this));
	}

	// Methods
	async createNote (event) {
		const newNoteTitleInput = document.querySelector('.new-note-title');
		const newNoteTitleBody = document.querySelector('.new-note-body');
		const notesFeed = document.querySelector('#my-notes');

		const newNote = {
			'title': newNoteTitleInput.value,
			'content': newNoteTitleBody.value,
			'status': 'private'
		}

		await axios.post(`${universityData.root_url}/wp-json/wp/v2/note/`, newNote)
			.then(response => {
				if (response.data == 'You have reached your post limit') {
					document.querySelector('.note-limit-message').classList.add('active');
					return;
				}
				console.log('Note saved successfully.');
				console.log(response);
				newNoteTitleInput.value = '';
				newNoteTitleBody.value = '';

				const showNewNote = document.createElement('li');
				showNewNote.setAttribute('data-id', response.data.id);
				showNewNote.innerHTML = `
					<input readonly class="note-title-field" type="text" value="${response.data.title.raw}">
					<span class="edit-note"><i class="fa fa-pencil" aria-hidden="true"></i> Edit</span>
					<span class="delete-note"><i class="fa fa-pencil" aria-hidden="true"></i> Delete</span>
					<textarea readonly class="note-body-field">${response.data.content.raw}</textarea>
					<span class="update-note btn btn--blue btn--small"><i class="fa fa-arrow-right" aria-hidden="true"></i> Save</span>
				`;
	
				notesFeed.prepend(showNewNote);
			}).catch(error => {
				console.log('Something went wrong.');
				console.log(error);
			})	
	}

	editNote (event) {
		const thisNote = event.target.closest('li');

		if (thisNote.dataset.state == 'editable') {
			this.makeNoteReadOnly(thisNote);
		} else {
			this.makeNoteEditable(thisNote);
		}
	}

	async updateNote (event) {
		const thisNote = event.target.closest('li');

		const updatedNote = {
			'title': thisNote.querySelector('.note-title-field').value,
			'content': thisNote.querySelector('.note-body-field').value
		}

		await axios.post(`${universityData.root_url}/wp-json/wp/v2/note/${thisNote.dataset.id}`, updatedNote)
			.then(response => {
				console.log('Note updated successfully.');
				console.log(response);
				this.makeNoteReadOnly(thisNote);				
			}).catch(error => {
				console.log('Something went wrong.');
				console.log(error);
			})
	}

	async deleteNote (event) {
		const thisNote = event.target.closest('li');

		await axios.delete(`${universityData.root_url}/wp-json/wp/v2/note/${thisNote.dataset.id}`)
			.then(response => {
				console.log('Note deleted successfully.');
				console.log(response);
				setTimeout(() => {
					thisNote.classList.add('fade-out');
					document.querySelector('.note-limit-message').classList.remove('active');
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