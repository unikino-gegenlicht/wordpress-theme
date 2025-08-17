<?php

defined( 'ABSPATH' ) || exit;


require_once 'customizer/front-page.php';
require_once 'customizer/block-coops.php';
require_once 'customizer/block-team.php';
require_once 'customizer/block-location.php';
require_once 'customizer/page-cooperations.php';
require_once 'customizer/page-supporters.php';
require_once 'customizer/page-team.php';
require_once 'customizer/page-location.php';
require_once 'customizer/navigation.php';
require_once "customizer/contact-social-medias.php";
require_once "customizer/contact-email.php";
require_once "customizer/contact-addresses.php";
require_once 'customizer/anonymization.php';
require_once 'customizer/semester-break.php';
require_once 'customizer/footer.php';
require_once 'customizer/youth-protection.php';

function configure_customizer( WP_Customize_Manager $wp_customize ): void {
	custom_controls();

	$wp_customize->remove_section( 'custom_css' );
	$wp_customize->remove_section( 'title_tagline' );

	new FrontPageCustomizerOverrides( $wp_customize, 21 );
	new NavigationBarCustomizer( $wp_customize, 22 );
	new SemesterBreakCustomizer( $wp_customize, priority: 23 );

	$wp_customize->add_panel( 'blocks', array(
		'title'       => esc_html__( 'Blocks', 'gegenlicht' ),
		'priority'    => 24,
		'capability'  => 'edit_others_posts',
		'description' => esc_html__( 'Edit the blocks displayed on the frontpage', 'gegenlicht' ),
	) );
	new CooperationAndSupportersBlockCustomizer( $wp_customize, priority: 1, additionalArgs: [ "panel" => "blocks" ] );
	new TeamBlockCustomizer( $wp_customize, priority: 2, additionalArgs: [ "panel" => "blocks" ] );
	new LocationBlockCustomizer( $wp_customize, priority: 3, additionalArgs: [ "panel" => "blocks" ] );

	$wp_customize->add_panel( 'pages', array(
		'title'       => esc_html__( 'Pages', 'gegenlicht' ),
		'priority'    => 25,
		'capability'  => 'edit_others_posts',
		'description' => esc_html__( 'Edit the display of some content pages of this theme', 'gegenlicht' ),
	) );
	new CooperationPageCustomizer( $wp_customize, priority: 1, additionalArgs: [ "panel" => "pages" ] );
	new SupporterPageCustomizer( $wp_customize, priority: 2, additionalArgs: [ "panel" => "pages" ] );
	new TeamPageCustomizer( $wp_customize, priority: 3, additionalArgs: [ "panel" => "pages" ] );
	new LocationPageCustomizer( $wp_customize, priority: 4, additionalArgs: [ "panel" => "pages" ] );

	new AnonymizationCustomizer( $wp_customize, priority: 26 );

	$wp_customize->add_panel( 'contact-options', array(
		'title'       => esc_html__( 'Contact Options', 'gegenlicht' ),
		'priority'    => 27,
		'capability'  => 'edit_others_posts',
		'description' => __('Configure the contact options listed throughout the website', 'gegenlicht' ),
	) );
	new SocialMediasOptionsCustomizer($wp_customize, priority: 1, additionalArgs: ["panel" => "contact-options"]);
	new EmailOptionsCustomizer($wp_customize, priority: 2, additionalArgs: ["panel" => "contact-options"]);
	new AddressOptionsCustomizer($wp_customize, 3, ["panel" => "contact-options"]);
	new YouthProtectionCustomizer( $wp_customize, priority: 28);
	new FooterCustomizer( $wp_customize, priority: 29);

}