<?php
defined( 'ABSPATH' ) || exit;
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
require_once "inc/constants.php";

/**
 * Load the customizer configuration
 */
require_once "inc/customizer.php";

add_action( "after_setup_theme", "ggl_setup_theme_supports" );
add_action( "after_setup_theme", "ggl_add_image_sizes" );
add_action( "customize_register", "configure_customizer" );
add_action( "init", "ggl_disable_wp_emoji_styles" );
add_action( "init", "ggl_disable_emoji_staticization" );
add_action( "init", "ggl_disable_tinymce_emojis" );
add_action( "wp_enqueue_scripts", "ggl_remove_default_styles" );
add_action( "wp_enqueue_scripts", "ggl_enqueue_styles" );
add_action( "wp_enqueue_scripts", "ggl_enqueue_scripts" );
add_action( "wp_enqueue_scripts", "ggl_send_link_headers", 90 );
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
add_filter( "locale", "ggl_locale_use_http_fallback", 10 );
add_filter( "locale", "ggl_locale_log", 11 );
add_action( "wp_head", "ggl_inject_special_program_colors" );
add_action( "get_header", "ggl_redirect_from_non_semester_pages", 2 );
add_action( "wp_head", "ggl_inject_movie_schema_markup" );
add_filter( "wpseo_opengraph_image", "ggl_anonymize_opengraph_image" );

function ggl_anonymize_opengraph_image( $original_image ) {
	if ( is_singular( [ "movie", "event" ] ) ) {
		return ggl_get_thumbnail_url( size: "desktop" );
	}

	return $original_image;
}

function ggl_inject_movie_schema_markup(): void {
	if ( ! is_singular( [ "movie", "event" ] ) ) {
		return;
	}

	$title          = ggl_get_title();
	$screeningStart = new DateTimeImmutable( date( "Y-m-d\TH:i:s", (int) rwmb_get_value( "screening_date" ) ) . " Europe/Berlin" );
	$duration       = (int) rwmb_get_value( "running_time" );
	$shortDuration  = (int) rwmb_get_value( "short_movie_running_time" );
	$totalDuration  = $duration + $shortDuration + 10;
	$screeningEnd   = $screeningStart->add( new DateInterval( "PT{$totalDuration}M" ) );

	$organizer     = "";
	$organizerType = "";
	$organizerUrl  = "";
	switch ( rwmb_get_value( "selected_by" ) ) {
		case "member":
			$selectorID    = rwmb_meta( 'team_member_id' );
			$member        = get_post( $selectorID );
			$organizer     = $member->post_title;
			$organizerType = "Person";
			$organizerUrl  = get_post_permalink( $member );
			break;
		case "cooperation":
			$selectorID          = rwmb_meta( 'cooperation_partner_id' );
			$cooperation_partner = get_post( $selectorID );
			$organizer           = $cooperation_partner->post_title . " & " . get_bloginfo( "name" );
			$organizerType       = "Organization";
			$organizerUrl        = get_post_permalink( $cooperation_partner );

			break;
	}

	$paidEvent    = rwmb_get_value( "admission_type" ) == "paid";
	$admissionFee = rwmb_get_value( "admission_fee" );

	$location = get_post( rwmb_get_value( "screening_location" ) );

	$schemaData["@context"]                               = "https://schema.org";
	$schemaData["@type"]                                  = "Event";
	$schemaData["name"]                                   = $title;
	$schemaData["startDate"]                              = $screeningStart->format( "c" );
	$schemaData["endDate"]                                = $screeningEnd->format( "c" );
	$schemaData["location"]["@type"]                      = "Place";
	$schemaData["location"]["name"]                       = $location->post_title;
	$schemaData["location"]["address"]["streetAddress"]   = rwmb_get_value( "street", post_id: $location->ID );
	$schemaData["location"]["address"]["addressLocality"] = rwmb_get_value( "city", post_id: $location->ID );
	$schemaData["location"]["address"]["postalCode"]      = rwmb_get_value( "postal_code", post_id: $location->ID );
	$schemaData["location"]["address"]["addressCountry"]  = rwmb_get_value( "country", post_id: $location->ID );
	$schemaData["image"]                                  = get_the_post_thumbnail_url( size: "full" ) ?: wp_get_attachment_image_url( get_theme_mod( "anonymous_image" ), 'full' );
	$schemaData["description"]                            = strip_tags( ggl_get_summary() );
	$schemaData["organizer"]["@type"]                     = $organizerType;
	$schemaData["organizer"]["name"]                      = $organizer;
	$schemaData["organizer"]["url"]                       = $organizerUrl;
	$schemaData["performer"]["@type"]                     = $organizerType;
	$schemaData["performer"]["name"]                      = $organizer;
	$schemaData["performer"]["url"]                       = $organizerUrl;
	$schemaData["eventStatus"]                            = "https://schema.org/EventScheduled";
	if ( rwmb_get_value( "allow_reservations" ) ):
		$schemaData["offers"] = [
			[
				"@type"         => "Offer",
				"availability"  => "https://schema.org/PreOrder",
				"priceCurrency" => "EUR",
				"price"         => $paidEvent ? $admissionFee : 0.00,
				"url"           => rwmb_get_value( "reservation_url" ),
				"validFrom"     => $screeningStart->sub( new DateInterval( "P28D" ) )->format( "c" ),
			]
		];
	endif;
	if ( is_singular( "movie" ) ):
		$schemaData["workFeatured"]["@type"]                    = "Movie";
		$schemaData["workFeatured"]["countryOfOrigin"]["@type"] = "Country";
		$schemaData["workFeatured"]["countryOfOrigin"]["name"]  = rwmb_meta( 'country' )[0] ?? "Unknown";
		$schemaData["workFeatured"]["name"]                     = $title;
		$schemaData["workFeatured"]["image"]                    = ggl_get_thumbnail_url();
		$schemaData["workFeatured"]["director"][]               = [
			"@type" => "Person",
			"name"  => rwmb_get_value( "director" )->name
		];
		$schemaData["workFeatured"]["duration"]                 = "PT{$duration}M";
		$schemaData["workFeatured"]["description"]              = strip_tags( ggl_get_summary() );
		$schemaData["workFeatured"]["dateCreated"]              = rwmb_get_value("release_date");
	endif;

	?>
    <script type="application/ld+json"><?= json_encode( $schemaData ) ?></script>
	<?php
}

