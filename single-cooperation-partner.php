<?php
defined( 'ABSPATH' ) || exit;

get_header();
?>
<main class="page-content mt-4">
    <article class="content">
        <div class="is-flex is-dynamic-flex is-align-items-top is-justify-content-space-evenly is-flex-wrap-wrap is-gap-1 mb-5">
            <figure class="image coop-logo is-flex is-justify-content-center is-align-items-center is-flex-grow-1   ">
                <img height="250" src="<?= get_the_post_thumbnail_url( size: 'full' ) ?>" style="height: 250px;"/>
            </figure>
            <header class="is-flex-grow-3 main-content word-break-break-word">
                <h1><?php the_title() ?></h1>
                <?php the_content(); ?>
            </header>
        </div>
        <?php
        get_template_part( "src/partials/button", args: [
                "href"    => rwmb_get_value( "cooperation-partner_website" ),
                "content" => __( "Open Website", "gegenlicht" ),
                'additionalClasses' => "plausible-event-name=Open+Cooperation+Partner+Website"
        ] )
        ?>
    </article>

    <div class="my-5">
        <?php
        $movies = ggl_get_partner_movies();
        $upcoming_movies = array_filter($movies, function($movie) {
            $now = new DateTime();
            return $now < ggl_get_starting_time($movie);
        });
        $past_movies = array_filter($movies, function($movie) {
            $now = new DateTime();
            return $now > ggl_get_starting_time($movie);
        });

        if (!empty($upcoming_movies)) {
            get_template_part( 'src/partials/movie-list', args: [
                    "posts" => $upcoming_movies,
                    "title" => __( "Upcoming Screenings", "gegenlicht" )
            ] );
        }

        $pastScreenings = array();

        foreach ($past_movies as $movie) {
            $pastScreenings[ggl_get_starting_time($movie)->format("Y")][] = ggl_get_localized_title($movie);
        }

        $manualEntries = ggl_get_partner_manual_movie_entries();
        foreach ( $manualEntries as $entry ) {
            $pastScreenings[ $entry[0] ][] = $entry[1];
        }



        krsort( $pastScreenings, SORT_NUMERIC );
        if ( ! empty( $pastScreenings ) ):
            ?>
            <div class="movie-list mt-4">
                <p class="movie-list-title">
                    <?= esc_html__( "Past Cooperations", "gegenlicht" ) ?>
                </p>
                <div class="movie-list-entries">
                    <?php
                    foreach ( $pastScreenings as $year => $screenings ) {
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
        <?php
        else:
            ?>
            <div class="content">
                <?= apply_filters( "the_content", get_theme_mod( "missing_coop_entries" )[ substr(get_user_locale(), 0, 2)  ] ?? "" ) ?>
            </div>
        <?php endif; ?>

    </div>
</main>
<?php
get_footer();
?>
