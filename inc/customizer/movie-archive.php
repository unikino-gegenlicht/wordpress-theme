<?php
/**
 * Youth Protection Customizer
 *
 * This file sets up the customizer section regarding the Youth Protection.
 */


namespace GGL\customizer\movieArchive;

const section = 'movie-archive';

function customizer( $wp_customize ): void {
	$wp_customize->add_section( section, array(
		'title'    => __( 'Archive Page', 'gegenlicht' ),
		'priority' => 30,
		'description' => __( 'Configure the display of the movie and event archive', 'gegenlicht' ),
	) );

	add_archive_header($wp_customize);
	add_archive_text($wp_customize);
}


function add_archive_header( $wp_customize ): void {
	$wp_customize->add_setting( "archive_header[de]", array(
		'sanitize_callback' => 'sanitize_text_field',
		'type'              => 'theme_mod',
		'capability'        => 'edit_others_posts',
	) );

	$wp_customize->add_setting( "archive_header[en]", array(
		'sanitize_callback' => 'sanitize_text_field',
		'type'              => 'theme_mod',
		'capability'        => 'edit_others_posts',
	) );


	$wp_customize->add_control( 'archive_header[de]', array(
		'label'   => __( 'Page Header (German)', 'gegenlicht' ),
		'type'    => 'text',
		'section' => section,
	));
	$wp_customize->add_control( 'archive_header[en]', array(
		'label'   => __( 'Page Header (English)', 'gegenlicht' ),
		'type'    => 'text',
		'section' => section,
	));

}

function add_archive_text( $wp_customize ): void {
	$wp_customize->add_setting( "archive_text[de]", array(
		'sanitize_callback' => 'wp_kses_post',
		'type'              => 'theme_mod',
	) );

	$wp_customize->add_setting( "archive_text[en]", array(
		'sanitize_callback' => 'wp_kses_post',
		'type'              => 'theme_mod',
	) );

	$wp_customize->add_control( 'archive_text[de]', array(
		'label'   => __( 'Page Header (German)', 'gegenlicht' ),
		'type'    => 'text',
		'section' => section,
	));

	$wp_customize->add_control( new \WP_Customize_Code_Editor_Control( $wp_customize, 'archive_text[de]', array(
		'label'       => __( 'Page Content (German)', 'gegenlicht' ),
		'section'     => section,
		'code_type'   => 'text/html',
		'settings'    => 'archive_text[de]',
	)));

	$wp_customize->add_control( new \WP_Customize_Code_Editor_Control( $wp_customize, 'archive_text[en]', array(
		'label'       => __( 'Page Content (English)', 'gegenlicht' ),
		'section'     => section,
		'code_type'   => 'text/html',
		'settings'    => 'archive_text[en]',
	)));



}