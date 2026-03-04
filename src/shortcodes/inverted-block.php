<?php

function ggl_inverted_block_shortcode( $atts, $content = null ) {
	$atts    = array_change_key_case( $atts );
	$options = shortcode_atts( array(
		'adjustDarkMode' => true,
	), $atts );

	$blkID = random_int( 0, PHP_INT_MAX );

	return '<div style=" margin: -0.25rem;
                padding: 1rem 0.25rem;
                --bulma-body-background-color: black;
                --bulma-body-color: var(--bulma-primary);
                background-color: var(--bulma-body-background-color);
                color: var(--bulma-body-color)" id="' . $blkID . '">' . $content . '
                </div>';
}