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
        get_template_part( "partials/button", args: [
                "href"    => rwmb_get_value( "cooperation-partner_website" ),
                "content" => __( "Open Website", "gegenlicht" ),
        ] )
        ?>
    </article>

    <div class="my-5">
        <?php
        $status          = rwmb_meta( 'status' );
        $currentSemester = get_theme_mod( "displayed_semester" );
        if ( $status == "active" ):

            $args = array(
                    "post_type"      => [ "movie", "event" ],
                    "posts_per_page" => 3,
                    'tax_query'      => array(
                            'relation' => "AND",
                            array(
                                    'taxonomy' => 'semester',
                                    'terms'    => $currentSemester,
                            ),
                    ),
                    "meta_query"     => [
                            "relation" => "AND",
                            [
                                    "key"   => "cooperation_partner_id",
                                    "value" => get_the_ID(),
                            ],
                            [
                                    "key"   => "selected_by",
                                    "value" => "coop",
                            ],
                            [
                                    "key"     => "screening_date",
                                    "value"   => time(),
                                    "compare" => ">",
                            ]
                    ],
                    "meta_key"       => "screening_date",
                    "orderby"        => "meta_value_num",
                    "order"          => "ASC"
            );

            $query = new WP_Query( $args );

            if ( $query->have_posts() ) :
                get_template_part( 'partials/movie-list', args: [
                        "posts" => $query->posts,
                        "title" => __( "Upcoming Screenings", "gegenlicht" )
                ] );
                ?>

            <?php endif; ?>
        <?php else: ?>

        <?php endif; ?>

        <?php
        $args = array(
                "post_type"      => [ "movie", "event" ],
                "posts_per_page" => - 1,
                "meta_query"     => [
                        "relation" => "AND",
                        [
                                "key"   => "cooperation_partner_id",
                                "value" => get_the_ID(),
                        ],
                        [
                                "key"   => "selected_by",
                                "value" => "coop",
                        ],
                        [
                                "key"     => "screening_date",
                                "value"   => time(),
                                "compare" => "<",
                        ]
                ],
                "meta_key"       => "screening_date",
                "orderby"        => "meta_value_num",
                "order"          => "ASC"
        );

        $query          = new WP_Query( $args );
        $pastScreenings = [];
        while ( $query->have_posts() ) : $query->the_post();
            $programType                                     = (string) rwmb_get_value( 'program_type' );
            $startDateTime                                   = (int) rwmb_get_value( 'screening_date' );
            $pastScreenings[ date( "Y", $startDateTime ) ][] = ggl_get_title( $query->post );
        endwhile;
        wp_reset_postdata();

        $manualEntries = rwmb_get_value( "cooperation-partner_shown_movies" ) ?: [];
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
                <?= apply_filters( "the_content", get_theme_mod( "missing_coop_entries" )[ get_locale() ] ?? "" ) ?>
            </div>
        <?php endif; ?>

    </div>
</main>
<?php
get_footer();
?>
