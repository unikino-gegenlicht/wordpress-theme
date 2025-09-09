<?php
defined( 'ABSPATH' ) || exit;

get_header();
?>
<main class="page-content mt-4">
    <article class="content">
        <div class="is-flex is-dynamic-flex is-align-items-top is-justify-content-space-evenly is-flex-wrap-wrap is-gap-1 mb-5 content">
            <figure class="image member-picture is-flex is-flex-grow-1">
                <img alt=""
                     src="<?= get_the_post_thumbnail_url( size: 'member-crop' ) ?: wp_get_attachment_image_url( get_theme_mod( 'anonymous_team_image' ), 'member-crop' ) ?>"/>
            </figure>
            <header class="is-flex-grow-3 main-content word-break-break-word">
                <h1><?php the_title() ?></h1>
                <?php
                if ( rwmb_get_value( "status" ) == "active" ):
                    $templateText = get_theme_mod( "active_text" )[ get_locale() ] ?? "";

                    $outputText = str_replace( "%%name%%", get_the_title(), $templateText );
                    $outputText = str_replace( "%%joinedIn%%", rwmb_get_value( "joined_in" ), $outputText );
                else:
                    $templateText = get_theme_mod( "former_text" )[ get_locale() ] ?? "";
                    $outputText   = str_replace( "%%name%%", get_the_title(), $templateText );
                    $outputText   = str_replace( "%%joinedIn%%", rwmb_get_value( "joined_in" ), $outputText );
                    $outputText   = str_replace( "%%leftIn%%", rwmb_get_value( "left_in" ), $outputText );
                endif;

                echo apply_filters( 'the_content', $outputText );
                ?>
            </header>
        </div>
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
                                    "key"   => "team_member_id",
                                    "value" => get_the_ID(),
                            ],
                            [
                                    "key"   => "selected_by",
                                    "value" => "member",
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
                                "key"   => "team_member_id",
                                "value" => get_the_ID(),
                        ],
                        [
                                "key"   => "selected_by",
                                "value" => "member",
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
        if ( $query->have_posts() ) :
            while ( $query->have_posts() ) : $query->the_post();
                $programType                                     = (string) rwmb_get_value( 'program_type' );
                $startDateTime                                   = (int) rwmb_get_value( 'screening_date' );
                $pastScreenings[ date( "Y", $startDateTime ) ][] = ggl_get_title( $query->post );
            endwhile;
            wp_reset_postdata();
        endif;

        $manualEntries = rwmb_get_value( "team-member_shown_movies" );
        foreach ( $manualEntries as $entry ) {
            $pastScreenings[ $entry[0] ][] = $entry[1];
        }
        krsort( $pastScreenings, SORT_NUMERIC );

        if ( ! empty( $pastScreenings ) ):
            ?>
            <div class="movie-list mt-4">
                <p class="movie-list-title">
                    <?= esc_html__( "Past Screenings", "gegenlicht" ) ?>
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
        <?php endif; ?>

    </div>
</main>
<?php
get_footer();
?>
