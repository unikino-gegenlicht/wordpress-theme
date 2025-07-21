<?php
namespace GGL\customizer\teamPage;

/**
 * This file contains all customizer settings regarding the team page and the team block on the frontpage
 */

const section = 'team-page';


function customize_team(\WP_Customize_Manager $wp_customize): void {

	$wp_customize->add_section( section, array(
		'title'    => __( 'Team (Page)', 'gegenlicht' ),
		'capability' => 'edit_theme_options',
		'description' => __( 'Configure the display of the team page and the linked block on the frontpage', 'gegenlicht' ),
		'priority'   => 30,
		'active_callback' => function() {
			return is_post_type_archive('team-member');
		},
	) );

	add_fallback_image($wp_customize);
	add_team_intro($wp_customize);
	add_thank_you($wp_customize);

}


function add_fallback_image(\WP_Customize_Manager $wp_customize): void {
	$wp_customize->add_setting('member_fallback_image', array(
		'type' => 'theme_mod',
		'capability' => 'edit_theme_options',
	));

	$control = new \WP_Customize_Media_Control( $wp_customize, 'member_fallback_image', array(
		'label' => __( 'Fallback Image', 'ggl' ),
		'section' => section,
		'description' => esc_html__('The selected image will be displayed if a team member has no photo set', 'gegenlicht'),
	));

	$wp_customize->add_control($control);
}

function add_team_intro(\WP_Customize_Manager $wp_customize): void {
	$wp_customize->add_setting( 'team_intro[de]', array(
		'type' => 'theme_mod',
		'capability' => 'edit_theme_options',
		'default' => ''
	));

	$wp_customize->add_control( 'team_intro[de]', array(
		'label'   => __( 'German Intro Text', 'gegenlicht' ),
		'type'    => 'textarea',
		'description' => esc_html__('To create a new paragraph inside the text, please use a doubled line break. All other linebreaks will be removed', 'gegenlicht'),
		'section' => section,
	));

	$wp_customize->add_setting( 'team_intro[en]', array(
		'type' => 'theme_mod',
		'capability' => 'edit_theme_options',
		'default' => ''
	));

	$wp_customize->add_control( 'team_intro[en]', array(
		'label'   => __( 'English Intro Text', 'gegenlicht' ),
		'type'    => 'textarea',
		'description' => esc_html__('To create a new paragraph inside the text, please use a doubled line break. All other linebreaks will be removed', 'gegenlicht'),
		'section' => section,
	));
}

function add_thank_you(\WP_Customize_Manager $wp_customize): void {
	$wp_customize->add_setting( 'team_thanks[de]', array(
		'type' => 'theme_mod',
		'capability' => 'edit_theme_options',
		'default' => ''
	));

	$wp_customize->add_control( 'team_thanks[de]', array(
		'label'   => __( 'Thank You Note (German)', 'gegenlicht' ),
		'type'    => 'textarea',
		'description' => esc_html__('To create a new paragraph inside the text, please use a doubled line break. All other linebreaks will be removed', 'gegenlicht'),
		'section' => section,
	));

	$wp_customize->add_setting( 'team_thanks[en]', array(
		'type' => 'theme_mod',
		'capability' => 'edit_theme_options',
		'default' => ''
	));

	$wp_customize->add_control( 'team_thanks[en]', array(
		'label'   => __( 'Thank You Note (English)', 'gegenlicht' ),
		'type'    => 'textarea',
		'description' => esc_html__('To create a new paragraph inside the text, please use a doubled line break. All other linebreaks will be removed', 'gegenlicht'),
		'section' => section,
	));
}