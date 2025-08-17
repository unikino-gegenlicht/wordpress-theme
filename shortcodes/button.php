<?php
function ggl_button_shortcode( $atts, $content = null ) {
	$atts = array_change_key_case( $atts );

	$options = shortcode_atts( array(
		'external' => false,
        'backgroundColor' => 'var(--bulma-body-background-color)',
        'textColor' => 'var(--bulma-body-color)',
        'link' => "",
	), $atts );
	return '<a class="button is-fullwidth is-uppercase is-size-5 is-outlined my-4" target="_blank" href="'.$options['link'].'" style="background-color: '. $options['backgroundColor'] .' !important; color: '. $options['textColor'] .' !important; ">
        <span class="has-text-weight-bold">'. $content .'</span>
        <span class="material-symbols ml-1">open_in_new</span>
    </a>';
}