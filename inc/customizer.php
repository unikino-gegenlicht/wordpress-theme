<?php

defined('ABSPATH') || exit;
require_once 'customizer/header.php';
require_once 'customizer/frontpage.php';
require_once 'customizer/team-block.php';
require_once 'customizer/team-page.php';
require_once 'customizer/location-block.php';
require_once 'customizer/cooperations-block.php';
require_once 'customizer/cooperations-page.php';
require_once 'customizer/supporter-page.php';
require_once 'customizer/social-medias.php';
require_once 'customizer/youth-protection.php';
require_once 'customizer/movie-archive.php';
require_once 'customizer/anonymization.php';
use GGL\customizer;
use function GGL\customizer\custom_controls;

function configure_customizer( WP_Customize_Manager $wp_customize): void {
	custom_controls();

	//$wp_customize->get_panel('nav_menus')->priority = 999;
	$wp_customize->remove_section( 'custom_css' );



	customizer\title_tagline_section($wp_customize);
	customizer\customize_frontpage_section($wp_customize);
	customizer\teamBlock\customize_team($wp_customize);
	customizer\teamPage\customize_team($wp_customize);
	customizer\locationBlock\customize_location($wp_customize);
	customizer\cooperationsBlock\customize($wp_customize);
	customizer\cooperationsPage\customize($wp_customize);
	customizer\supporterPage\customize($wp_customize);
	customizer\socialMedias\customizer($wp_customize);
	customizer\youthProtection\customizer($wp_customize);
	customizer\movieArchive\customizer($wp_customize);
	customizer\anonymization\customizer($wp_customize);
	//ggl_add_customizer_options($wp_customize);
}

