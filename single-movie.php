<?php
defined( 'ABSPATH' ) || exit;
get_header();

$anonymize        = rwmb_get_value( "license_type" ) != "full" && ! is_user_logged_in();
$isSpecialProgram = rwmb_get_value( "program_type" ) === "special_program";


?>
<main>
    <header class="page-content">
        <div class="screening-information pt-0 <?= ( ! $anonymize && ggl_get_title() != rwmb_meta( 'original_title' ) ) ? '' : 'pb-0' ?>">
            <div>
                <p><?= esc_html__( 'Screening', 'gegenlicht' ) ?></p>
                <p>
                    <time
                            datetime="<?= date( 'Y-m-d H:i', rwmb_meta( 'screening_date' ) ) ?>">
                        <?= date( 'd.m.Y | H:i', rwmb_meta( 'screening_date' ) ) ?>
                    </time>
                </p>
            </div>
            <div class="is-justify-content-right">
                <?php
                $admission_type = rwmb_meta( 'admission_type' );

                switch ( $admission_type ) {
                    case 'free':
                        echo "<p>" . esc_html__( 'Free Admission', 'gegenlicht' ) . "</p>";
                        break;
                    case 'donation':
                        echo "<p>" . esc_html__( 'Donations welcome', 'gegenlicht' ) . "</p>";
                        break;
                    case 'paid':
                        $admissionFee = (float) rwmb_meta( 'admission_fee' );
                        echo "<p>" . esc_html__( 'Admission', 'gegenlicht' ) . " " . number_format( $admissionFee, 2, get_locale() == 'en' ? '.' : "," ) . "&euro;</p>";

                }
                ?>
            </div>
        </div>
        <div class="content mb-0">
            <h1 role="heading"
                class="mb-0 no-separator <?= ( ! $anonymize && ggl_get_title() != rwmb_meta( 'original_title' ) ) ? "" : "pt-2" ?>">
                <?= ggl_get_title() ?>
            </h1>
            <?php if ( ! $anonymize && ggl_get_title() != rwmb_meta( 'original_title' ) ): ?>
                <p class="is-size-5 mb-0"><?= rwmb_meta( 'original_title' ) ?></p>
            <?php endif; ?>
        </div>
        <hr class="separator"/>
        <div class="mt-2">
            <p>
                <?= join( '/', rwmb_meta( 'country' ) ) ?> <?= date( 'Y', strtotime( rwmb_meta( 'release_date' ) ) ) ?>
                |
                <?= rwmb_meta( 'running_time' ) ?> <?= esc_html__( 'Minutes', 'gegenlicht' ) ?>
            </p>
            <p>
                <?= esc_html__( 'by', 'gegenlicht' ) ?> <?= ! $anonymize ? rwmb_meta( 'director' )->name : trim( preg_replace( '/\w/', '█', rwmb_meta( 'director' )->name ) ) ?>
            </p>
            <p>
                <?php
                $actors     = rwmb_meta( 'actors' );
                $actorNames = array();
                foreach ( $actors as $actor ) {
                    if ( ! $anonymize ) {
                        $actorNames[] = $actor->name;
                    } else {
                        $actorNames[] = preg_replace( '/\w/', '█', $actor->name );
                    }
                }
                ?>
                <?= esc_html__( 'with', 'gegenlicht' ) ?> <?= join( separator: ' ' . esc_html__( 'and', 'gegenlicht' ) . ' ', array: $actorNames ) ?>
            </p>
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
                $audioLanguage    = rwmb_meta( 'audio_language' );
                $subtitleLanguage = rwmb_meta( 'subtitle_language' );

                if ( $audioType == 'original' ):
                    if ( $subtitleLanguage == 'eng' ):
                        echo '<span class="tag is-rounded is-primary has-tooltip-arrow has-tooltip-bottom"  data-tooltip="' . esc_html__( "Audio Language:", "gegenlicht" ) . '&nbsp;' . esc_html__( $audioLanguage, "gegenlicht" ) . PHP_EOL . esc_html__( "Subtitle Language:", "gegenlicht" ) . '&nbsp;' . esc_html__( $subtitleLanguage, "gegenlicht" ) . '">OmeU</span>';
                    elseif ( $subtitleLanguage == 'zxx' ):
                        echo '<span class="tag is-rounded is-primary has-tooltip-arrow has-tooltip-bottom"  data-tooltip="' . esc_html__( "Audio Language:", "gegenlicht" ) . '&nbsp;' . esc_html__( $audioLanguage, "gegenlicht" ) . PHP_EOL . esc_html__( "Subtitle Language:", "gegenlicht" ) . '&nbsp;' . esc_html__( "None", "gegenlicht" ) . '">OV</span>';
                    else:
                        echo '<span class="tag is-rounded is-primary has-tooltip-arrow has-tooltip-bottom"  data-tooltip="' . esc_html__( "Audio Language:", "gegenlicht" ) . '&nbsp;' . esc_html__( $audioLanguage, "gegenlicht" ) . PHP_EOL . esc_html__( "Subtitle Language:", "gegenlicht" ) . '&nbsp;' . esc_html__( $subtitleLanguage, "gegenlicht" ) . '">OmU</span>';
                    endif;
                endif;

                if ( $audioType == 'synchronization' ):
                    if ( $subtitleLanguage == 'zxx' ):
                        echo '<span class="tag is-rounded is-primary has-tooltip-arrow has-tooltip-bottom"  data-tooltip="' . esc_html__( "Audio Language:", "gegenlicht" ) . '&nbsp;' . esc_html__( $audioLanguage, "gegenlicht" ) . PHP_EOL . esc_html__( "Subtitle Language:", "gegenlicht" ) . '&nbsp;' . esc_html__( "None", "gegenlicht" ) . '">' . esc_html__( "Dub w/o Subs", "gegenlicht" ) . '</span>';
                    elseif ( $subtitleLanguage == 'eng' ):
                        echo '<span class="tag is-rounded is-primary has-tooltip-arrow has-tooltip-bottom"  data-tooltip="' . esc_html__( "Audio Language:", "gegenlicht" ) . '&nbsp;' . esc_html__( $audioLanguage, "gegenlicht" ) . PHP_EOL . esc_html__( "Subtitle Language:", "gegenlicht" ) . '&nbsp;' . esc_html__( $subtitleLanguage, "gegenlicht" ) . '">' . esc_html__( "Dub w/ eng. Subs", "gegenlicht" ) . '</span>';
                    else:
                        echo '<span class="tag is-rounded is-primary has-tooltip-arrow has-tooltip-bottom"  data-tooltip="' . esc_html__( "Audio Language:", "gegenlicht" ) . '&nbsp;' . esc_html__( $audioLanguage, "gegenlicht" ) . PHP_EOL . esc_html__( "Subtitle Language:", "gegenlicht" ) . '&nbsp;' . esc_html__( $subtitleLanguage, "gegenlicht" ) . '">' . esc_html__( "Dub w/ Subs", "gegenlicht" ) . '</span>';
                    endif;
                endif;


                ?>
                <?php
                if ( function_exists( 'ggl_cpt__generate_single_ical' ) && function_exists( 'ggl_cpt__serialize_icals' ) ):
                    $ical = ggl_cpt__generate_single_ical( $post );
                    $serializedData = ggl_cpt__serialize_icals( [ $ical ] );
                    ?>
                    <span class="tag is-rounded is-primary ml-auto">
                    <a href="<?= $serializedData ?>"
                       download="<?= ggl_get_title() ?>.ics"
                       style="color: var(--bulma-body-color)">
                            <span class="icon is-medium"><span class="material-symbols"
                                                               style="font-size: 24px">calendar_add_on</span></span>
                    </a>
                </span>
                <?php endif; ?>
            </div>
        </div>


        </span>
        <?php ggl_the_post_thumbnail(); ?>
        <?php if ( ! ! $anonymize && ! $isSpecialProgram ): ?>
            <div class="boxed-text mt-3">
                <?= apply_filters( "the_content", get_theme_mod( 'anonymized_movie_explainer' )[ get_locale() ] ?? "" ) ?>
            </div>
        <?php endif; ?>
        <?php if ( $isSpecialProgram ): ?>
            <div class="boxed-text mt-3">
                <?= apply_filters( "the_content", rwmb_get_value( "special_program" )->description ) ?>
            </div>
        <?php endif; ?>
    </header>
    <?php if ( rwmb_meta( "allow_reservations" ) ): ?>
        <div class="reservation-button">
            <div class="page-content">
                <?php get_template_part( 'partials/button', args: [
                        'href'     => rwmb_get_value( "reservation_url" ),
                        'content'  => esc_html__( 'Reserve Now', 'gegenlicht' ),
                        'external' => false,
                        'icon'     => 'confirmation_number'
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
        <?= apply_filters( "the_content", ggl_get_summary() ) ?>
        <h2 class="font-ggl is-size-3 is-uppercase mt-6">
            <?= esc_html__( "Why it's worth watching", 'gegenlicht' ) ?>
        </h2>
        <?= apply_filters( "the_content", ggl_get_worth_to_see() ) ?>
        <?php if ( rwmb_meta( 'short_movie_screened' ) == 'yes' && is_user_logged_in() ): ?>
            <h2 class="font-ggl is-size-3 is-uppercase">
                <?= esc_html__( 'Short Movie' ) ?>
            </h2>
            <p class="m-0"><?= rwmb_meta( 'short_movie_title' ) ?></p>
            <div class="is-flex short-details">
                <p><?= esc_html__( 'by' ) ?> <?= rwmb_meta( 'short_movie_directed_by' ) ?></p>
                |
                <p><?= join( '/', rwmb_meta( 'short_movie_country' ) ) ?> <?= rwmb_meta( 'short_movie_release_year' ) ?></p>
                |
                <p><?= rwmb_meta( 'short_movie_running_time' ) ?> <?= esc_html__( 'Minutes' ) ?></p>
            </div>
        <?php endif; ?>
    </article>
    <?php if ( rwmb_meta( "allow_reservations" ) ): ?>
        <div class="reservation-button">
            <div class="page-content">
                <?php get_template_part( 'partials/button', args: [
                        'href'     => rwmb_get_value( "reservation_url" ),
                        'content'  => esc_html__( 'Reserve Now', 'gegenlicht' ),
                        'external' => false,
                        'icon'     => 'confirmation_number'
                ] ) ?></div>
        </div>
    <?php endif; ?>
</main>
<section id="proposed-by">
    <?php
    get_template_part( "partials/proposal-list" )
    ?>
</section>
<?php get_footer(); ?>



