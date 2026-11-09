<?php
/**
 * # CinemaPress Theme
 *
 * This WordPress theme is designed for usage with the CinemaPress plugin
 * which introduces the posts types that are picked up on by this theme.
 * The theme has originally been designed and developed as a mobile first
 * layout and for high readability standards.
 *
 * @package Cinema_Press_Theme
 * @author Jan Eike Suchard <jan@suchard.cloud>
 * @copyright 2024-2026 Unikino GEGENLICHT
 * @license EUPL-1.2-or-later
 */

/**
 * List of constants for the theme
 *
 * These constants control the output format for the date and time.
 */

require_once "vendor/autoload.php";
require_once 'inc/color-converter.php';
require_once "customizer.php";

use Sokil\IsoCodes\IsoCodesFactory;
use Sokil\IsoCodes\TranslationDriver\SymfonyTranslationDriver;

const CINEMA_PRESS__TITLE_SEPARATOR = " | ";
add_action( 'init', function () {
	/* translators: date in US notation */
	define( "CINEMA_PRESS__DATE_FORMAT", __( "m/d/Y", "cinema-press-theme" ) );

	/* translators: time in 12hrs format */
	define( "CINEMA_PRESS__TIME_FORMAT", __( "g:i a", "cinema-press-theme" ) );

	define( "CINEMA_PRESS__DATETIME_FORMAT", CINEMA_PRESS__DATE_FORMAT . CINEMA_PRESS__TITLE_SEPARATOR . CINEMA_PRESS__TIME_FORMAT );
} );

add_action( 'after_setup_theme', function () {
	load_theme_textdomain( 'cinema-press-theme', get_template_directory() . '/i18n' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'title-tag' );
	add_theme_support( 'automatic-feed-links' );
	add_theme_support( 'widgets' );
	//  add_theme_support( 'wp-block-styles' );
	add_theme_support( 'widgets-block-editor' );
	add_theme_support( 'customize-selective-refresh-widgets' );

	$header_args = array(
		'default-image'      => get_theme_file_uri('static/img/header.png'),
		'default-text-color' => 'ffffff',
		'width'              => 150,
		'height'             => 30,
		'flex-height'        => true,
		'flex-width'         => true,
	);
	add_theme_support( 'custom-header', $header_args );
	add_theme_support( 'menus' );
	register_nav_menus( array(
		'header'        => __( 'Header Menu', 'cinema-press-theme' ),
		'footer'        => __( 'Footer Link Menu', 'cinema-press-theme' ),
		'footer_bottom' => __( 'Footer Bottom Menu', 'cinema-press-theme' ),
	) );
} );

add_action( 'init', function () {
	add_image_size( 'desktop', 800, 450, true );
	add_image_size( 'mobile', 300, 400, true );
	add_image_size( 'teamie', 250, 333, true );
} );

add_action( 'wp_enqueue_scripts', function () {
	//wp_enqueue_style( 'bulma-root', get_theme_file_uri( 'static/css/root.min.css' ), ver: md5_file( get_theme_file_path( 'static/css/root.min.css' ) ) );
	wp_enqueue_style( 'base-bulma', get_theme_file_uri( 'static/css/bulma.min.css' ), ver: md5_file( get_theme_file_path( 'static/css/bulma.min.css' ) ) );
	wp_enqueue_style( 'base-fa', get_theme_file_uri( 'static/css/fa.min.css' ), ver: md5_file( get_theme_file_path( 'static/css/fa.min.css' ) ) );
	wp_dequeue_style( 'dashicons' );
	wp_enqueue_script( 'theme-navigation', get_theme_file_uri( 'static/js/navigation.min.js' ), ver: md5_file( get_theme_file_path( 'static/js/navigation.min.js' ) ), args: [
		"strategy"  => "defer",
		"in_footer" => true
	] );
} );

