<?php
defined( 'ABSPATH' ) || exit;

get_header();

$social_urls = ggl_get_teamie_social_links();
?>
<main class="page-content mt-4">
    <article class="content">
        <div class="is-flex is-dynamic-flex is-align-items-top is-justify-content-space-evenly is-flex-wrap-wrap is-gap-1 mb-5 content">
            <?php if ( ! empty( $social_urls ) ) : ?>
                <div class="is-flex is-flex-grow-0 is-flex-shrink-0 is-float-left is-flex-direction-column">
                    <?php ggl_the_teamie_image( classes: "image member-picture" ) ?>
                    <div class="is-flex is-gap-3 mt-2 is-justify-content-space-evenly">
                        <?php foreach ( $social_urls as $platform => $url ) : ?>
                            <a class="plausible-event-name=Open+Teamie+Social+Media" href="<?= esc_attr( $url ) ?>" title="">
                                <span class="icon is-large social-icon">
                                    <img alt="" height="48" width="48"
                                         src="<?= get_stylesheet_directory_uri() ?>/assets/img/<?= $platform ?>.svg">
                                </span>
                            </a>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php else: ?>
                <?php ggl_the_teamie_image( classes: "image member-picture is-flex-grow-0 is-flex-shrink-0" ) ?>
            <?php endif; ?>
            <header class="is-flex-grow-3 main-content word-break-break-word is-float-none">
                <h1 class="mb-1"><?php the_title() ?></h1>
                <?php if ( ggl_is_teamie_active() ): ?>
                    <p class="is-italic mb-3"><?php ggl_teamie_joined_in(); ?></p>
                <?php else: ?>
                    <p class="is-italic mb-3"><?php ggl_the_teamie_membership_duration(); ?></p>
                <?php endif; ?>
                <?php ggl_the_teamie_description(); ?>
            </header>
        </div>
    </article>
    <div class="my-5">
        <?php
        $entries             = ggl_get_teamie_movies();
        $upcoming_screenings = array_filter( $entries, function ( $entry ) {
            $now           = new DateTime();
            $starting_time = ggl_get_starting_time( $entry );

            return $now < $starting_time;
        } );
        $pastScreenings      = array_filter( $entries, function ( $entry ) {
            $now           = new DateTime();
            $starting_time = ggl_get_starting_time( $entry );

            return $now > $starting_time;
        } );

        usort( $upcoming_screenings, function ( $a, $b ) {
            return ggl_get_starting_time($a)->getTimestamp() - ggl_get_starting_time($b)->getTimestamp();
        });

        if ( ! empty( $upcoming_screenings ) ) {
            get_template_part( 'src/partials/movie-list', args: [
                    "posts" => $upcoming_screenings,
                    "title" => __( "Upcoming Screenings", "gegenlicht" )
            ] );
        }

        $pastScreeningEntries = [];
        foreach ( $pastScreenings as $past_screening ) {
            $starting_time                                           = ggl_get_starting_time( $past_screening );
            $pastScreeningEntries[ $starting_time->format( "Y" ) ][] = ggl_get_localized_title( $past_screening );
        }

        $manualEntries = ggl_get_teamie_manual_movie_entries();
        foreach ( $manualEntries as $entry ) {
            $pastScreeningEntries[ $entry[0] ][] = $entry[1];
        }

        krsort( $pastScreeningEntries, SORT_NUMERIC );

        if ( ! empty( $pastScreeningEntries ) ):
            ?>
            <div class="movie-list mt-4">
                <p class="movie-list-title">
                    <?= esc_html__( "Past Screenings", "gegenlicht" ) ?>
                </p>
                <div class="movie-list-entries">
                    <?php
                    foreach ( $pastScreeningEntries as $year => $screenings ) {
                        foreach ( $screenings as $screening ) {
                            ?>
                            <div class="entry">
                                <div>
                                    <p style="font-feature-settings: unset !important;">
                                        <?= $year ?>
                                    </p>
                                    <h2 class="is-size-5 no-separator is-uppercase">
                                        <?= $screening ?>
                                    </h2>
                                </div>
                            </div>
                            <?php
                        }
                    }
                    ?>
                </div>
            </div>
        <?php endif; ?>

    </div>
</main>
<?php
get_footer();
?>