function ggl_redirect_from_non_semester_pages(): void {
	if ( ! is_singular( [ "movie", "event" ] ) ) {
		return;
	}

	if ( current_user_can( "edit_posts" ) ) {
		return;
	}

	$visibleSemester  = (int) get_theme_mod( "displayed_semester" );
	$assignedSemester = rwmb_get_value( "semester" );

	if ( $visibleSemester === $assignedSemester->term_id ) {
		$screeningTs    = (int) rwmb_get_value( "screening_date" );
		$now            = new DateTimeImmutable( "now" );
		$screeningStart = new DateTime( date( "Y-m-d\TH:i:s", (int) rwmb_get_value( "screening_date" ) ) . " Europe/Berlin" );
		$availableUntil = $screeningStart->add( new DateInterval( "P14D" ) );
		$availableUntil = $availableUntil->setTimezone( new DateTimeZone( "UTC" ) );
		if ( $availableUntil < $now ) {
			goto issue410;
		}

		return;
	}

	issue410:
	remove_action( "wp_enqueue_scripts", "ggl_send_image_link_headers", 70 );
	remove_action( "wp_enqueue_scripts", "ggl_send_link_headers", 90 );
	remove_action( "wp_head", "ggl_inject_special_program_colors" );
	remove_action( "wp_head", "ggl_inject_movie_schema_markup" );
	add_filter( 'wpseo_frontend_presenter_classes', function ( $classes ) {
		$classes = array_filter( $classes, function ( $class ) {
			return strpos( $class, 'Open_Graph' ) === false;
		} );

		return $classes;
	} );


	header( $_SERVER["SERVER_PROTOCOL"] . " 410 Gone" );
	require_once "410.php";
	die;
}

function ggl_inject_special_program_colors(): void {
	if ( ! is_singular( [ "movie", "event" ] ) ) {
		return;
	}

	$specialProgram = rwmb_get_value( "program_type" ) == "special_program" ? rwmb_get_value( "special_program" ) : false;
	if ( ! $specialProgram ) {
		return;
	}

	$colors["light"]["background"] = get_term_meta( $specialProgram->term_id, 'background_color', true );
	$colors["light"]["body"]       = get_term_meta( $specialProgram->term_id, 'text_color', true );
	$colors["dark"]["background"]  = get_term_meta( $specialProgram->term_id, 'dark_background_color', true );
	$colors["dark"]["body"]        = get_term_meta( $specialProgram->term_id, 'dark_text_color', true );

	?>

    <style>
        :root {
            --bulma-body-color: <?= $colors["light"]["body"] ?> !important;
            --bulma-body-background-color: <?= $colors["light"]["background"] ?> !important;
        }

        @media (prefers-color-scheme: dark) {
            :root {
                --bulma-body-color: <?= $colors["dark"]["body"] ?> !important;
                --bulma-body-background-color: <?= $colors["dark"]["background"] ?> !important;
            }
        }

        .navbar {
            background-color: var(--bulma-body-background-color) !important;
        }

        a.navbar-item {
            color: var(--bulma-body-color) !important;
        }
    </style>

	<?php

}

