<?php
function ggl_button_shortcode( $atts, $content = null ) {
	$atts = array_change_key_case( $atts );

	$options = shortcode_atts( array(
		'external' => false,
        'backgroundColor' => 'var(--bumla-body-background-color)',
        'textColor' => 'var(--bumla-body-text-color)',
	), $atts );

	?>
    <a class="button is-fullwidth is-uppercase is-size-5 " href="" style="background-color: <?= $options['backgroundColor'] ?> !important; color: <?= $options['textColor'] ?> !important; ">
        <span class="has-text-weight-bold"><?= $content ?></span>
        <span class="material-symbols ml-1">open_in_new</span>
    </a>
<?php
}