<?php

defined('ABSPATH') || exit;

function ggl_add_customizer_options($customizer): void
{
    $customizer->add_section(
        'ggl_panel',
        array(
            'title' => __('Program'),
            'description' => esc_html__('Here you can modify the settings regarding the displayed semester programme')
        )
    );
}