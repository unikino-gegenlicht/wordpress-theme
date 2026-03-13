<?php
function ggl_location_button_shortcode( $atts ) {
	$atts = array_change_key_case( $atts );

	$options = shortcode_atts( array(
		'screening_location_id' => - 1,
		'lat'                   => 0,
		'long'                  => 0,
		'place_name'            => "",
		'street'                => "",
		'postal_code'           => "",
		'city'                  => "",
		"google_place_id"       => null,
		"apple_place_id"        => null,
	), $atts );

	ob_start();

	get_template_part( 'src/partials/location-button', args: $options );

	return ob_get_clean();
}