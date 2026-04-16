<?php
defined( 'ABSPATH' ) || exit;
get_header();

$show_details     = apply_filters( "ggl__show_full_details", false, $post );
$isSpecialProgram = rwmb_get_value( "program_type" ) === "special_program" && rwmb_get_value("special_program") !== false;
$specialProgram = $isSpecialProgram ? rwmb_get_value("special_program") : null;

try {
    $tz = new DateTimeZone( wp_timezone_string() );
} catch ( DateInvalidTimeZoneException $e ) {
    error_log( "Invalid timezone set in wordpress, falling back to Europe/Berlin" );
    $tz = new DateTimeZone( "Europe/Berlin" );
}

$today = new DateTimeImmutable( "today", $tz );
$now   = new DateTimeImmutable( "now", $tz );

$running_time      = ggl_get_running_time( $post ) + 10;
$movie_ends_at     = ggl_get_starting_time( $post )->add( new DateInterval( "PT{$running_time}M" ) );
$currently_running = $now > ggl_get_starting_time( $post ) && $now < $movie_ends_at;

?>
<main>
    <header class="page-content">
        <div class="screening-information pt-0 <?= ( ( ggl_get_title() !== ggl_get_localized_title() ) ) ? '' : 'pb-0' ?>">
            <div>
                <?php if ( $currently_running ) : ?>
                    <p>
                    <span class="icon-text">
                    <span class="icon blink">
                        <span class="material-symbols filled" style="color: red !important;">circle</span>
                    </span>
                    <span><?= esc_html__( "Currently screening", "gegenlicht" ) ?></span>
                    </span>
                    </p>
                    <?php
                    $remaining_time     = $movie_ends_at->diff( $now );
                    $remaining_time_str = "";

                    if ( $remaining_time->h > 0 ) {
                        $remaining_time_str .= sprintf( _n( "%d hour", "%d hours", $remaining_time->h, "gegenlicht" ), number_format_i18n( $remaining_time->h ) );
                    }
                    if ( $remaining_time->i > 3 ) {
                        $remaining_time_str .= " " .sprintf( _n( "%d minute", "%d minutes", $remaining_time->i, "gegenlicht" ), number_format_i18n( $remaining_time->i ) );
                    } else {
                        $remaining_time_str .= __("a few minutes", "gegenlicht" );
                    }

                    echo "<p>" . esc_html__( "Ends in", "gegenlicht" ) . "&nbsp;" . esc_html(mb_trim($remaining_time_str)) . "</p>";
                    ?>

                <?php else: ?>
                    <p><?= esc_html__( 'Screening', 'gegenlicht' ) ?></p>
                    <p>
                        <time
                                datetime="<?= date( 'Y-m-d H:i', rwmb_meta( 'screening_date' ) ) ?>">
                            <?php
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
                            ?>
                        </time>
                    </p>
                <?php endif; ?>
            </div>
            <div class="is-justify-content-right">
                <?= esc_html__( "Admission", "gegenlicht" ) ?>: <?php ggl_the_admission_fee() ?>
            </div>
        </div>
        <div class="content mb-0">
            <h1 role="heading"
                class="mb-0 no-separator <?= ( ggl_get_title() !== ggl_get_localized_title() ) ? "" : "pt-2" ?>">
                <?= ggl_get_localized_title() ?>
            </h1>
            <?php if ( ggl_get_title() !== ggl_get_localized_title() ): ?>
                <p class="is-size-5 mb-0"><?= ggl_get_title() ?></p>
            <?php endif; ?>
        </div>
        <hr class="separator"/>
        <div class="mt-2">
            <p>
                <?php ggl_the_countries_of_origin(); ?>&nbsp;<?php ggl_the_release_date(); ?>
                |
                <?php ggl_the_running_time(); ?>
            </p>
            <p>
                <?= esc_html__( 'by', 'gegenlicht' ) ?>&nbsp;<?php ggl_the_movie_director(); ?>
            </p>
            <p><?php ggl_the_actors(); ?></p>
        </div>
        <hr class="separator"/>
        <div class="mb-2">
            <div class="tags are-medium is-flex-grow-1">
                <?php
                $ageRating   = rwmb_meta( 'age_rating' );
                $descriptors = rwmb_meta( 'descriptors' );

                $translatedDescriptors = array();

                foreach ( $descriptors as $descriptor ) {
                    $translatedDescriptors[] = ggl_get_translate_rating_descriptor( $descriptor );
                }

                switch ( $ageRating ) {
                    case - 2:
                    case - 1:
                        echo '<span class="tag is-rounded is-primary ' . ( ! empty( $translatedDescriptors ) ? ( 'has-tooltip-arrow has-tooltip-bottom has-tooltip-text-left" data-tooltip="' . join( PHP_EOL, $translatedDescriptors ) . '"' ) : '' ) . '">' . esc_html__( 'Not Rated', 'gegenlicht' ) . '</span>';
                        break;
                    default:
                        echo '<span class="tag is-rounded is-primary ' . ( ! empty( $translatedDescriptors ) ? ( 'has-tooltip-arrow has-tooltip-bottom has-tooltip-text-left" data-tooltip="' . join( PHP_EOL, $translatedDescriptors ) . '"' ) : '' ) . '">FSK ' . $ageRating . '</span>';

                }
                ?>

                <?php
                $audioType        = rwmb_meta( 'audio_type' );
                $audioLanguage    = ggl_get_audio_language();
                $subtitleLanguage = rwmb_meta( 'subtitle_language' );

                if ( $audioType == 'original' ):
                    if ( $subtitleLanguage == 'eng' ):
                        echo '<span class="tag is-rounded is-primary has-tooltip-arrow has-tooltip-bottom"  data-tooltip="' . esc_html__( "Audio Language:", "gegenlicht" ) . '&nbsp;' . ggl_the_audio_language( output: false ) . PHP_EOL . esc_html__( "Subtitle Language:", "gegenlicht" ) . '&nbsp;' . ggl_the_subtitle_language( output: false ) . '">OmeU</span>';
                    elseif ( $subtitleLanguage == 'zxx' ):
                        echo '<span class="tag is-rounded is-primary has-tooltip-arrow has-tooltip-bottom"  data-tooltip="' . esc_html__( "Audio Language:", "gegenlicht" ) . '&nbsp;' . ggl_the_audio_language( output: false ) . PHP_EOL . esc_html__( "Subtitle Language:", "gegenlicht" ) . '&nbsp;' . ggl_the_subtitle_language( output: false ) . '">OV</span>';
                    else:
                        echo '<span class="tag is-rounded is-primary has-tooltip-arrow has-tooltip-bottom"  data-tooltip="' . esc_html__( "Audio Language:", "gegenlicht" ) . '&nbsp;' . ggl_the_audio_language( output: false ) . PHP_EOL . esc_html__( "Subtitle Language:", "gegenlicht" ) . '&nbsp;' . ggl_the_subtitle_language( output: false ) . '">OmU</span>';
                    endif;
                endif;

                if ( $audioType == 'synchronization' ):
                    if ( $subtitleLanguage == 'zxx' ):
                        echo '<span class="tag is-rounded is-primary has-tooltip-arrow has-tooltip-bottom"  data-tooltip="' . esc_html__( "Audio Language:", "gegenlicht" ) . '&nbsp;' . ggl_the_audio_language( output: false ) . PHP_EOL . esc_html__( "Subtitle Language:", "gegenlicht" ) . '&nbsp;' . ggl_the_subtitle_language( output: false ) . '">' . esc_html__( "Dub w/o Subs", "gegenlicht" ) . '</span>';
                    elseif ( $subtitleLanguage == 'eng' ):
                        echo '<span class="tag is-rounded is-primary has-tooltip-arrow has-tooltip-bottom"  data-tooltip="' . esc_html__( "Audio Language:", "gegenlicht" ) . '&nbsp;' . ggl_the_audio_language( output: false ) . PHP_EOL . esc_html__( "Subtitle Language:", "gegenlicht" ) . '&nbsp;' . ggl_the_subtitle_language( output: false ) . '">' . esc_html__( "Dub w/ eng. Subs", "gegenlicht" ) . '</span>';
                    else:
                        echo '<span class="tag is-rounded is-primary has-tooltip-arrow has-tooltip-bottom"  data-tooltip="' . esc_html__( "Audio Language:", "gegenlicht" ) . '&nbsp;' . ggl_the_audio_language( output: false ) . PHP_EOL . esc_html__( "Subtitle Language:", "gegenlicht" ) . '&nbsp;' . ggl_the_subtitle_language( output: false ) . '">' . esc_html__( "Dub w/ Subs", "gegenlicht" ) . '</span>';
                    endif;
                endif;


                ?>
            </div>
        </div>

        <?php ggl_the_movie_thumbnail(); ?>
        <?php if ( ! $show_details && $isSpecialProgram !== false ): ?>
            <div class="boxed-text mt-3">
                <?= apply_filters( "the_content", get_theme_mod( 'anonymized_movie_explainer' )[ substr( get_user_locale(), 0, 2 ) ] ?? "" ) ?>
            </div>
        <?php endif; ?>
        <?php if ( $isSpecialProgram && ! empty( mb_trim( $specialProgram->description ) ) ): ?>
            <div class="boxed-text mt-3">
                <?= apply_filters( "the_content", rwmb_get_value( "special_program" )->description ) ?>
            </div>
        <?php endif; ?>
        <?php if ( get_theme_mod( "main_screening_location", null ) != null && get_theme_mod( "main_screening_location" ) != rwmb_get_value( "screening_location" ) ) : $screening_location = get_post( rwmb_get_value( "screening_location" ) ); ?>
            <div class="content-notice mt-2 p-2 content">
                <h2 class="is-size-4 border-is-background-color"><?= esc_html__( "Changed Screening Location", "gegenlicht" ) ?></h2>
                <p>
                    <?= esc_html__( "This screening is not taking place at our usual screening location. Instead the screening will be hosted at:", "gegenlicht" ) ?>
                    <?= $screening_location->post_title ?>,
                    <?= rwmb_get_value( "street", post_id: $screening_location->ID ) ?>,
                    <?= rwmb_get_value( "postal_code", post_id: $screening_location->ID ) ?> <?= rwmb_get_value( "city", post_id: $screening_location->ID ) ?>
                </p>
                <?php
                get_template_part( 'src/partials/location-button', args: [
                        "screening_location_id" => $screening_location->ID,
                ] ); ?>
            </div>
        <?php endif; ?>
    </header>
    <?php if ( time() < rwmb_get_value( 'screening_date' ) && ! empty( trim( rwmb_get_value( "pretix_event_url" ) ) ) ): ?>
        <div class="reservation-button">
            <div class="page-content">
                <?php get_template_part( 'src/partials/button', args: [
                        'href'               => rwmb_get_value( "pretix_event_url" ),
                        'content'            => esc_html__( 'Reserve Now', 'gegenlicht' ),
                        'external'           => true,
                        'icon'               => 'confirmation_number',
                        'additional-classes' => "plausible-event-name=Opened+Reservations+Page"
                ] ) ?></div>
        </div>
    <?php endif; ?>
    <article class="page-content px-2 my-4 content">
        <?php if ( rwmb_meta( 'show_content_notice' ) ): ?>
            <div class="content-notice mb-6 p-2">
                <h2 class="is-size-4 border-is-background-color"><?= esc_html__( "Content Notice", 'gegenlicht' ) ?></h2>
                <p>
                    <?= apply_filters( "the_content", rwmb_get_value( 'content_notice' ) ?? "" ) ?>
                </p>
            </div>
        <?php endif; ?>
        <h2 class="font-ggl is-size-3 is-uppercase">
            <?= esc_html__( 'What the movie is about', 'gegenlicht' ) ?>
        </h2>
        <?php ggl_the_summary(); ?>
        <h2 class="font-ggl is-size-3 is-uppercase mt-6">
            <?= esc_html__( "Why it's worth watching", 'gegenlicht' ) ?>
        </h2>
        <?php ggl_the_worth_to_see_section(); ?>
        <?php if ( ggl_movie_has_short() ): ?>
            <h2 class="font-ggl is-size-3 is-uppercase">
                <?= esc_html__( 'Short Movie', "gegenlicht" ) ?>
            </h2>
            <p class="m-0"><?php ggl_the_short_movie_title(); ?></p>
            <div class="is-flex short-details">
                <p><?= esc_html__( "by", "gegenlicht" ) ?>&nbsp;<?php ggl_the_short_movie_director() ?></p> |
                <p><?php ggl_the_short_movie_countries(); ?><?php ggl_the_short_movie_release_year() ?></p> |
                <p><?php ggl_the_short_movie_running_time() ?></p>
            </div>
        <?php endif; ?>
    </article>
    <?php if ( time() < rwmb_get_value( 'screening_date' ) && ! empty( trim( rwmb_get_value( "pretix_event_url" ) ) ) ): ?>
        <div class="reservation-button">
            <div class="page-content">
                <?php get_template_part( 'src/partials/button', args: [
                        'href'              => rwmb_get_value( "pretix_event_url" ),
                        'content'           => esc_html__( 'Reserve Now', 'gegenlicht' ),
                        'external'          => true,
                        'icon'              => 'confirmation_number',
                        'additionalClasses' => "plausible-event-name=Opened+Reservations+Page"
                ] ) ?></div>
        </div>
    <?php endif; ?>
</main>
<section id="proposed-by">
    <?php
    get_template_part( "src/partials/proposal-list" )
    ?>
</section>
<?php get_footer(); ?>



