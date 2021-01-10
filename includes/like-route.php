<?php

add_action('rest_api_init', 'universityLikeRoutes');

function universityLikeRoutes () {
	register_rest_route('university/v1', 'manageLike', array(
		'methods' => 'POST',
		'callback' => 'createLike'
	));

	register_rest_route('university/v1', 'manageLike', array(
		'methods' => 'DELETE',
		'callback' => 'deleteLike'
	));
}

function createLike ($request) {
	if (is_user_logged_in()) {
		$professor = sanitize_text_field($request['professorId']);

		$checkUserLike = new WP_Query(array(
			'author' => get_current_user_id(),
			'post_type' => 'like',
			'meta_query' => array(
				array(
					'key' => 'liked_professor_id',
					'compare' => '=',
					'value' => $professor
				)
			)
		));

		if ($checkUserLike->found_posts == 0 AND get_post_type($professor) == 'professor') {
			return wp_insert_post(array(
				'post_type' => 'like',
				'post_status' => 'publish',
				'post_title' => 'Liked professor',
				'meta_input' => array(
					'liked_professor_id' => $professor
				)
			));
		} else {
			die('Invalid professor ID.');
		}
	} else {
		die("Only logged in users can like a professor.");
	}
}

function deleteLike ($request) {
	$likeId = sanitize_text_field($request['like']);

	if (get_current_user_id() == get_post_field('post_author', $likeId) AND get_post_type($likeId) == 'like') {
		wp_delete_post($likeId, true);
		return 'Like deleted.';
	} else {
		die('You do not have permission to delete that.');
	}
}