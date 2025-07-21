<?php
defined( 'ABSPATH' ) || exit;
show_admin_bar( false );


function enable_theme_supports() {
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'title-tag' );
	add_theme_support( 'customize-selective-refresh-widgets' );
	add_theme_support( 'automatic-feed-links' );
}

add_action( 'after_setup_theme', 'enable_theme_supports' );


if ( ! is_admin() || is_customize_preview() ) {
	if ( ! is_user_logged_in() ) {
		add_filter( 'locale', 'use_accept_locale' );
		add_filter( 'pre_determine_locale', 'use_accept_locale' );
	} else {
		add_filter( 'locale', function ( $locale ) {
			return substr( get_user_meta( wp_get_current_user()->ID, 'locale', true ) ?? "en", 0, 2 );
		} );
	}
}

function use_accept_locale( $locale ) {
	if ( ! isset( $_SERVER['HTTP_ACCEPT_LANGUAGE'] ) ) {
		return $locale;
	}
	$prefLocales = array_reduce( explode( ',', $_SERVER['HTTP_ACCEPT_LANGUAGE'] ), function ( $res, $el ) {
		list( $l, $q ) = array_merge( explode( ';q=', $el ), [ 1 ] );
		$res[ $l ] = (float) $q;

		return $res;
	}, [] );
	arsort( $prefLocales );

	return substr( array_key_first( $prefLocales ), 0, 2 );
}

load_theme_textdomain( 'gegenlicht', get_template_directory() . '/languages' );

require_once 'inc/customizer.php';
add_action( 'customize_register', 'configure_customizer' );


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

remove_action( 'wp_enqueue_scripts', 'wp_enqueue_global_styles' );
remove_action( 'wp_footer', 'wp_enqueue_global_styles', 1 );
remove_action( 'wp_body_open', 'wp_global_styles_render_svg_filters' );

add_action( 'wp_enqueue_scripts', function () {
	wp_dequeue_style( 'global-styles-inline-css' );
	wp_dequeue_style( 'wp-block-library-css' );
	wp_dequeue_style( 'wp-block-library' );
	wp_dequeue_style( 'wp-block-library-theme' );
	wp_dequeue_style( 'wc-blocks-style' );
	wp_dequeue_style( 'classic-theme-styles' );

	if ( defined( 'WP_DEBUG' ) && WP_DEBUG ):
		wp_enqueue_style( 'main-style', get_stylesheet_directory_uri() . '/style.css', ver: filemtime( get_template_directory() . '/style.css' ) );
	else:
		wp_enqueue_style( 'main-style', get_stylesheet_directory_uri() . '/style.min.css', ver: filemtime( get_template_directory() . '/style.css' ) );
	endif;

    wp_enqueue_style('simple-icons', get_stylesheet_directory_uri() . '/assets/css/simple-icons.css');
} );

add_action('wp_enqueue_scripts', function () {
    wp_enqueue_script('menu-toggle', get_stylesheet_directory_uri() . '/assets/js/menu-toggle.js');
    wp_enqueue_script('list-toggle', get_stylesheet_directory_uri() . '/assets/js/program-list-toggle.js');
});


register_nav_menu( "navigation-menu", "Navigation Menu" );


function block_wp_admin() {
	if ( is_admin() && ! current_user_can( 'edit_posts' ) && ! ( defined( 'DOING_AJAX' ) && DOING_AJAX ) ) {
		nocache_headers();
		wp_safe_redirect( home_url() );
		exit;
	}
}

add_action( 'admin_init', 'block_wp_admin' );


function my_login_logo() {
	?>
    <style>
        @media (prefers-color-scheme: dark) {
            #login h1 a {
                filter: invert(74%) sepia(89%) saturate(508%) hue-rotate(358deg) brightness(102%) contrast(105%);
            }
        }

        #login h1 a {
            background-image: url(<?= wp_get_attachment_url(get_theme_mod( 'header_logo' )) ?>);
            width: 320px;
            background-size: 320px 65px;
            background-repeat: no-repeat;
            padding-bottom: 30px;
        }

        #loginform:after {
            content: "<?= esc_html__('Members of the Carl von Ossietzky University are able to register for a free account to get more details about the program') ?>";
            display: block;
            margin-top: 4rem !important;
            font-style: italic;
        }
    </style>
