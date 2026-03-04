<?php
function ggl_button_shortcode( $atts, $content = null ) {
	$atts = array_change_key_case( $atts );

	$options = shortcode_atts( array(
		'external' => false,
        'backgroundColor' => 'inherit',
        'textColor' => 'inheritŝ',
        'link' => "",
	), $atts );
	return '<div class="py-1"><a class="button is-fullwidth is-uppercase is-size-5 is-outlined my-4" target="_blank" href="'.$options['link'].'">
        <span class="has-text-weight-bold">'. $content .'</span>
        <span class="material-symbols ml-1">open_in_new</span>
    </a></div>';
}