function ggl_locale_log( string $locale ): string {
	error_log( $locale );

	return $locale;
}

function ggl_locale_use_http_fallback( string $locale ): string {
	if ( is_admin() && ! is_customize_preview() ) {
		return $locale;
	}

	if ( is_user_logged_in() ) {
		$locale = substr( $locale, 0, 2 );
		switch ( $locale ) {
			case 'en':
			case 'de':
				return $locale;
			default:
				return "en";
		}

	}

	if ( ! isset( $_SERVER['HTTP_ACCEPT_LANGUAGE'] ) ) {
		return $locale;
	}

	$preferredLocales = array_reduce( explode( ',', $_SERVER['HTTP_ACCEPT_LANGUAGE'] ), function ( $res, $el ) {
		list( $l, $q ) = array_merge( explode( ';q=', $el ), [ 1 ] );
		$res[ $l ] = (float) $q;

		return $res;
	}, [] );

	arsort( $preferredLocales );

	return substr( array_key_first( $preferredLocales ), 0, 2 );

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
	$query->set( "orderby", "name" );
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

	header( 'Link: <' . $mobileOptimizedImage . '>; rel=preload; as=image; fetchpriority="high;', false, 103 );
	header( 'Link: <' . $desktopOptimizedImage . '>; rel=preload; as=image; fetchpriority="high;', false, 103 );
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
            --login-logo: url(<?= wp_get_attachment_image_url(get_theme_mod("header_logo"), "full") ?: "/wp-includes/images/w-logo-blue.png" ?>)
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


function ggl_send_link_headers(): void {
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

	foreach ( $relativeStylePaths as $relativeStylePath ) {
		header( "Link: <{$relativeStylePath}>; rel=preload; as=style; fetchpriority=high;", false, 103 );
	}

	foreach ( $relativeScriptPaths as $relativeScriptPath ) {
		header( "Link: <{$relativeScriptPath}>; rel=preload; as=script; fetchpriority=high;", false, 103 );
	}

	header( 'Link: <' . parse_url( get_stylesheet_directory_uri(), PHP_URL_PATH ) . '/assets/fonts/gegenlicht.woff2>; rel=preload; as=font; crossorigin=anonymous; fetchpriority=high;', false, 103 );
	header( 'Link: <' . parse_url( get_stylesheet_directory_uri(), PHP_URL_PATH ) . '/assets/fonts/icons.woff2>; rel=preload; as=font; crossorigin=anonymous; fetchpriority=high;', false, 103 );
	header( 'Link: <' . parse_url( get_stylesheet_directory_uri(), PHP_URL_PATH ) . '/assets/fonts/inter.woff2>; rel=preload; as=font; crossorigin=anonymous; fetchpriority=high;', false, 103 );
	header( 'Link: <' . parse_url( get_stylesheet_directory_uri(), PHP_URL_PATH ) . '/assets/fonts/inter_italic.woff2>; rel=preload; as=font; crossorigin=anonymous; fetchpriority=high;', false, 103 );
	if ( is_user_logged_in() ):
		header( 'Link: <' . get_stylesheet_directory_uri() . '/assets/fonts/simple-icons.woff2>; rel=preload; as=font; crossorigin=anonymous; fetchpriority=high;', false, 103 );
	endif;
}

function ggl_enqueue_scripts(): void {
	wp_enqueue_script( 'menu-toggle', get_stylesheet_directory_uri() . '/assets/js/menu-toggle.js' );
	wp_enqueue_script( 'list-toggle', get_stylesheet_directory_uri() . '/assets/js/program-list-toggle.js' );
}

function ggl_enqueue_styles() {
	if ( is_user_logged_in() ) {
		wp_enqueue_style( "simple-icons", get_stylesheet_directory_uri() . '/assets/css/simple-icons.css', ver: md5_file( get_stylesheet_directory() . "/assets/css/simple-icons.css" ) );
	}

	if ( defined( "WP_DEBUG" ) && WP_DEBUG ) {
		wp_enqueue_style( "gegenlicht-main", get_stylesheet_directory_uri() . '/style.css', ver: md5_file( get_stylesheet_directory() . '/style.css' ) );
	} else {
		wp_enqueue_style( "gegenlicht-main", get_stylesheet_directory_uri() . '/style.min.css', ver: md5_file( get_stylesheet_directory() . '/style.min.css' ) );
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
	add_image_size( 'member-crop', 450, 600, crop: true );
}

require_once "inc/theme-functions.php";