add_filter( 'post_thumbnail_html', function ( $html, $post_id, $attr ) {
	$post = get_post( $post_id );
	switch ( get_post_type( $post ) ) {
		case 'screening':
			if ( has_post_thumbnail() ):
				return '<figure class="image">
                            <picture>
                                <source class="is-16by9" media="(min-width: 576px)"
                                        srcset="' . wp_get_attachment_image_url( get_post_thumbnail_id( $post ), 'desktop' ) . '" />
                                <img src="' . wp_get_attachment_image_url( get_post_thumbnail_id(), 'mobile' ) . '" fetchpriority="high" decoding="async"/>
                            </picture>
                        </figure>';
			else:
				return '<figure class="image">
                            <picture>
                                <source class="is-16by9" media="(min-width: 576px)"
                                        srcset="' . get_theme_file_uri( 'static/img/fallback.jpeg' ) . '"/>
                                <img class="is-3by4" src="' . get_theme_file_uri( 'static/img/fallback-mobile.jpeg' ) . '" fetchpriority="high" decoding="async"/>
                            </picture>
                        </figure>';
			endif;
		case 'team_member':
			return '<picture class="image is-3by4 is-bordered team-member">
                                 <source srcset="' . get_the_post_thumbnail_url( $post, 'teamie' ) . '"/>
                                <img src="' . get_theme_file_uri( 'static/img/team-fallback.svg' ) . '" fetchpriority="auto"/>
                            </picture>';
		case 'movie':
			return '<figure class="image">
						<picture style="aspect-ratio: 800/310;">
							<source srcset="' . (rwmb_meta( 'logo_dark' )['full_url'] ?? "") . '" media="(prefers-color-scheme: dark)">
							<source srcset="' . (rwmb_meta( 'logo' )['full_url'] ?? "") . '">
							<img src="' . get_theme_file_uri( 'static/img/movie-fallback.png' ) . '" fetchpriority="high" decoding="async"/>
						</picture>
					</figure>';
		case 'cooperation_partner':
			return '<figure class="image">
						<picture style="max-height: 10rem">
							<source srcset="' . (rwmb_meta( 'logo_dark' )['full_url'] ?? "") . '" media="(prefers-color-scheme: dark)">
							<source srcset="' . (rwmb_meta( 'logo' )['full_url'] ?? "") . '"/>
							<img src="' . get_theme_file_uri( 'static/img/cooperation-fallback.svg' ) . '" fetchpriority="auto"  style="height: 10rem; object-fit: contain;"/>
						</picture>
					</figure>';
		case 'sponsor':
			return '<figure class="image">
						<picture style="max-height: 10rem">
							<source srcset="' . (rwmb_meta( 'logo_dark' )['full_url'] ?? "") . '" media="(prefers-color-scheme: dark)">
							<source srcset="' . (rwmb_meta( 'logo' )['full_url'] ?? "") . '"/>
							<img src="' . get_theme_file_uri( 'static/img/sponsor-fallback.svg' ) . '" fetchpriority="auto"  style="height: 10rem; object-fit: contain;"/>
						</picture>
					</figure>';

	}
	if ( has_post_thumbnail() ):
		$html = '<figure class="image">
                            <picture>
                                <source class="is-16by9" media="(min-width: 576px)"
                                        srcset="' . wp_get_attachment_image_url( get_post_thumbnail_id(), 'desktop' ) . '"/>
                                <img src="' . wp_get_attachment_image_url( get_post_thumbnail_id(), 'mobile' ) . '" fetchpriority="auto"/>
                            </picture>
                        </figure>';
	else:
		$html = '<figure class="image">
                            <picture>
                                <source class="is-16by9" media="(min-width: 576px)"
                                        srcset="' . get_theme_file_uri( 'static/img/fallback.jpeg' ) . '"/>
                                <img class="is-3by4" src="' . get_theme_file_uri( 'static/img/fallback-mobile.jpeg' ) . '" fetchpriority="auto"/>
                            </picture>
                        </figure>';
	endif;

	return $html;
}, 10, 3 );


if ( ! function_exists( 'iso_codes' ) ) {
	function iso_codes(): IsoCodesFactory {

		$locale             = get_user_locale();
		$translation_driver = new SymfonyTranslationDriver();
		$translation_driver->setLocale( $locale );

		return new IsoCodesFactory( null, $translation_driver );

	}
}

