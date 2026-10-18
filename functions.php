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

const CINEMA_PRESS__TITLE_SEPARATOR = " | ";
add_action( 'init', function () {
	/* translators: date in US notation */
	define( "CINEMA_PRESS__DATE_FORMAT", __( "m/d/Y", "cinema-press-theme" ) );

	/* translators: time in 12hrs format */
	define( "CINEMA_PRESS__TIME_FORMAT", __( "g:i a", "cinema-press-theme" ) );

	define( "CINEMA_PRESS__DATETIME_FORMAT", CINEMA_PRESS__DATE_FORMAT . CINEMA_PRESS__TITLE_SEPARATOR . CINEMA_PRESS__TIME_FORMAT );
} );

add_action( 'after_setup_theme', function () {
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'title-tag' );
	add_theme_support( 'automatic-feed-links' );
} );

add_action('wp_enqueue_scripts', function () {
	wp_enqueue_style( 'cinema-press-theme', get_theme_file_uri('style.min.css'), ver: md5_file(get_theme_file_path('style.min.css')) );
});

