<?php

use ArrayPress\AcceptLanguageUtils\AcceptLanguage;
use inc\GGL_Font;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

defined( 'ABSPATH' ) || exit;
require_once ABSPATH . 'wp-admin/includes/file.php';
/**
 * GEGENLICHT Website Theme
 *
 * This file contains the main functions of the theme. The whole code is always being
 * executed for every call, therefore it is recommended to use hooks where available
 * and possible to enhance the loading speed of the page
 */

/**
 * Load constants that are used thoughout the theme
 */
require_once "src/inc/constants.php";

/**
 * Load the customizer configuration
 */
require_once "src/inc/customizer.php";
require_once "src/inc/GGL_Font.php";

require_once "vendor/autoload.php";
require_once "src/filters.php";

add_action( "after_setup_theme", "ggl_setup_theme_supports" );
add_action( "after_setup_theme", "ggl_add_image_sizes" );
add_action( "customize_register", "configure_customizer" );
add_action( "init", "ggl_disable_wp_emoji_styles" );
add_action( "init", "ggl_disable_emoji_staticization" );
add_action( "init", "ggl_disable_tinymce_emojis" );
add_action( "wp_enqueue_scripts", "ggl_remove_default_styles" );
add_action( "get_header", "ggl_enqueue_styles", 5 );
add_action( "get_header", "ggl_enqueue_scripts", 6 );
add_action( "get_header", "ggl_enqueue_fonts", 7 );
add_action( "get_header", "ggl_send_link_headers", 8 );
add_action( "after_setup_theme", "ggl_setup_menus" );
add_action( "init", "ggl_disable_wpadmin_for_subscribers" );
add_action( "init", "ggl_disable_admin_bar" );
add_action( "setup_theme", "ggl_load_frontend_textdomain" );
add_action( "login_enqueue_scripts", "ggl_enqueue_logo_url_variable" );
add_action( "login_enqueue_scripts", "ggl_enqueue_login_style" );
add_filter( "login_headerurl", "ggl_login_header_url" );
add_filter( "login_display_language_dropdown", "__return_false" );
add_filter( "login_errors", "ggl_obfuscate_login_errors" );
add_action( "wp_enqueue_scripts", "ggl_send_image_link_headers", 70 );
add_action( "pre_get_posts", "ggl_list_all_entities_sorted" );
add_action( "pre_get_posts", "ggl_frontpage_query_only_current_semester", 1 );
add_filter( "locale", "ggl_locale_use_http_fallback" );
add_action( "wp_head", "ggl_inject_special_program_colors" );
add_filter( "wpseo_opengraph_image", "ggl_anonymize_opengraph_image" );
add_filter( "init", "ggl_add_shortcodes" );
add_filter( 'wpseo_sitemap_exclude_empty_terms', '__return_false' );
add_action( "wp_head", "ggl_insert_font_faces" );
add_filter( "query_vars", "ggl_add_query_vars" );
//add_filter( "wp_new_user_notification_email", "ggl_new_user_notification_email", 10, 3 );
function ggl_new_user_notification_email( array $notification, WP_User $user, string $blogname ) {
    $loader = new FilesystemLoader( get_stylesheet_directory() . "assets/email-templates" );
    $twig   = new Environment( $loader, [
            'cache' => get_temp_dir() . "twig",
    ] );

    try {
        $tmpl = $twig->load( "user-account-creation-notification.html.twig" );
    } catch ( Exception $e ) {
        error_log( "Unable to find template for user notification" );
        wp_die( "Unable to process the registration. Please try again later" );
    }

    $content = $tmpl->render( [ "email" => $notification['to'], "username" => $user->user_login ] );


    $notification['subject'] = "[%s] Benutzeraccount erstellt // User Account Created";
    $notification['message'] = $content;
    $notification['headers'] = [
            "Content-Type: text/html; charset=UTF-8",
            'From: "Unikino GEGENLICHT" <noreply@gegenlicht.net>'
    ];

    return $notification;

}

function ggl_add_query_vars( $vars ) {
    $vars[] = "ics";

    return $vars;
}

