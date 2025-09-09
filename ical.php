<?php
/**
 * This file is responsible for generating the main iCalendar feed
 *
 * The feed includes movies and events that will happen in the future and that happened
 * up to 4 weeks ago.
 */
if ( ! function_exists( 'ggl_cpt__generate_single_ical' ) || ! function_exists( 'ggl_cpt__serialize_icals' ) ) {
	wp_safe_redirect( home_url(), 307 );
}

$all = new WP_Query( [
	"posts_per_page" => - 1,
	"post_type"      => [ "movie", "event" ],
] );

$events = [];
while ( $all->have_posts() ) : $all->the_post();
	$screeningDate = (int) rwmb_get_value( "screening_date", post_id: $all->post->ID );
	if ( time() < strtotime( "4 weeks", $screeningDate ) ):
		$events[] = ggl_cpt__generate_single_ical( $all->post );
	endif;
endwhile;

header( "{$_SERVER["SERVER_PROTOCOL"]} 200 OK" );
header( "Content-type: text/calendar; charset=utf-8" );
header( 'Content-Disposition: attachment; filename="Unikino GEGENLICHT.ics"' );
headers_send();

echo ggl_cpt__serialize_icals( $events, false );

exit();