function ggl_add_customizer_options(WP_Customize_Manager $wp_customize): void
{



	$wp_customize->add_panel('frontpage', array(
		'title' => esc_html__('Frontpage', 'gegenlicht'),
		'priority' => 5,
		'capability' => 'edit_theme_options',
		'description' => esc_html__('Control the contents shown on the front page. Select a semester to be shown and the special programs displayed', 'gegenlicht'),
	));

	$wp_customize->add_section('semester', array(
		'title' => esc_html__('Displayed Semester', 'gegenlicht'),
		'panel' => 'frontpage',
		'priority' => 5,
		'capability' => 'edit_theme_options',
		'description' => esc_html__('Display the selected semester on the front page', 'gegenlicht'),
	));



	$wp_customize->add_section('special-programs', array(
		'title' => esc_html__('Special Programs', 'gegenlicht'),
		'panel' => 'frontpage',
		'priority' => 10,
		'capability' => 'edit_theme_options',
		'description' => esc_html__('Display the selected special programs on the front page below the semester progrem', 'gegenlicht'),
	));

	$wp_customize->add_section('frontpage_team', array(
		'title' => esc_html__('Team Block', 'gegenlicht'),
		'panel' => 'frontpage',
		'priority' => 15,
		'capability' => 'edit_theme_options',
		'description' => esc_html__('Display the selected team block on the front page', 'gegenlicht'),
	));

	$wp_customize->add_setting('front_page_team_image', array(
		'type'       => 'theme_mod',
		'capability' => 'edit_theme_options',
	));
	$wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'front_page_team_image', array(
		'label'   => 'Front Page Team Image',
		'section' => 'frontpage_team',
		'panel'   => 'frontpage',
	)));

	$wp_customize->add_setting('fp_team_block_color', array(
		'type' => 'theme_mod',
		'capability' => 'edit_theme_options',
	));
	$wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'fp_team_block_color', array(
		'label'   => esc_html__('Background Color', 'gegenlicht'),
		'section' => 'frontpage_team',
	)));

	$wp_customize->add_setting('fp_team_text_color', array(
		'type' => 'theme_mod',
		'capability' => 'edit_theme_options',
	));
	$wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'fp_team_text_color', array(
		'label'   => esc_html__('Text Color', 'gegenlicht'),
		'section' => 'frontpage_team',
	)));

	$wp_customize->add_setting('fp_team_block_color_dark', array(
		'type' => 'theme_mod',
		'capability' => 'edit_theme_options',
	));
	$wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'fp_team_block_color_dark', array(
		'label'   => esc_html__('Background Color (Dark)', 'gegenlicht'),
		'section' => 'frontpage_team',
	)));

	$wp_customize->add_setting('fp_team_text_color_dark', array(
		'type' => 'theme_mod',
		'capability' => 'edit_theme_options',
	));
	$wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'fp_team_text_color_dark', array(
		'label'   => esc_html__('Text Color (Dark)', 'gegenlicht'),
		'section' => 'frontpage_team',
	)));

	$wp_customize->add_section('frontpage_location', array(
		'title' => esc_html__('Location Block', 'gegenlicht'),
		'panel' => 'frontpage',
		'priority' => 15,
		'capability' => 'edit_theme_options',
		'description' => esc_html__('Edit the content of the location block', 'gegenlicht'),
	));

	$wp_customize->add_setting('fp_location_image', array(
		'type'       => 'theme_mod',
		'capability' => 'edit_theme_options',
	));
	$wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'fp_location_image', array(
		'label'   => esc_html__('Map Image', 'gegenlicht'),
		'description' => esc_html__('The selected image will be displayed over a black background. Tip: Try to use transparent images for the best effect', 'gegenlicht'),
		'section' => 'frontpage_location',
		'panel'   => 'frontpage',
	)));

	$wp_customize->add_setting('fp_location_text_en', array(
		'type' => 'theme_mod',
		'capability' => 'edit_theme_options',
	));
	$wp_customize->add_control('fp_location_text_en', array(
		'label'   => esc_html__('Location Description (English)', 'gegenlicht'),
		'description' => esc_html__('This text will be displayed below the selected map image', 'gegenlicht'),
		'section' => 'frontpage_location',
		'type' => 'textarea'
	));
	$wp_customize->add_setting('fp_location_text_de', array(
		'type' => 'theme_mod',
		'capability' => 'edit_theme_options',
	));
	$wp_customize->add_control('fp_location_text_de', array(
		'label'   => esc_html__('Location Description (German)', 'gegenlicht'),
		'description' => esc_html__('This text will be displayed below the selected map image', 'gegenlicht'),
		'section' => 'frontpage_location',
		'type' => 'textarea'
	));

	$wp_customize->add_setting('fp_location_block_color', array(
		'type' => 'theme_mod',
		'capability' => 'edit_theme_options',
	));
	$wp_customize->add_control('fp_location_block_color', array(
		'label'   => esc_html__('Background Color', 'gegenlicht'),
		'section' => 'frontpage_location',
		'type' => 'color'
	));

	$wp_customize->add_setting('fp_location_text_color', array(
		'type' => 'theme_mod',
		'capability' => 'edit_theme_options',
	));
	$wp_customize->add_control('fp_location_text_color', array(
		'label'   => esc_html__('Text Color', 'gegenlicht'),
		'section' => 'frontpage_location',
		'type' => 'color'
	));

	$wp_customize->add_setting('fp_location_block_color_dark', array(
		'type' => 'theme_mod',
		'capability' => 'edit_theme_options',
	));
	$wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'fp_location_block_color_dark', array(
		'label'   => esc_html__('Background Color (Dark)', 'gegenlicht'),
		'section' => 'frontpage_location',
	)));

	$wp_customize->add_setting('fp_location_text_color_dark', array(
		'type' => 'theme_mod',
		'capability' => 'edit_theme_options',
	));
	$wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'fp_location_text_color_dark', array(
		'label'   => esc_html__('Text Color (Dark)', 'gegenlicht'),
		'section' => 'frontpage_location',
	)));

	$wp_customize->add_setting('location_detail_page', array(
		'type' => 'theme_mod',
		'capability' => 'edit_theme_options',
	));
	$wp_customize->add_control('location_detail_page', array(
		'label'   => esc_html__('Detail Page', 'gegenlicht'),
		'description' => esc_html__('The selected page should contain a more detailed description about the location of the GEGENLICHT', 'gegenlicht'),
		'section' => 'frontpage_location',
		'type' => 'dropdown-pages',
	));

	foreach (_get_special_programs() as $id => $name) {
		$wp_customize->add_setting('displayed_special_programs[' . $id . ']', array(
			'type' => 'option',
			'capability' => 'edit_theme_options',
		));

		$wp_customize->add_control('displayed_special_programs[' . $id . ']', array(
			'label'   => $name,
			'section' => 'special-programs',
			'type'     => 'checkbox',
		));
	}





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
	$wp_customize->add_setting('anonymized_movie_explainer_en', array(
		'type'       => 'theme_mod',
		'capability' => 'edit_theme_options',
	));
	$wp_customize->add_control('anonymized_movie_explainer_en', array(
		'label'   => esc_html__('Explanation for Anonymization (English)', 'gegenlicht'),
		'section' => 'anonymization',
		'description' => esc_html__("This text will be displayed below the posts anonymous image, if a movie's details may not be displayed", 'gegenlicht' ),
	));
	$wp_customize->add_setting('anonymized_movie_explainer_de', array(
		'type'       => 'theme_mod',
		'capability' => 'edit_theme_options',
	));
	$wp_customize->add_control('anonymized_movie_explainer_de', array(
		'label'   => esc_html__('Explanation for Anonymization (German)', 'gegenlicht'),
		'description' => esc_html__("This text will be displayed below the posts anonymous image, if a movie's details may not be displayed", 'gegenlicht' ),
		'section' => 'anonymization',
	));


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

function _get_semesters(): array {
	$terms = get_terms(array(
		'taxonomy' => 'semester',
		'hide_empty' => false,
	));

	$output = array();

	foreach ( $terms as $term ) {
		$output[$term->term_id] = $term->name;
	}

	return $output;
}

function _get_special_programs(): array {
	$terms = get_terms(array(
		'taxonomy' => 'special-program',
		'hide_empty' => false,
	));

	$output = array();

	foreach ( $terms as $term ) {
		$output[$term->term_id] = $term->name;
	}

	return $output;
}

function block_team_enabled() {
	return GGL\customizer\teamBlock\block_team_enabled() && is_front_page();
}

function block_location_enabled() {
	return GGL\customizer\locationBlock\block_location_enabled() && is_front_page();
}

function block_cooperations_enabled() {
	return GGL\customizer\cooperationsBlock\block_cooperations_enabled() && is_front_page();
}

function page_cooperations_enabled() {
	return GGL\customizer\cooperationsPage\page_cooperations_enabled();
}

function page_supporter_enabled() {
	return GGL\customizer\supporterPage\page_supporter_enabled();
}