function ggl_insert_font_faces() {
    ?>
    <style>
        <?php foreach (FONT_REGISTRY as $font): ?>
        @font-face {
            font-family: <?= $font->name ?>;
            src: url('<?= "{$font->public_path}?ver={$font->hash}" ?>') format('<?= $font->type ?>');
        <?php foreach ($font->font_face_attrs as $key => $value): ?>
        <?= $key ?>: <?= $value ?>;
        <?php endforeach; ?>
        }

        <?php foreach ($font->additional_classes as $name => $attrs): ?>
        <?= $name ?>
        {
        <?php foreach ($attrs as $key => $value): ?>
        <?= $key ?>
        :
        <?= $value ?>
        ;
        <?php endforeach; ?>
        }
        <?php endforeach; ?>
        <?php endforeach; ?>
    </style>
    <?php
}

function ggl_add_shortcodes() {
    require_once "src/shortcodes/button.php";
    require_once "src/shortcodes/inverted-block.php";
    require_once "src/shortcodes/location-button.php";

    add_shortcode( "ggl_inverted_block", "ggl_inverted_block_shortcode" );
    add_shortcode( "ggl_button", "ggl_button_shortcode" );
    add_shortcode( "location_button", "ggl_location_button_shortcode" );
}

function ggl_anonymize_opengraph_image( $original_image ) {
    if ( is_singular( [ "movie", "event" ] ) ) {
        return ggl_get_thumbnail_url( size: "medium_large" );
    }

    return $original_image;
}

function ggl_inject_special_program_colors(): void {
    $specialProgram = rwmb_get_value( "program_type" ) == "special_program" ? rwmb_get_value( "special_program" ) : false;
    if ( ! is_singular( [ "movie", "event" ] ) || ! $specialProgram ) {
        ?>
        <meta name="theme-color" content="#ffdd00">
        <meta name="theme-color" content="#000000" media="(prefers-color-scheme: dark)">

        <?php
        return;
    }

    $colors = ggl_get_special_program_colors( $specialProgram );

    ?>
    <meta name="theme-color" content="<?= $colors["lightMode"]["backgroundColor"] ?? '#ffdd00' ?>">
    <meta name="theme-color" content="<?= $colors["darkMode"]["backgroundColor"] ?? '#000000' ?>"
          media="(prefers-color-scheme: dark)">
    <?php

}

function ggl_locale_use_http_fallback( string $locale ): string {
    if ( is_user_logged_in() && current_user_can( "edit_posts" ) ) {
        remove_filter( "locale", "ggl_locale_use_http_fallback" );
        $locale = get_user_locale();
        add_filter( "locale", "ggl_locale_use_http_fallback" );

        return $locale;
    }

    if ( ( is_admin() && ! is_customize_preview() ) ) {
        return $locale;
    }


    return str_starts_with( AcceptLanguage::get_best_match( [
            "en-US",
            "en",
            "de-DE",
            "de"
    ], "en_US" ), "de" ) ? "de_DE" : "en_US";
}


/**
 * Modify the query for the front page to only display upcoming events and movies
 * on the frontpage.
 *
 * @param WP_Query $query The initially received query
 *
 * @return void
 */
function ggl_frontpage_query_only_current_semester( WP_Query $query ): void {
    if ( ! $query->is_front_page() || ! $query->is_main_query() ) {
        return;
    }

    $query->set( 'post_type', [ "movie", "event" ] );
    $query->set( 'posts_per_page', - 1 );
    $query->set( 'meta_key', 'screening_date' );
    $query->set( 'orderby', 'meta_value_num' );
    $query->set( 'order', 'ASC' );
    $query->set( 'tax_query', [
            [
                    "taxonomy" => "semester",
                    "terms"    => get_theme_mod( "displayed_semester" )
            ]
    ] );
    $query->set( "meta_query", array(
            [
                    "key"     => "screening_date",
                    "value"   => time(),
                    "compare" => ">"
            ]
    ) );

}

function ggl_list_all_entities_sorted( WP_Query $query ): void {
    if ( is_admin() ) {
        return;
    }

    if ( ! is_post_type_archive( [ "team-member", "cooperation-partner", "supporter" ] ) ) {
        return;
    }

    $query->set( "posts_per_page", - 1 );
    $query->set( "orderby", "title" );
    $query->set( "order", "ASC" );
}