<?php }

add_action( 'login_enqueue_scripts', 'my_login_logo' );

function my_login_logo_url() {
	return home_url();
}

add_filter( 'login_headerurl', 'my_login_logo_url' );

function my_login_stylesheet() {
	if ( defined( "WP_DEBUG" ) && WP_DEBUG ):
		wp_enqueue_style( 'custom-login', get_stylesheet_directory_uri() . '/assets/css/login.css' );
	else:
		wp_enqueue_style( 'custom-login-min', get_stylesheet_directory_uri() . '/assets/css/login.min.css' );
	endif;

}

add_action( 'login_enqueue_scripts', 'my_login_stylesheet' );

function custom_login_error_message( $error ) {
	if ( strpos( $error, 'Please enter a username' ) || strpos( $error, 'Please type your email address' ) ) {
		return esc_html__( 'Username or email address is missing', 'gegenlicht' );
	}
	if ( ( strpos( $error, 'The username' ) && strpos( $error, 'is not registered on this site' ) ) || strpos( $error, 'Unknown email address' ) || strpos( $error, 'The password you entered for the username' ) ) {
		return esc_html__( 'The entered credentials are invalid. Please try again', 'gegenlicht' );

	}

	return $error;
}

add_filter( 'login_errors', 'custom_login_error_message' );
add_filter( 'login_display_language_dropdown', '__return_false' );

function ggl_cleanup_paragraphs( string $input ): string {
	return preg_replace( '/<p[^>]*>(?:\s|&nbsp;)*<\/p>/xu', '', trim( $input ) );
}

function ggl_theme_get_translated_age_rating_descriptor( string $metaKey ): string {
	$descriptors = [
		'sexualized_violence' => esc_html__( 'Sexualized Violence', 'ggl-post-types' ),
		'violence'            => esc_html__( 'Violence', 'ggl-post-types' ),
		'self_harm'           => esc_html__( 'Self Harm', 'ggl-post-types' ),
		'drug_usage'          => esc_html__( 'Drug Usage', 'ggl-post-types' ),
		'discrimination'      => esc_html__( 'Discrimination', 'ggl-post-types' ),
		'sexuality'           => esc_html__( 'Sexuality', 'ggl-post-types' ),
		'threat'              => esc_html__( 'Threat', 'ggl-post-types' ),
		'injury'              => esc_html__( 'Injury', 'ggl-post-types' ),
		'stressful_topics'    => esc_html__( 'Stressful Topics', 'ggl-post-types' ),
		'language'            => esc_html__( 'Language', 'ggl-post-types' ),
		'nudeness'            => esc_html__( 'Nudeness', 'ggl-post-types' ),
	];
	if ( array_key_exists( $metaKey, $descriptors ) ) {
		return $descriptors[ $metaKey ];
	}

	return $metaKey;
}

function crunchify_print_scripts_styles() {

	$result            = [];
	$result['scripts'] = [];
	$result['styles']  = [];

	// Print all loaded Scripts
	global $wp_scripts;
	foreach ( $wp_scripts->queue as $script ) :
		$result['scripts'][] = $wp_scripts->registered[ $script ]->src . "?ver=" . ( ! $wp_scripts->registered[ $script ]->ver ? wp_get_wp_version() : urlencode($wp_scripts->registered[ $script ]->ver ));
	endforeach;

	// Print all loaded Styles (CSS)
	global $wp_styles;
	foreach ( $wp_styles->queue as $style ) :
		$result['styles'][] = $wp_styles->registered[ $style ]->src . "?ver=" . ( ! $wp_styles->registered[ $style ]->ver ? wp_get_wp_version() : urlencode($wp_styles->registered[ $style ]->ver ));
	endforeach;

	return $result;
}


add_filter( 'wp_print_styles', function () {
	$styles  = crunchify_print_scripts_styles()['styles'] ?? [];
	$scripts = crunchify_print_scripts_styles()['scripts'] ?? [];

    $links = [];

	foreach ( $styles as $style ) :
		$links[] = '<' . $style . '>; rel=preload; as=style;';
	endforeach;

	foreach ( $scripts as $script ) :
        $links[] = '<' . $script . '>; rel=preload; as=script;';
	endforeach;

    header('Link: ' . join( ', ', $links ));

} );