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

/**
 * Get the title of a post
 *
 * This function supersedes the inbuilt `get_the_title()` function as it automatically handles the anonymization for
 * events and movies. Additionally, the function automatically handles the retrieval of the german or english title.
 * If a other type of post is used the function will just return the result of the inbuilt function.
 *
 * @param WP_Post|int $post Defaults to global $post. The post the title should be loaded from
 *
 * @return string
 */
function ggl_get_title(WP_Post|int $post = 0): string {
	$post = get_post($post);

	if ($post->post_type !== "movie" && $post->post_type !== "event") {
		return get_the_title($post);
	}

	$anonymize = (rwmb_get_value("license_type") != "full" && !is_user_logged_in());
	if (!$anonymize) {
		return get_locale() == "de" ? rwmb_meta( 'german_title' ) : rwmb_meta( 'english_title' );
	}

	$inSpecialProgram = rwmb_meta( 'program_type' ) == 'special_program';
	if ($inSpecialProgram) {
		return rwmb_get_value( 'special_program' )->name;
	}

	if ($post->post_type === "event") {
		return __( "An unnamed event", "gegenlicht" );
	}

	return __( "An unnamed movie", "gegenlicht" );
}

function ggl_get_translate_rating_descriptor(string $descriptorKey): string {
	$descriptors = [
		'sexualized_violence' => esc_html__( 'Sexualized Violence', 'gegenlicht' ),
		'violence'            => esc_html__( 'Violence', 'gegenlicht' ),
		'self_harm'           => esc_html__( 'Self Harm', 'gegenlicht' ),
		'drug_usage'          => esc_html__( 'Drug Usage', 'gegenlicht' ),
		'discrimination'      => esc_html__( 'Discrimination', 'gegenlicht' ),
		'sexuality'           => esc_html__( 'Sexuality', 'gegenlicht' ),
		'threat'              => esc_html__( 'Threat', 'gegenlicht' ),
		'injury'              => esc_html__( 'Injury', 'gegenlicht' ),
		'stressful_topics'    => esc_html__( 'Stressful Topics', 'gegenlicht' ),
		'language'            => esc_html__( 'Language', 'gegenlicht' ),
		'nudeness'            => esc_html__( 'Nudeness', 'gegenlicht' ),
		'risky_behaviour'     => esc_html__( 'Risky Behaviour', 'gegenlicht' ),
		'marginalization'     => esc_html__( 'Marginalization', 'gegenlicht' ),
	];
	if ( array_key_exists( $descriptorKey, $descriptors ) ) {
		return $descriptors[ $descriptorKey ];
	}

	return $descriptorKey;
}