function ggl_send_image_link_headers(): void {
    if ( ! is_singular() ) {
        return;
    }

    if ( ! has_post_thumbnail() ) {
        return;
    }

    $mobileOptimizedImage  = get_the_post_thumbnail_url( size: "mobile" );
    $desktopOptimizedImage = get_the_post_thumbnail_url( size: "desktop" );

    header( 'Link: <' . $mobileOptimizedImage . '>; rel=preload; as=image; fetchpriority="high;', replace: false, response_code: 103 );
    header( 'Link: <' . $desktopOptimizedImage . '>; rel=preload; as=image; fetchpriority="high;', replace: false, response_code: 103 );
}


function ggl_obfuscate_login_errors( string $error ): string {
    if ( strpos( $error, "Please enter a username" ) || strpos( $error, "Please type your email address" ) ) {
        return esc_html__( "Username or email address is missing", "gegenlicht" );
    }

    if ( ( strpos( $error, "The username" ) && strpos( $error, "is not registered on this site" ) ) || strpos( $error, 'Unknown email address' ) || strpos( $error, 'The password you entered for the username' ) ) {
        return esc_html__( 'The entered credentials are invalid. Please try again', 'gegenlicht' );
    }

    return $error;
}

function ggl_login_header_url( $_ ): string {
    return get_home_url( null, "/", "https" );
}

function ggl_enqueue_login_style(): void {
    if ( defined( "WP_DEBUG" ) && WP_DEBUG ) {
        wp_enqueue_style( 'custom-login', parse_url( get_stylesheet_directory_uri(), PHP_URL_PATH ) . '/assets/css/login.css', ver: md5_file( get_stylesheet_directory() . '/assets/css/login.css' ) );
    } else {
        wp_enqueue_style( 'custom-login', parse_url( get_stylesheet_directory_uri(), PHP_URL_PATH ) . '/assets/css/login.min.css', ver: md5_file( get_stylesheet_directory() . '/assets/css/login.min.css' ) );
    }
}

function ggl_enqueue_logo_url_variable(): void {
    ?>
    <style>
        :root {
            --login-logo: url(<?= wp_get_attachment_image_url(get_theme_mod("header_logo"), "full") ?: "/wp-includes/images/w-logo-blue.png" ?>);
            --login-after-text: "<?= esc_html__('Students and Faculty at the Carl von Ossietzky University are able to register for a free account which unlocks more details about the program (especially the special programs)', "gegenlicht") ?>";
        }

        .frc-captcha * {
            /* Mostly a CSS reset so existing website styles don't clash */
            margin: 0;
            padding: 0;
            border: 0;
            text-align: initial;
            border-radius: 0;
            filter: none !important;
            transition: none !important;
            font-weight: normal;
            font-size: 14px;
            line-height: 1.2;
            text-decoration: none;
            background-color: initial;
            color: #222;
        }

        .frc-captcha {
            width: 100% !important;
            background-color: var(--bulma-body-color);
        }
    </style>
    <?php
}

function ggl_load_frontend_textdomain(): void {
    load_theme_textdomain( "gegenlicht", get_template_directory() . "/languages/" );
}

function ggl_disable_admin_bar(): void {
    show_admin_bar( false );
}

function ggl_disable_wpadmin_for_subscribers(): void {

    if ( ! is_admin() ) {
        return;
    }

    if ( defined( "DOING_AJAX" ) && DOING_AJAX ) {
        return;
    }

    if ( current_user_can( "edit_posts" ) ) {
        return;
    }

    nocache_headers();
    wp_safe_redirect( home_url(), 307 );
    exit();
}

function ggl_setup_menus(): void {
    register_nav_menu( "navigation-menu", "Header Menu" );
}


