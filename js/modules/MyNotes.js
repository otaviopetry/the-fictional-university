import $ from 'jquery';

class MyNotes {
	constructor () {
		this.events();			
	}

	events () {
		$('.delete-note').on('click', this.deleteNote);
	}

	// Methods will go below
	deleteNote () {
		$.ajax({
			beforeSend: (xhr) => {
				xhr.setRequestHeader('X-WP-Nonce', universityData.nonce);
			},
			url: universityData.root_url + '/wp-json/wp/v2/note/98',
			type: 'DELETE',
			success: (response) => {
				console.log('Note deleted sucessfully.');
				console.log(response);
			},
			error: (error) => {
				console.log('Something went wrong.');
				console.log(error);
			}
		})
	}
}

export default MyNotes;