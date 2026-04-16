<?php
/**
 * GEGENLICHT Theme Functions
 *
 * The functions in this file are used to shorten the code in other places and make it
 * easier to maintain
 */


/**
 * Get all screenings that will happen from today on
 *
 *
 * @param WP_Term|int $semesterID
 *
 * @return WP_Post[] The upcoming screenings, including today's
 */
function ggl_theme__get_upcoming_screenings( WP_Term|int $semesterID ): array {
	$semester = get_term( $semesterID, taxonomy: "semester" );
	if ( ! $semester ) {
		return [];
	}

	$all_movies_in_semester = new WP_Query( [
		'post_type'      => [ "movie", "event" ],
		'posts_per_page' => - 1,
		'orderby'        => 'meta_value_num',
		'order'          => 'ASC',
		'meta_key'       => 'screening_date',
		'tax_query'      => [
			[
				'taxonomy' => 'semester',
				'terms'    => $semester->term_id,
			]
		]
	] );

	try {
		$tz = new DateTimeZone( wp_timezone_string() );
	} catch ( DateInvalidTimeZoneException $e ) {
		error_log( "Invalid timezone set in wordpress, falling back to Europe/Berlin" );
		$tz = new DateTimeZone( "Europe/Berlin" );
	}

	$now = new DateTimeImmutable( "now", $tz );


	return array_filter( $all_movies_in_semester->posts, function ( $screening ) use ( $now ) {
		$running_time  = ggl_get_running_time( $screening ) + 10;
		$movie_ends_at = ggl_get_starting_time( $screening )->add( new DateInterval( "PT{$running_time}M" ) );
		return $now <= $movie_ends_at;
	} );
}

/**
 * Get advertisable movies and events
 *
 * @param WP_Term|int $semesterID
 *
 * @return WP_Post[] Movies and Events that shall be advertised for on the frontpage
 */
function ggl_get_advertisements( WP_Term|int $semesterID ): array {
	$screenings = ggl_theme__get_upcoming_screenings( $semesterID );

	$advertisable_screenings[] = array_shift( $screenings );
	$start_of_first_screening  = ggl_get_starting_time( $advertisable_screenings[0] )->setTime( 0, 0, 0 );

	foreach ( $screenings as $screening ) {

		$screening_start = ggl_get_starting_time( $screening )->setTime( 0, 0, 0 );
		$diff            = $start_of_first_screening->diff( $screening_start );
		if ( $diff->days != 0  ) {
			continue;
		}
		$advertisable_screenings[] = $screening;
	}

	return $advertisable_screenings;
}

function is_location_page(): bool {
	return ( get_post()->ID ?? - 2 ) === (int) get_theme_mod( 'location_detail_page' );
}

function is_impress_page(): bool {
	return ( get_post()->ID ?? - 2 ) == get_theme_mod( 'impress_page' );
}

function is_contact_page(): bool {
	return ( get_post()->ID ?? - 2 ) == get_theme_mod( 'contact_page' );
}


function ggl_get_thumbnail_url( WP_Post|int $post = 0, string $size = "full" ): false|string {
	$post = get_post( $post );
	if ( $post->post_type !== "movie" && $post->post_type !== "event" ) {
		return get_the_post_thumbnail_url( $post, $size );
	}

	$fallbackImageUrl = wp_get_attachment_image_url( get_theme_mod( 'anonymous_image' ), $size );

	$show_details = apply_filters( "ggl__show_full_details", false, $post );

	if ( $show_details ) {
		return get_the_post_thumbnail_url( $post, $size ) ?: $fallbackImageUrl;
	}

	$inSpecialProgram = rwmb_meta( 'program_type' ) == 'special_program';
	if ( $inSpecialProgram ) {
		$specialProgram = rwmb_get_value( 'special_program' );
		if ( $specialProgram === false ) {
			return $fallbackImageUrl;
		}

		return wp_get_attachment_image_url( get_term_meta( $specialProgram->term_id, "anonymous_image", single: true ), $size ) ?: $fallbackImageUrl;
	}

	return $fallbackImageUrl;
}


function ggl_get_translate_rating_descriptor( string $descriptorKey ): string {
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
		'nudity'              => esc_html__( 'Nudeness', 'gegenlicht' ),
		'risky_behaviour'     => esc_html__( 'Risky Behaviour', 'gegenlicht' ),
		'marginalization'     => esc_html__( 'Marginalization', 'gegenlicht' ),
	];
	if ( array_key_exists( $descriptorKey, $descriptors ) ) {
		return $descriptors[ $descriptorKey ];
	}

	return $descriptorKey;
}

function ggl_resolve_country_list( array $selectedCountries ): array {
	require_once get_stylesheet_directory() . '/src/inc/defunct-countries.php';

	$country_list = [];
	foreach ( $selectedCountries as $numeric ) {
		try {
			$country = ( new League\ISO3166\ISO3166 )->numeric( str_pad( $numeric, 3, "0", STR_PAD_LEFT ) );
		} catch ( League\ISO3166\Exception\OutOfBoundsException $e ) {
			foreach ( $defunct_countries as $country ) {
				$comparison = mb_strtolower( $country['numeric'] );
				if ( $numeric === $comparison || $numeric === mb_substr( $comparison, 0, mb_strlen( $numeric ) ) ) {
					$country_list[] = $country["alpha2"];
				}
			}
		}
		$country_list[] = $country["alpha2"];
	}

	return $country_list;
}