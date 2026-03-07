<?php
/**
 * GEGENLICHT Theme Functions
 *
 * The functions in this file are used to shorten the code in other places and make it
 * easier to maintain
 */


/**
 * Get advertisable movies and events
 *
 * @param int $semesterID
 *
 * @return WP_Post[] Movies and Events that shall be advertised for on the frontpage
 */
function ggl_get_advertisements( int $semesterID ): array {
    $posts = [];

    $args = array(
            "post_type"      => [ "movie", "event" ],
            "posts_per_page" => 1,
            "meta_key"       => "screening_date",
            "orderby"        => "meta_value_num",
            "order"          => "ASC",
            "tax_query"      => [
                    [
                            "taxonomy" => "semester",
                            "terms"    => $semesterID,
                    ]
            ]
    );

    $metaQuery = [
            [
                    "key"     => "screening_date",
                    "value"   => time(),
                    "compare" => ">="
            ]
    ];

    $query = new WP_Query( array_merge( $args, [ "meta_query" => $metaQuery ] ) );
    if ( ! $query->have_posts() ) {
        return [];
    }

    $query->the_post();
    $posts[] = $query->post;

    $metaQuery = [
            [
                    "key"     => "screening_date",
                    "value"   => [
                            (int) rwmb_get_value( "screening_date" ) + 1,
                            strtotime( "+8 hours", (int) rwmb_get_value( "screening_date" ) ) ?: 0,
                    ],
                    "compare" => "BETWEEN"
            ]
    ];

    wp_reset_postdata();


    $query = new WP_Query( array_merge( $args, [ "meta_query" => $metaQuery, "posts_per_page" => - 1 ] ) );
    while ( $query->have_posts() ) {
        $query->the_post();
        $posts[] = $query->post;
    }
    wp_reset_postdata();


    return $posts;
}

function is_location_page(): bool {
    return ( get_post()->ID ?? - 2 ) === (int) get_theme_mod( 'location_detail_page' );
}

function is_impress_page(): bool {
    return ( get_post()->ID ?? - 2 ) == get_theme_mod( 'impress_page' );
}

function is_contact_page(): bool {
    return ( get_post()->ID ?? - 2 ) == get_theme_mod( 'contact_page' );
}

/**
 * Get the title of a post
 *
 * This function supersedes the inbuilt `get_the_title()` function as it automatically handles the anonymization for
 * events and movies. Additionally, the function automatically handles the retrieval of the german or english title.
 * If a other type of post is used the function will just return the result of the inbuilt function.
 *
 * @param WP_Post|int $post Defaults to global $post. The post the title should be loaded from
 *
 * @return string
 */
function ggl_get_title( WP_Post|int $post = 0 ): string {
    $post = get_post( $post );
    if ( $post->post_type !== "movie" && $post->post_type !== "event" ) {
        return get_the_title( $post );
    }

    $show_details = apply_filters( "ggl__show_full_details", false, $post );

    if ( $show_details ) {
        return get_locale() == "de" ? mb_trim( rwmb_get_value( 'german_title', post_id: $post->ID ) ) : mb_trim( rwmb_get_value( 'english_title', post_id: $post->ID ) );
    }

    $inSpecialProgram = rwmb_get_value( 'program_type', post_id: $post->ID ) == 'special_program';
    if ( $inSpecialProgram ) {
        return rwmb_get_value( 'special_program', post_id: $post->ID )->name;
    }

    if ( $post->post_type === "event" ) {
        return __( "An unnamed event", "gegenlicht" );
    }

    return __( "An unnamed movie", "gegenlicht" );
}


function ggl_get_summary( WP_Post|int $post = 0, bool $plain = false ): string {
    $post = get_post( $post );
    $val  = "";
    if ( $post->post_type !== "movie" && $post->post_type !== "event" ) {
        return $val;
    }

    $show_details = apply_filters( "ggl__show_full_details", false, $post );


    if ( ! $show_details ) {
        $val = rwmb_get_value( "anon_summary" );
    } else {
        $val = rwmb_get_value( "summary" );
    }

    return ggl_cleanup( $val, $plain );
}

function ggl_get_worth_to_see( WP_Post|int $post = 0, bool $plain = false ): string {
    $post = get_post( $post );
    $val  = "";
    if ( $post->post_type !== "movie" && $post->post_type !== "event" ) {
        return $val;
    }

    $show_details = apply_filters( "ggl__show_full_details", false, $post );

    if ( ! $show_details ) {
        $val = rwmb_get_value( "anon_worth_to_see" );
    } else {
        $val = rwmb_get_value( "worth_to_see" );
    }

    return ggl_cleanup( $val, $plain );
}

function ggl_cleanup( $content, bool $plain = false ): string {
    return $plain ? strip_tags( $content ) : strip_tags( $content, [
            "p",
            "ul",
            "ol",
            "li",
            "blockquote",
            "strong",
            "em",
            "del",
            "span"
    ] );

}

