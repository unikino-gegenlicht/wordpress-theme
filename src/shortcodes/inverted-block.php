<?php

function ggl_inverted_block_shortcode( $atts, $content = null ) {
	$atts    = array_change_key_case( $atts );
	$options = shortcode_atts( array(
		'adjustDarkMode' => true,
	), $atts );

	$blkID = random_int( 0, PHP_INT_MAX );

	return '<div style=" margin: -0.25rem;
                padding: 1rem 0.25rem;
                --bg: var(--bulma-body-color);
                --fg: var(--bulma-body-background-color);
                background-color: var(--bg);
                color: var(--fg)" id="' . $blkID . '"><div style="--bulma-body-color: var(--fg) !important; --bulma-body-background-color: var(--bg) !important; --bulma-content-heading-color: var(--fg);"' . $content . '
                </div></div>';
}