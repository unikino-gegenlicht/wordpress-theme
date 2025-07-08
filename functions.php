<?php
defined( 'ABSPATH' ) || exit;

function enable_theme_supports() {
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'title-tag' );
	add_theme_support( 'customize-selective-refresh-widgets' );
	add_theme_support( 'automatic-feed-links' );
}

add_action( 'after_setup_theme', 'enable_theme_supports' );

add_filter( 'locale', function( $locale ) {
	if ( ! isset( $_SERVER['HTTP_ACCEPT_LANGUAGE'] ) ) {
		return $locale;
	}

	$prefLocales = array_reduce( explode( ',', $_SERVER['HTTP_ACCEPT_LANGUAGE'] ), function ( $res, $el ) {
		list( $l, $q ) = array_merge( explode( ';q=', $el ), [ 1 ] );
		$res[ $l ] = (float) $q;

		return $res;
	}, [] );
	arsort( $prefLocales );

	return substr(array_key_first( $prefLocales ), 0, 2);
});

load_theme_textdomain('gegenlicht', get_template_directory() . '/languages');

require_once 'inc/customizer.php';
add_action( 'customize_register', 'ggl_add_customizer_options' );



/**
 * Disable the emoji's
 */
function disable_emojis() {
	remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
	remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
	remove_action( 'wp_print_styles', 'print_emoji_styles' );
	remove_action( 'admin_print_styles', 'print_emoji_styles' );
	remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
	remove_filter( 'comment_text_rss', 'wp_staticize_emoji' );
	remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );
	add_filter( 'tiny_mce_plugins', 'disable_emojis_tinymce' );
	add_filter( 'wp_resource_hints', 'disable_emojis_remove_dns_prefetch', 10, 2 );
}

add_action( 'init', 'disable_emojis' );

/**
 * Filter function used to remove the tinymce emoji plugin.
 *
 * @param array $plugins
 *
 * @return array Difference betwen the two arrays
 */
function disable_emojis_tinymce( $plugins ) {
	if ( is_array( $plugins ) ) {
		return array_diff( $plugins, array( 'wpemoji' ) );
	} else {
		return array();
	}
}

/**
 * Remove emoji CDN hostname from DNS prefetching hints.
 *
 * @param array $urls URLs to print for resource hints.
 * @param string $relation_type The relation type the URLs are printed for.
 *
 * @return array Difference betwen the two arrays.
 */
function disable_emojis_remove_dns_prefetch( $urls, $relation_type ) {
	if ( 'dns-prefetch' == $relation_type ) {
		/** This filter is documented in wp-includes/formatting.php */
		$emoji_svg_url = apply_filters( 'emoji_svg_url', 'https://s.w.org/images/core/emoji/2/svg/' );

		$urls = array_diff( $urls, array( $emoji_svg_url ) );
	}

	return $urls;
}

function remove_wp_block_library_css() {
	wp_dequeue_style( 'wp-block-library' );
	wp_dequeue_style( 'wp-block-library-theme' );
	wp_dequeue_style( 'wc-blocks-style' );
}

add_action( 'wp_enqueue_scripts', 'remove_wp_block_library_css', 100 );

remove_action( 'wp_enqueue_scripts', 'wp_enqueue_global_styles' );
remove_action( 'wp_footer', 'wp_enqueue_global_styles', 1 );
remove_action( 'wp_body_open', 'wp_global_styles_render_svg_filters' );

add_action( 'wp_enqueue_scripts', function () {
	wp_dequeue_style( 'global-styles-inline-css' );
	wp_dequeue_style( 'wp-block-library-css' );
	wp_dequeue_style( 'wp-block-library' );
	wp_dequeue_style( 'wp-block-library-theme' );
	wp_dequeue_style( 'classic-theme-styles' );
} );


register_nav_menu( "navigation-menu", "Navigation Menu" );


show_admin_bar(false);


function block_wp_admin() {
	if ( is_admin() && ! current_user_can( 'edit_posts' ) && ! ( defined( 'DOING_AJAX' ) && DOING_AJAX ) ) {
		nocache_headers();
		wp_safe_redirect( home_url() );
		exit;
	}
}
add_action( 'admin_init', 'block_wp_admin' );

require_once 'inc/contact-block.php';
add_shortcode( 'ggl-contact-block', 'ggl_do_contact_shortcode' );

function my_login_logo() { ?>
	<style type="text/css">
        #login h1 a {
            background-image: url(<?= wp_get_attachment_image_url( get_theme_mod( 'header_logo' ) ) ?>);
            width: 320px;
            background-size: 320px 65px;
            background-repeat: no-repeat;
            padding-bottom: 30px;
        }
	</style>
<?php }
add_action( 'login_enqueue_scripts', 'my_login_logo' );

function my_login_logo_url() {
	return home_url();
}
add_filter( 'login_headerurl', 'my_login_logo_url' );

function my_login_stylesheet() {
    if (defined("WP_DEBUG") && WP_DEBUG):
	    wp_enqueue_style( 'custom-login', get_stylesheet_directory_uri() . '/assets/css/login.css' );
    else:
	    wp_enqueue_style( 'custom-login-min', get_stylesheet_directory_uri() . '/assets/css/login.min.css' );
    endif;

    }
add_action( 'login_enqueue_scripts', 'my_login_stylesheet' );

function custom_login_error_message( $error ) {
	return esc_html__("Unable to log in. Please check your credentials and try again.", 'gegenlicht');
}
add_filter( 'login_errors', 'custom_login_error_message' );