function ggl_send_link_headers( $_ ): void {
    if ( is_404() ) {
        return;
    }
    $styles  = wp_styles();
    $scripts = wp_scripts();

    $relativeStylePaths  = [];
    $relativeScriptPaths = [];

    foreach ( $styles->queue as $enqueuedStyle ) {
        foreach ( $styles->registered as $registeredStyle ) {
            if ( $registeredStyle->handle == $enqueuedStyle ) {
                if ( ! $registeredStyle->src ) {
                    continue;
                }
                $basePath         = parse_url( $registeredStyle->src, PHP_URL_PATH );
                $versionParameter = $registeredStyle->ver ? urlencode( $registeredStyle->ver ) : wp_get_wp_version();

                $relativeStylePaths[] = "{$basePath}?ver={$versionParameter}";
            }
        }
    }

    foreach ( $scripts->queue as $enqueuedScript ) {
        foreach ( $scripts->registered as $registeredScript ) {
            if ( $registeredScript->handle == $enqueuedScript ) {
                if ( ! $registeredScript->src ) {
                    continue;
                }
                $basePath         = parse_url( $registeredScript->src, PHP_URL_PATH );
                $versionParameter = $registeredScript->ver ? urlencode( $registeredScript->ver ) : wp_get_wp_version();

                $relativeScriptPaths[] = "{$basePath}?ver={$versionParameter}";
            }
        }
    }

    foreach ( FONT_REGISTRY as $font ) {
        header( "Link: <{$font->public_path}?ver={$font->hash}>; rel=preload; as=font; crossorigin=anonymous; fetchpriority=high;", false, 103 );
    }

    foreach ( $relativeStylePaths as $relativeStylePath ) {
        header( "Link: <{$relativeStylePath}>; rel=preload; as=style; fetchpriority=high;", false, 103 );
    }

    foreach ( $relativeScriptPaths as $relativeScriptPath ) {
        header( "Link: <{$relativeScriptPath}>; rel=preload; as=script; fetchpriority=high;", false, 103 );
    }
    headers_send( 103 );
}

function ggl_enqueue_fonts(): void {
    /**
     * @type GGL_Font[] $fonts
     */
    $fonts   = [];
    $fonts[] = new GGL_Font( "GEGENLICHT", get_stylesheet_directory_uri() . "/assets/fonts/gegenlicht.woff2", "woff2", [
            "font-weight"  => "bold",
            "font-style"   => "normal",
            "font-display" => "swap"
    ], [
            ".font-ggl" => [
                    "font-family" => "GEGENLICHT, sans-serif",
            ]
    ] );

    $fonts[] = new GGL_Font( "Inter", get_stylesheet_directory_uri() . "/assets/fonts/inter.woff2", "woff2", [
            "font-style"            => "normal",
            "font-display"          => "swap",
            "font-stretch"          => "normal",
            "font-weight"           => "300 800",
            "font-feature-settings" => '"calt" on, "tnum" on, "frac" on, "ss01" on, "ss02" on'
    ] );
    $fonts[] = new GGL_Font( "Inter Italic", get_stylesheet_directory_uri() . "/assets/fonts/inter_italic.woff2", "woff2", [
            "font-style"            => "italic",
            "font-display"          => "swap",
            "font-stretch"          => "normal",
            "font-weight"           => "300 800",
            "font-feature-settings" => '"calt" on, "tnum" on, "frac" on, "ss01" on, "ss02" on'
    ], [
            ".is-italic" => [
                    "font-family" => "Inter Italic",
                    "font-style"  => "italic",
            ]
    ] );
    $fonts[] = new GGL_Font( "Material Symbols", get_stylesheet_directory_uri() . "/assets/fonts/icons.woff2", "woff2", [
            "font-weight"  => "100 700",
            "font-style"   => "normal",
            "font-display" => "block",
    ], [
            ".material-symbols"           => [
                    "font-family"                => "'Material Symbols'",
                    "font-weight"                => "normal",
                    "font-style"                 => "normal",
                    "font-size"                  => "inherit",
                    "line-height"                => "1",
                    "letter-spacing"             => "normal",
                    "text-transform"             => "none",
                    "display"                    => "inline-block",
                    "white-space"                => "nowrap",
                    "word-wrap"                  => "normal",
                    "direction"                  => "ltr",
                    "-moz-font-feature-settings" => "'liga'",
                    "-moz-osx-font-smoothing"    => "grayscale",
                    "font-variation-settings"    => "'FILL' 0"
            ],
            ".material-symbols > .filled" => [
                    "font-variation-settings" => "'FILL' 1"
            ]
    ] );

    if ( is_user_logged_in() ) {
        $fonts[] = new GGL_Font( "Simple Icons", get_stylesheet_directory_uri() . "/assets/fonts/simple-icons.woff2", "woff2", additional_classes: [
                ".si" => [
                        "font-family"    => "'Simple Icons', sans-serif",
                        "font-style"     => "normal",
                        "vertical-algin" => "middle"
                ],
        ] );
    }

    /**
     * @type GGL_Font[]
     */
    define( "FONT_REGISTRY", $fonts );
}

