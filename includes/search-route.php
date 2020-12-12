<?php

add_action('rest_api_init', 'universityRegisterSearch');

function universityRegisterSearch () {
	register_rest_route('university/v1', 'search', array(
		'methods' => WP_REST_SERVER::READABLE,
		'callback' => 'universitySearchResults'
	)); // (namespace, route, parameters)
}

function universitySearchResults () {
	return "Congratulations, you've just created your first WP Route.";
}