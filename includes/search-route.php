<?php

add_action('rest_api_init', 'universityRegisterSearch');

function universityRegisterSearch () {
	register_rest_route('university/v1', 'search', array(
		'methods' => WP_REST_SERVER::READABLE,
		'callback' => 'universitySearchResults'
	)); // (namespace, route, parameters)
}

function universitySearchResults ($request) {
	$mainQuery = new WP_Query(array(
		'post_type' => array('post', 'page', 'professor', 'program', 'campus', 'event'),
		's' => sanitize_text_field($request['term'])
	));

	$response = array(
		'generalInfo' => array(),
		'professors' => array(),
		'programs' => array(),
		'events' => array(),
		'campuses' => array()
	);

	while ($mainQuery->have_posts()) {
		$mainQuery->the_post();

		if (get_post_type() == 'post' OR get_post_type() == 'page') {
			array_push($response['generalInfo'], array(
				'title' => get_the_title(),
				'permalink' => get_the_permalink(),
				'postType' => get_post_type(),
				'author' => get_the_author()
			));	
		}

		if (get_post_type() == 'professor') {
			array_push($response['professors'], array(
				'title' => get_the_title(),
				'permalink' => get_the_permalink(),
				'image' => get_the_post_thumbnail_url(0, 'professorLandscape')
			));	
		}

		if (get_post_type() == 'program') {
			array_push($response['programs'], array(
				'title' => get_the_title(),
				'permalink' => get_the_permalink()
			));	
		}

		if (get_post_type() == 'event') {
			$eventDate = new DateTime(get_field('event_date'));
			$description = NULL;

			if (has_excerpt()) {
				// use get_the_excerpt here to deal with markup ourselves
				$description = get_the_excerpt();
			} else {
				$description = wp_trim_words(get_the_content(), 18);
			}

			array_push($response['events'], array(
				'title' => get_the_title(),
				'permalink' => get_the_permalink(),
				'month' => $eventDate->format('M'),
				'day' => $eventDate->format('d'),
				'description' => $description
			));	
		}

		if (get_post_type() == 'campus') {
			array_push($response['campuses'], array(
				'title' => get_the_title(),
				'permalink' => get_the_permalink()
			));	
		}
	}

	return $response;
}