function ggl_get_thumbnail_url( WP_Post|int $post = 0, string $size = "full" ): false|string {
    $post = get_post( $post );
    if ( $post->post_type !== "movie" && $post->post_type !== "event" ) {
        return get_the_post_thumbnail_url( $post, $size );
    }

    $fallbackImageUrl = wp_get_attachment_image_url( get_theme_mod( 'anonymous_image' ), $size );

    $show_details = apply_filters( "ggl__show_full_details", false, $post );

    if ( $show_details ) {
        return get_the_post_thumbnail_url( $post, $size ) ?: $fallbackImageUrl;
    }

    $inSpecialProgram = rwmb_meta( 'program_type' ) == 'special_program';
    if ( $inSpecialProgram ) {
        $specialProgram = rwmb_get_value( 'special_program' );

        return wp_get_attachment_image_url( get_term_meta( $specialProgram->term_id, "anonymous_image", single: true ), $size ) ?: $fallbackImageUrl;
    }

    return $fallbackImageUrl;
}


function ggl_the_post_thumbnail( WP_Post|int $post = 0 ): void {
    $post                    = get_post( $post );
    $mobileUrl               = ggl_get_thumbnail_url( $post, "mobile" );
    $desktopUrl              = ggl_get_thumbnail_url( $post, "desktop" );
    $landscape_animation_url = wp_get_original_image_url( rwmb_meta( "landscape_animated_feature_image" )["ID"] ?? false );
    $portrait_animation_url  = wp_get_original_image_url( rwmb_meta( "portrait_animated_feature_image" )["ID"] ?? false );
    $is_animated             = rwmb_meta( "use_animated_feature_image" );

    $show_details = apply_filters( "ggl__show_full_details", false, $post );
    ?>
    <picture class="image movie-image">
        <source height="450" width="800" media="(prefers-reduced-motion: reduce) and (width > 768px)"
                srcset="<?= $desktopUrl ?>">
        <source height="1000" width="800" media="(prefers-reduced-motion: reduce) and (width <= 768px)"
                srcset="<?= $mobileUrl ?>">
        <source height="450" width="800" media="(width > 768px)"
                srcset="<?= $is_animated && $show_details ? $landscape_animation_url : $desktopUrl ?> ">
        <source height="1000" width="800" media="(width <= 768px)"
                srcset="<?= $is_animated && $show_details ? $portrait_animation_url : $mobileUrl ?> ">
        <img width="800" height="1000" src="<?= $mobileUrl ?>">
    </picture>

    <?php
}


function ggl_get_translate_rating_descriptor( string $descriptorKey ): string {
    $descriptors = [
            'sexualized_violence' => esc_html__( 'Sexualized Violence', 'gegenlicht' ),
            'violence'            => esc_html__( 'Violence', 'gegenlicht' ),
            'self_harm'           => esc_html__( 'Self Harm', 'gegenlicht' ),
            'drug_usage'          => esc_html__( 'Drug Usage', 'gegenlicht' ),
            'discrimination'      => esc_html__( 'Discrimination', 'gegenlicht' ),
            'sexuality'           => esc_html__( 'Sexuality', 'gegenlicht' ),
            'threat'              => esc_html__( 'Threat', 'gegenlicht' ),
            'injury'              => esc_html__( 'Injury', 'gegenlicht' ),
            'stressful_topics'    => esc_html__( 'Stressful Topics', 'gegenlicht' ),
            'language'            => esc_html__( 'Language', 'gegenlicht' ),
            'nudity'              => esc_html__( 'Nudeness', 'gegenlicht' ),
            'risky_behaviour'     => esc_html__( 'Risky Behaviour', 'gegenlicht' ),
            'marginalization'     => esc_html__( 'Marginalization', 'gegenlicht' ),
    ];
    if ( array_key_exists( $descriptorKey, $descriptors ) ) {
        return $descriptors[ $descriptorKey ];
    }

    return $descriptorKey;
}

function ggl_resolve_country_list( array $selectedCountries ): array {
    require_once get_stylesheet_directory() . '/src/inc/defunct-countries.php';

    $country_list = [];
    foreach ( $selectedCountries as $numeric ) {
        try {
            $country = ( new League\ISO3166\ISO3166 )->numeric( $numeric );
        } catch ( League\ISO3166\Exception\OutOfBoundsException $e ) {
            foreach ( $defunct_countries as $country ) {
                $comparison = mb_strtolower( $country['numeric'] );
                if ( $numeric === $comparison || $numeric === mb_substr( $comparison, 0, mb_strlen( $numeric ) ) ) {
                    $country_list[] = $country["alpha2"];
                }
            }
        }
        $country_list[] = $country["alpha2"];
    }

    return $country_list;
}