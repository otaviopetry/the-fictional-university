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
	$professor = sanitize_text_field($request['professorId']);

	wp_insert_post(array(
		'post_type' => 'like',
		'post_status' => 'publish',
		'post_title' => 'Professooooooor',
		'meta_input' => array(
			'liked_professor_id' => $professor
		)
	));
}

function deleteLike () {
	return 'Thanks for trying to DELETE a like.';
}