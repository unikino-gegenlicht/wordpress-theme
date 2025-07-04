<?php

defined('ABSPATH') || exit;

function ggl_add_customizer_options($wp_customize): void
{
	$wp_customize->remove_section( 'custom_css' );
	$wp_customize->remove_section( 'static_front_page' );

	$wp_customize->add_setting( 'header_logo', array(
		'type'       => 'theme_mod',
		'capability' => 'edit_theme_options',
	) );
	$wp_customize->add_control( new WP_Customize_Media_Control( $wp_customize, 'header_logo', array(
		'label'   => 'Header Logo',
		'section' => 'title_tagline',
	) ) );

	$wp_customize->add_section('anonymization', array(
		'title'    => esc_html__('Anonymization', 'gegenlicht' ),
		'priority' => 30,
		'capability' => 'edit_theme_options',
		'description' => esc_html__('Control the anonymization for movies/events that we can\'t advertise', 'gegenlicht' )
	));
	$wp_customize->add_setting( 'anonymous_image', array(
		'type'       => 'theme_mod',
		'capability' => 'edit_theme_options',
	) );
	$wp_customize->add_control( new WP_Customize_Media_Control( $wp_customize, 'anonymous_image', array(
		'label'   => esc_html__('Fallback Image',  'gegenlicht' ),
		'description' => esc_html__("Upload an image for fallback usage which is displayed on a movies detail page in case one hasn't been uploaded or the movie may not be advertised", 'gegenlicht' ),
		'section' => 'anonymization',
	) ) );


	$wp_customize->add_panel('contact_info', array(
		'title' => esc_html__('Contact & Address Information', 'gegenlicht'),
		'description' => esc_html__('In this section you can add and edit the general contact information as well as the address displayed in the footer', 'gegenlicht'),
		'priority' => 40,
		'capability' => 'edit_theme_options',
	));

	$wp_customize->add_section( 'address', array(
		'title' => esc_html__('Address', 'gegenlicht'),
		'priority' => 20,
		'panel' => 'contact_info',
		'description' => esc_html__('In this section you can add and edit the address displayed in the footer', 'gegenlicht'),
	));

	$wp_customize->add_setting('address', array(
		'type'       => 'theme_mod',
		'capability' => 'edit_theme_options',
	));
	$wp_customize->add_control('address', array(
		'label'   => esc_html__('Address', 'gegenlicht'),
		'section' => 'address',
		'type' => 'textarea',
	));

	$wp_customize->add_section( 'contact', array(
		'title' => esc_html__('Contact', 'gegenlicht'),
		'priority' => 20,
		'panel' => 'contact_info',
		'description' => esc_html__('In this section you can add and edit the general contact information', 'gegenlicht'),
	));

	$wp_customize->add_section('youth_protection', array(
		'title' => esc_html__('Youth Protection', 'gegenlicht'),
		'description' => esc_html__('Information about the Youth Protection Officer', 'gegenlicht'),
		'priority' => 50,
		'capability' => 'edit_theme_options',
	));

	$wp_customize->add_setting('ypo_name', array(
		'type'       => 'theme_mod',
		'capability' => 'edit_theme_options',
	));
	$wp_customize->add_control('ypo_name', array(
		'label'   => esc_html__('Name', 'gegenlicht'),
		'section' => 'youth_protection',
	));

	$wp_customize->add_setting('ypo_email', array(
		'type'       => 'theme_mod',
		'capability' => 'edit_theme_options',
	));
	$wp_customize->add_control('ypo_email', array(
		'label'   => esc_html__('E-Mail Address', 'gegenlicht'),
		'section' => 'youth_protection',
	));

	$wp_customize->add_section('social_media', array(
		'title' => esc_html__('Social Medias', 'gegenlicht'),
		'description' => esc_html__('You can set the usernames/urls to the commonly used social medias here', 'gegenlicht'),
		'priority' => 55,
		'capability' => 'edit_theme_options',
	));

	$wp_customize->add_setting('instagram_username', array(
		'type'       => 'theme_mod',
		'capability' => 'edit_theme_options',
	));
	$wp_customize->add_control('instagram_username', array(
		'label'   => esc_html__('Instagram Username', 'gegenlicht'),
		'section' => 'social_media',
	));

	$wp_customize->add_setting('mastodon_url', array(
		'type'       => 'theme_mod',
		'capability' => 'edit_theme_options',
	));
	$wp_customize->add_control('mastodon_url', array(
		'label'   => esc_html__('Mastodon URL', 'gegenlicht'),
		'section' => 'social_media',
		'description' => esc_html__('Please use the full url to the profile as Mastodon is decentralized', 'gegenlicht'),
	));

	$wp_customize->add_setting('letterboxd_username', array(
		'type'       => 'theme_mod',
		'capability' => 'edit_theme_options',
	));
	$wp_customize->add_control('letterboxd_username', array(
		'label'   => esc_html__('Letterboxd Username', 'gegenlicht'),
		'section' => 'social_media',
	));


	$wp_customize->add_setting('contact_email_address', array(
		'type'       => 'theme_mod',
		'capability' => 'edit_theme_options',
	));
	$wp_customize->add_control('contact_email_address', array(
		'label'   => esc_html__('E-Mail Address for Contact', 'gegenlicht'),
		'section' => 'contact',
		'type' => 'email',
	));

	$wp_customize->add_setting('contact_telephone_number', array(
		'type'       => 'theme_mod',
		'capability' => 'edit_theme_options',
	));
	$wp_customize->add_control('contact_telephone_number', array(
		'label'   => esc_html__('Phone Number', 'gegenlicht'),
		'section' => 'contact',
		'type' => 'tel',
	));

	$wp_customize->add_section('team', array(
		'title' => esc_html__('Team', 'gegenlicht'),
		'description' => esc_html__('Customize the options for the members page', 'gegenlicht'),
		'priority' => 60,
		'capability' => 'edit_theme_options',
		'active_callback' => 'is_member_archive'
	));
	$wp_customize->add_setting('team_intro_text_en', array(
		'type'       => 'theme_mod',
		'capability' => 'edit_theme_options',
	));
	$wp_customize->add_control('team_intro_text_en', array(
		'label'   => esc_html__('Introduction Text (English)', 'gegenlicht'),
		'section' => 'team',
		'type' => 'textarea',
		'description' => esc_html__('To create a new paragraph inside the text, please use a doubled line break. All other linebreaks will be removed', 'gegenlicht'),
	));

	$wp_customize->add_setting('team_intro_text_de', array(
		'type'       => 'theme_mod',
		'capability' => 'edit_theme_options',
	));
	$wp_customize->add_control('team_intro_text_de', array(
		'label'   => esc_html__('Introduction Text (German)', 'gegenlicht'),
		'section' => 'team',
		'type' => 'textarea',
		'description' => esc_html__('To create a new paragraph inside the text, please use a doubled line break. All other linebreaks will be removed', 'gegenlicht'),
	));

	$wp_customize->add_setting('team_intro_text_en', array(
		'type'       => 'theme_mod',
		'capability' => 'edit_theme_options',
	));
	$wp_customize->add_control('team_intro_text_en', array(
		'label'   => esc_html__('Introduction Text (English)', 'gegenlicht'),
		'section' => 'team',
		'type' => 'textarea',
		'description' => esc_html__('To create a new paragraph inside the text, please use a doubled line break. All other linebreaks will be removed', 'gegenlicht'),
	));

	$wp_customize->add_setting('former_members_text_en', array(
		'type'       => 'theme_mod',
		'capability' => 'edit_theme_options',
	));
	$wp_customize->add_control('former_members_text_en', array(
		'label'   => esc_html__('Thank You Note (English)', 'gegenlicht'),
		'section' => 'team',
		'type' => 'textarea',
		'description' => esc_html__('To create a new paragraph inside the text, please use a doubled line break. All other linebreaks will be removed', 'gegenlicht'),
	));

	$wp_customize->add_setting('former_members_text_de', array(
		'type'       => 'theme_mod',
		'capability' => 'edit_theme_options',
	));
	$wp_customize->add_control('former_members_text_de', array(
		'label'   => esc_html__('Thank You Note (German)', 'gegenlicht'),
		'section' => 'team',
		'type' => 'textarea',
		'description' => esc_html__('To create a new paragraph inside the text, please use a doubled line break. All other linebreaks will be removed', 'gegenlicht'),
	));

	$wp_customize->add_setting('missing_team_image_replacement', array(
		'type'       => 'theme_mod',
		'capability' => 'edit_theme_options',
	));
	$wp_customize->add_control( new WP_Customize_Media_Control( $wp_customize, 'missing_team_image_replacement', array(
		'label'   => 'Fallback Image ',
		'description' => esc_html__("This image is used a team member didn't upload an image", 'gegenlicht'),
		'section' => 'team',
	) ) );


}

function is_member_archive() {
	return is_post_type_archive('team-member');
}