add_action( 'widgets_init',
	function () {
		/* Register the 'primary' sidebar. */
		register_sidebar(
			array(
				'id'            => 'footer',
				'name'          => __( 'Footer Widgets' ),
				'description'   => __( 'A short description of the sidebar.' ),
				'before_widget' => '<div id="%1$s" class="widget %2$s">',
				'after_widget'  => '</div>',
				'before_title'  => '<h3 class="widget-title">',
				'after_title'   => '</h3>',
			)
		);
		/* Repeat register_sidebar() code for additional sidebars. */
	}
);

add_action( "customize_register", "configure_customizer" );

add_action( 'wp_head', function () {
	$light_text_color = get_theme_mod( 'text_color_light', "#000000" );
	$primary_color    = get_theme_mod( 'primary_color_light', "#ffdd00" );

	$dark_text_color    = get_theme_mod( 'text_color_dark', "#ffdd00" );
	$dark_primary_color = get_theme_mod( 'primary_color_dark', "#000000" );

	$primary_color_hsl = hex2hsl( $primary_color );
	$text_color_hsl    = hex2hsl( $light_text_color );

	$dark_primary_color_hsl = hex2hsl( $dark_primary_color );
	$dark_text_color_hsl    = hex2hsl( $dark_text_color );

	echo '<style>' . file_get_contents( get_theme_file_path( 'static/css/root.min.css' ) ) . '</style>';
	echo '<style>:root{ --bulma-primary-h: ' . round( $primary_color_hsl[0] * 360 ) . 'deg; --bulma-primary-s: ' . $primary_color_hsl[1] * 100 . '%; --bulma-primary-l: ' . $primary_color_hsl[2] * 100 . '%;}</style>';
	echo '<style>:root{ --bulma-scheme-h: ' . round( $primary_color_hsl[0] * 360 ) . 'deg; --bulma-scheme-s: ' . $primary_color_hsl[1] * 100 . '%; --bulma-scheme-main-l: ' . $primary_color_hsl[2] * 100 . '%;}</style>';
	echo '<style>:root{ --bulma-text-h: ' . round( $text_color_hsl[0] * 360 ) . 'deg ; --bulma-text-s: ' . $text_color_hsl[1] * 100 . '%; --bulma-text-l: ' . $text_color_hsl[2] * 100 . '%;--bulma-text-strong-l: var(--bulma-text-l);--bulma-title-l: var(--bulma-text-l);}</style>';
	echo '<style>@media(prefers-color-scheme: dark){:root{ --bulma-dark-h: ' . $dark_primary_color_hsl[0] * 360 . 'deg; --bulma-dark-s: ' . $dark_primary_color_hsl[1] * 100 . '%; --bulma-dark-l: ' . $dark_primary_color_hsl[2] * 100 . '%;}}</style>';
	echo '<style>@media(prefers-color-scheme: dark){:root{ --bulma-scheme-h: ' . $dark_primary_color_hsl[0] * 360 . 'deg; --bulma-scheme-s: ' . $dark_primary_color_hsl[1] * 100 . '%; --bulma-scheme-main-l: ' . $dark_primary_color_hsl[2] * 100 . '%;}}</style>';
	echo '<style>@media(prefers-color-scheme: dark){:root{ --bulma-text-h: ' . $dark_text_color_hsl[0] * 360 . 'deg ; --bulma-text-s: ' . $dark_text_color_hsl[1] * 100 . '%; --bulma-text-l: ' . $dark_text_color_hsl[2] * 100 . '%;}}</style>';
	echo '<style>' . file_get_contents( get_theme_file_path( 'style.min.css' ) ) . '</style>';
	echo "<style>@font-face {
  font-family: 'Readex Pro';
  font-style: normal;
  font-weight: 100 900;
  font-display: swap;
  src: url('" . get_theme_file_uri( "static/webfonts/readex/readexpro.woff2" ) . "')
}</style>";
	echo '<style>:root{--text-font: ' . get_theme_mod( 'text_font_family', "InterVariable" ) . " ; --heading-font: '" . get_theme_mod( 'heading_font_family' ) . "' ;}</style>";
} );


add_filter( 'show_admin_bar', '__return_false' );