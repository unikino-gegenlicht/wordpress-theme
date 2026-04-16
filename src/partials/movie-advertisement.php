<?php
$isFollowup = (bool) $args['followUp'] ?? false;
$isLast     = (bool) $args['last'] ?? false;
$post       = get_post( $args['post_id'] ?? - 1 );
$anonymize  = ! apply_filters( "ggl_show_full_details", false );

try {
    $tz = new DateTimeZone( wp_timezone_string() );
} catch ( DateInvalidTimeZoneException $e ) {
    error_log( "Invalid timezone set in wordpress, falling back to Europe/Berlin" );
    $tz = new DateTimeZone( "Europe/Berlin" );
}

$today = new DateTimeImmutable( "today", $tz );
$now   = new DateTimeImmutable( "now", $tz );

$imageID = - 1;
if ( $anonymize ) {
    $imageID = get_theme_mod( 'anonymous_image' );
    if ( rwmb_get_value( "program_type" ) == "special_program" ) {
        $specialProgram = rwmb_get_value( "special_program" );
        $imageID        = get_term_meta( $specialProgram->term_id, "anonymous_image", single: true );
    }
}


$running_time      = ggl_get_running_time( $post ) + 10;
$movie_ends_at     = ggl_get_starting_time( $post )->add( new DateInterval( "PT{$running_time}M" ) );
$currently_running = $now > ggl_get_starting_time( $post ) && $now < $movie_ends_at;

if ( $movie_ends_at < $now ) {
    return;
}
?>
<article class="next-movie pt-0 <?= $isFollowup ? 'follow-up' : '' ?> <?= $isLast ? 'pb-5' : '' ?>">
    <header class="next-movie-header">
        <p>
            <?php if ( $currently_running ): ?>
                <span class="icon-text">
                    <span class="icon blink">
                        <span class="material-symbols filled" style="color: red !important;">circle</span>
                    </span>
                    <span><?= esc_html__( "Currently screening", "gegenlicht" ) ?></span>
                </span>
            <?php
            else:


                if ( $isFollowup ):
                    echo esc_html__( "Afterwards at", 'gegenlicht' );
                else:
                    echo match ( $post->post_type ) {
                        "movie" => esc_html__( "Next Screening", 'gegenlicht' ),
                        "event" => esc_html__( "Next Event", 'gegenlicht' ),
                        default => esc_html__( "Up next", "gegenlicht" ),
                    };
                endif;
            endif;
            ?>
        </p>
        <p class="is-size-6 m-0 p-0">
            <?php
            if ( $isFollowup ):
                echo ggl_get_starting_time( $post )->format( str_starts_with( get_user_locale(), "de" ) ? GGL_THEME__GERMAN_TIME_FORMAT : GGL_THEME__ENGLISH_TIME_FORMAT );
            else:
                if ( $currently_running ):
                    $remaining_time     = $movie_ends_at->diff( $now );
                    $remaining_time_str = "";

                    if ( $remaining_time->h > 0 ) {
                        $remaining_time_str .= sprintf( _n( "%d hour", "%d hours", $remaining_time->h, "gegenlicht" ), number_format_i18n( $remaining_time->h ) );
                    }
                    if ( $remaining_time->i > 3 ) {
                        $remaining_time_str .= " " . sprintf( _n( "%d minute", "%d minutes", $remaining_time->i, "gegenlicht" ), number_format_i18n( $remaining_time->i ) );
                    } else {
                        $remaining_time_str .= __( "a few minutes", "gegenlicht" );
                    }

                    echo esc_html__( "Ends in", "gegenlicht" ) . "&nbsp;" . esc_html( mb_trim( $remaining_time_str ) );
                else:
                    $start = ggl_get_starting_time( $post )->setTime( 0, 0, 0 );
                    $diff  = $today->diff( $start );
                    if ( $diff->days == 0 ) {
                        echo esc_html__( "Today at", 'gegenlicht' ) . "&nbsp;" . ggl_get_starting_time( $post )->format( str_starts_with( get_user_locale(), "de" ) ? GGL_THEME__GERMAN_TIME_FORMAT : GGL_THEME__ENGLISH_TIME_FORMAT );
                    }
                    if ( $diff->days == 1 ) {
                        echo esc_html__( "Tomorrow at", 'gegenlicht' ) . "&nbsp;" . ggl_get_starting_time( $post )->format( str_starts_with( get_user_locale(), "de" ) ? GGL_THEME__GERMAN_TIME_FORMAT : GGL_THEME__ENGLISH_TIME_FORMAT );
                    } else {
                        echo ggl_get_starting_time( $post )->format( str_starts_with( get_user_locale(), "de" ) ? GGL_THEME__GERMAN_DATETIME_FORMAT : GGL_THEME__ENGLISH_DATETIME_FORMAT );
                    }
                endif;
            endif;
            ?>
        </p>
    </header>
    <div class="content">
        <h2 class="title next-movie-title py-4">
            <?= ggl_get_localized_title( $post ) ?>
        </h2>
    </div>
    <?php
    if ( $post->post_type === "movie" ):
        ggl_the_movie_thumbnail( $post );
    else:
        ggl_the_event_thumbnail( $post );
    endif;
    ?>
    <hr class="separator"/>
    <?php
    get_template_part( "src/partials/button", args: [
            "href"    => get_the_permalink(),
            'content' => $post->post_type == 'movie' ? esc_html__( 'To the movie', 'gegenlicht' ) : esc_html__( 'To the event', 'gegenlicht' )
    ] );
    ?>
</article>
