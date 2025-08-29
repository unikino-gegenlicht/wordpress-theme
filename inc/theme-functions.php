<?php
/**
 * GEGENLICHT Theme Functions
 *
 * The functions in this file are used to shorten the code in other places and make it
 * easier to maintain
 */


/**
 * Get advertisable movies and events
 *
 * @param int $semesterID
 *
 * @return WP_Post[] Movies and Events that shall be advertised for on the frontpage
 */
function ggl_get_advertisements(int $semesterID): array {
	$posts = [];

	$args = array(
		"post_type"      => [ "movie", "event" ],
		"posts_per_page" => 1,
		"meta_key"       => "screening_date",
		"orderby"        => "meta_value_num",
		"order"          => "ASC",
		"tax_query" => [[
			"taxonomy" => "semester",
			"terms" => $semesterID,
		]]
	);

	$metaQuery =  [[
		"key" => "screening_date",
		"value" => time(),
		"compare" => ">="
	]];

	$query = new WP_Query(array_merge($args, ["meta_query" => $metaQuery]));
	if (!$query->have_posts()) {
		return [];
	}

	$query->the_post();
	$posts[] = $query->post;

	$metaQuery =  [[
		"key" => "screening_date",
		"value" => [
			(int) rwmb_get_value("screening_date") + 1,
			strtotime("+1 hours", (int) rwmb_get_value("screening_date")) ?: 0,
		],
		"compare" => "BETWEEN"
	]];

	wp_reset_postdata();


	$query = new WP_Query(array_merge($args, ["meta_query" => $metaQuery, "posts_per_page" => -1]));
	while ($query->have_posts()) {
		$query->the_post();
		$posts[] = $query->post;
	}
	wp_reset_postdata();


	return $posts;
}

function is_location_page(): bool {
	return (get_post()->ID ?? -2) === (int) get_theme_mod( 'location_detail_page' );
}

function is_impress_page(): bool {
	return (get_post()->ID ?? -2) == get_theme_mod( 'impress_page' );
}

function is_contact_page(): bool {
	return (get_post()->ID ?? -2) == get_theme_mod( 'contact_page' );
}