<?php

require_once "customizer/controls/custom_controls.php";

use inc\customizer\AddressOptionsCustomizer;
use inc\customizer\ArchivePageCustomizer;
use inc\customizer\CooperationAndSupportersBlockCustomizer;
use inc\customizer\CooperationPageCustomizer;
use inc\customizer\DarkModeBrandingCustomizer;
use inc\customizer\EmailOptionsCustomizer;
use inc\customizer\FontCustomizer;
use inc\customizer\FooterCustomizer;
use inc\customizer\FrontPageCustomizerOverrides;
use inc\customizer\LightModeBrandingCustomizer;
use inc\customizer\SemesterBreakCustomizer;
use inc\customizer\SingleCoopCustomizer;
use inc\customizer\SupporterPageCustomizer;
use inc\customizer\TeamBlockCustomizer;
use inc\customizer\YouthProtectionCustomizer;
use function inc\customizer\controls\custom_controls;

defined( 'ABSPATH' ) || exit;


require_once 'customizer/front-page.php';
require_once 'customizer/block-coops.php';
require_once 'customizer/block-team.php';
require_once 'customizer/page-cooperations.php';
require_once 'customizer/page-supporters.php';
require_once 'customizer/lightModeBranding.php';
require_once 'customizer/DarkModeBrandingCustomizer.php';
require_once 'customizer/FontCustomizer.php';
require_once "customizer/contact-email.php";
require_once 'customizer/semester-break.php';
require_once 'customizer/footer.php';
require_once 'customizer/youth-protection.php';
require_once 'customizer/archiv.php';
require_once 'customizer/single-team.php';
require_once 'customizer/single-cooperation-partner.php';

function configure_customizer( WP_Customize_Manager $wp_customize ): void {
	custom_controls();

	$wp_customize->remove_section( 'custom_css' );
	$wp_customize->remove_section( 'title_tagline' );
	$wp_customize->remove_section( 'navigation' );

	new FrontPageCustomizerOverrides( $wp_customize, 21 );

	$wp_customize->add_panel('branding', array(
		'title'       => __( 'Branding', 'cinema-press-theme' ),
		'priority'    => 22,
		'capability'  => 'edit_theme_options',
		'description' => __( 'Configure the branding options for the page', 'cinema-press-theme' ),
	));

	new LightModeBrandingCustomizer( $wp_customize, 22,  additionalArgs: [ "panel" => "branding"] );
	new DarkModeBrandingCustomizer( $wp_customize, 23, additionalArgs: [ "panel" => "branding"] );
	new FontCustomizer( $wp_customize, 24, additionalArgs: [ "panel" => "branding"] );
	new SemesterBreakCustomizer( $wp_customize, priority: 23 );

	$wp_customize->add_panel( 'blocks', array(
		'title'       => esc_html__( 'Blocks', 'cinema-press-theme' ),
		'priority'    => 24,
		'capability'  => 'edit_others_posts',
		'description' => esc_html__( 'Edit the blocks displayed on the frontpage', 'cinema-press-theme' ),
	) );
	new CooperationAndSupportersBlockCustomizer( $wp_customize, priority: 1, additionalArgs: [ "panel" => "blocks" ] );
	new TeamBlockCustomizer( $wp_customize, priority: 2, additionalArgs: [ "panel" => "blocks" ] );

	$wp_customize->add_panel( 'pages', array(
		'title'       => esc_html__( 'Pages', 'cinema-press-theme' ),
		'priority'    => 25,
		'capability'  => 'edit_others_posts',
		'description' => esc_html__( 'Edit the display of some content pages of this theme', 'cinema-press-theme' ),
	) );
	new CooperationPageCustomizer( $wp_customize, priority: 1, additionalArgs: [ "panel" => "pages" ] );
	new SupporterPageCustomizer( $wp_customize, priority: 2, additionalArgs: [ "panel" => "pages" ] );
	new SingleCoopCustomizer( $wp_customize, priority: 6, additionalArgs: [ "panel" => "pages" ] );

	$wp_customize->add_panel( 'contact-options', array(
		'title'       => esc_html__( 'Contact Options', 'cinema-press-theme' ),
		'priority'    => 27,
		'capability'  => 'edit_others_posts',
		'description' => __( 'Configure the contact options listed throughout the website', 'cinema-press-theme' ),
	) );
	new EmailOptionsCustomizer( $wp_customize, priority: 2, additionalArgs: [ "panel" => "contact-options" ] );
	new YouthProtectionCustomizer( $wp_customize, priority: 28 );
	new FooterCustomizer( $wp_customize, priority: 29 );
	new ArchivePageCustomizer( $wp_customize, priority: 30 );

}