function ggl_enqueue_scripts(): void {
    wp_enqueue_script( 'menu-toggle', get_stylesheet_directory_uri() . '/assets/js/menu-toggle.js', ver: md5_file( get_stylesheet_directory() . '/assets/js/menu-toggle.js' ) );
    wp_enqueue_script( 'list-toggle', get_stylesheet_directory_uri() . '/assets/js/program-list-toggle.js', ver: md5_file( get_stylesheet_directory() . '/assets/js/program-list-toggle.js' ) );
}

function ggl_enqueue_styles() {
    wp_enqueue_style( 'dashicons' );
    if ( is_user_logged_in() ) {
        wp_enqueue_style( "simple-icons", get_stylesheet_directory_uri() . '/assets/css/simple-icons.css', ver: md5_file( get_stylesheet_directory() . "/assets/css/simple-icons.css" ) );
    }

    if ( defined( "WP_DEBUG" ) && WP_DEBUG ) {
        wp_enqueue_style( "gegenlicht-main", get_stylesheet_directory_uri() . '/style.css', ver: md5_file( get_stylesheet_directory() . '/style.css' ) );
    } else {
        wp_enqueue_style( "gegenlicht-main", get_stylesheet_directory_uri() . '/style.min.css', ver: md5_file( get_stylesheet_directory() . '/style.min.css' ) );
    }

    if ( function_exists( 'ggl_special_program_get_stylesheet_path' ) ) {
        if ( is_front_page() ) {
            foreach ( get_theme_mod( 'displayed_special_programs' ) as $termID ) {
                $term      = get_term( $termID, "special-program" );
                $path      = ggl_special_program_get_stylesheet_path( $term );
                $http_path = str_replace( get_home_path(), "/", $path );
                wp_enqueue_style( $term->slug, $http_path, ver: md5_file( $path ) );
            }

        }
        $specialProgram = rwmb_get_value( "program_type" ) == "special_program" ? rwmb_get_value( "special_program" ) : false;
        if ( ! $specialProgram ) {
            return;
        }
        $path      = ggl_special_program_get_stylesheet_path( $specialProgram );
        $http_path = str_replace( get_home_path(), "/", $path );
        wp_enqueue_style( $specialProgram->slug, $http_path, ver: md5_file( $path ) );
    }
}


function ggl_remove_default_styles() {
    wp_dequeue_style( 'global-styles-inline-css' );
    wp_dequeue_style( 'wp-block-library-css' );
    wp_dequeue_style( 'wp-block-library' );
    wp_dequeue_style( 'wp-block-library-theme' );
    wp_dequeue_style( 'wc-blocks-style' );
    wp_dequeue_style( 'classic-theme-styles' );
    wp_dequeue_style( "global-styles" );
}


function ggl_disable_tinymce_emojis() {
    add_filter( 'tiny_mce_plugins', function ( $plugins ) {
        if ( is_array( $plugins ) ) {
            return array_diff( $plugins, array( 'wpemoji' ) );
        } else {
            return array();
        }
    } );
}

function ggl_disable_wp_emoji_styles(): void {
    remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
    remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
    remove_action( 'wp_print_styles', 'print_emoji_styles' );
    remove_action( 'admin_print_styles', 'print_emoji_styles' );

}

function ggl_disable_emoji_staticization(): void {
    remove_action( 'the_content_feed', 'wp_staticize_emoji', 7 );
    remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
    remove_filter( 'comment_text_rss', 'wp_staticize_emoji' );
    remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );
}

function ggl_setup_theme_supports(): void {
    add_theme_support( "post-thumbnails" );
    add_theme_support( "automatic-feed-links" );
}


function ggl_add_image_sizes(): void {
    add_image_size( 'mobile', 800, 1000, crop: true );
    add_image_size( 'desktop', 800, 450, crop: true );

}

require_once "src/inc/theme-functions.php";