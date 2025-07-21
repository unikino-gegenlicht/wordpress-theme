<?php

namespace GGL\customizer\socialMedias;

/**
 * This file contains all customizer settings regarding the social medias displayed over the page
 */

const section = 'social-medias';

function customizer(\WP_Customize_Manager $wp_customize): void {

	$wp_customize->add_section( section, array(
		'title'    => __( 'Social Medias', 'gegenlicht' ),
		'capability' => 'edit_theme_options',
		'description' => __( 'Configure the Social Media Accounts displayed on the website', 'gegenlicht' ),
		'priority'   => 30,
	) );

	add_instagram_field($wp_customize);
	add_mastodon_field($wp_customize);
	add_letterboxd_field($wp_customize);

}

function add_instagram_field(\WP_Customize_Manager $wp_customize): void {
	$wp_customize->add_setting("social_medias[instagram]", array(
		'type' => 'theme_mod',
		'capability' => 'edit_theme_options',
	));

	$wp_customize->add_control("social_medias[instagram]", array(
		'label'    => __( 'Instagram Username', 'gegenlicht' ),
		'section'  => section,
		'type'     => 'text',
		'description' => __('Just put in the username here. The full URL is not required as it is generated during the page rendering', 'gegenlicht'),
	));
}

function add_mastodon_field(\WP_Customize_Manager $wp_customize): void {
	$wp_customize->add_setting("social_medias[mastodonURL]", array(
		'type' => 'theme_mod',
		'capability' => 'edit_theme_options',
	));

	$wp_customize->add_control("social_medias[mastodonURL]", array(
		'label'    => __( 'Mastodon Profile URL', 'gegenlicht' ),
		'section'  => section,
		'type'     => 'text',
		'description' => __('Please put in the full URL to the Mastodon Profile. The generation of the username only displays is done during page rendering', 'gegenlicht'),
	));
}

function add_letterboxd_field(\WP_Customize_Manager $wp_customize): void {
	$wp_customize->add_setting("social_medias[letterboxd]", array(
		'type' => 'theme_mod',
		'capability' => 'edit_theme_options',
	));

	$wp_customize->add_control("social_medias[letterboxd]", array(
		'label'    => __( 'Letterboxd Username', 'gegenlicht' ),
		'section'  => section,
		'type'     => 'text',
		'description' => __('Just put in the username here. The full URL is not required as it is generated during the page rendering', 'gegenlicht'),
	));
}