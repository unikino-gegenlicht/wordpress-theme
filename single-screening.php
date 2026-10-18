<?php
/*
 * Copyright (c) 2026. Unikino GEGENLICHT. Licensed under the EUPL v1.2 or later
 */
get_header();
$movie = get_post( rwmb_meta( 'movie' ) );
?>
<main class="content-container">
    <hr>
    <header class="is-flex is-justify-content-space-between">
        <p><?= esc_html__( 'Screening starts', 'cinema-press-theme' ) ?></p>
        <p><?php rwmb_the_value( 'screening_start', [ 'format' => CINEMA_PRESS__DATETIME_FORMAT ] ) ?></p>
    </header>
    <hr>
    <div class="content">
        <div>
            <h1 class="mb-1 font-readex is-uppercase"><?= rwmb_meta( 'german_title', post_id: $movie->ID ) ?></h1>
            <p class="font-inter is-uppercase">
                OT:&ensp;<?= rwmb_meta( 'original_title', post_id: $movie->ID ) ?></p>
        </div>
        <hr>
        <p class="mb-0">
            <?= join( "/", array_map( function ( $country ) {
                return iso_codes()->getCountries()->getByNumericCode( $country )->getAlpha2();
            }, rwmb_meta( 'country_of_origin', post_id: $movie->ID ) ) ) ?>
            <?= rwmb_meta( 'release_year', post_id: $movie->ID ) ?>
        </p>
        <p class="mb-0">
            <?php
            $directors = rwmb_meta( 'directors', post_id: $movie->ID );
            if ( count( $directors ) === 1 ) {
                echo sprintf( __(/* translators: the name of the actor in %s */ "by %s", 'cinema-press-theme' ), $directors[0]->name );
            } else {
                $last_director = array_pop( $directors );
                $str1          = join( "/", array_map( function ( $director ) {
                    return $director->name;
                }, $directors ) );
                echo sprintf( __( /* translators: first element is a comma separated list, the second one is the last listed director */ "by %s and %s", 'cinema-press-theme' ), $str1, $last_director->name );
            }
            ?>
        </p>
        <p class="mb-0">
            <?php
            $actors = rwmb_meta( 'actors', post_id: $movie->ID );
            if ( count( $actors ) === 1 ) {
                echo sprintf( __(/* translators: the name of the actor in %s */ "with %s", 'cinema-press-theme' ), $actors[0]->name );
            } else {
                $last_actor = array_pop( $actors );
                $str1       = join( "/", array_map( function ( $actor ) {
                    return $actor->name;
                }, $actors ) );
                echo sprintf( __( /* translators: first element is a comma separated list, the second one is the last listed director */ "with %s and %s", 'cinema-press-theme' ), $str1, $last_actor->name );
            }

            ?>
        </p>
        <hr>
        <div class="tags are-medium is-flex-grow-1 mb-3">
            <span class="tag is-rounded is-primary"><?php rwmb_the_value( 'age_rating', post_id: $movie->ID ) ?></span>
            <span class="tag is-rounded is-primary"><?= esc_html__( 'Audio in', 'cinema-press-theme' ) ?>&nbsp;<?php rwmb_the_value( 'audio_language' ) ?></span>
            <?php if ( rwmb_meta( 'has_subtitles' ) ): ?>
                <span class="tag is-rounded is-primary"><?= esc_html__( 'Subtitles in', 'cinema-press-theme' ) ?>&nbsp;<?php rwmb_the_value( 'subtitle_language' ) ?></span>
            <?php else: ?>
                <span class="tag is-rounded is-danger"><?= esc_html__( 'No Subtitles', 'cinema-press-theme' ) ?></span>
            <?php endif; ?>
        </div>
        <?php the_post_thumbnail() ?>
        <hr>
        <?php do_action( 'cinema_press_theme_render_reservation_button' ) ?>
        <h2 class="mt-0 is-uppercase"> <?= esc_html__( 'What is the movie about?', 'cinema-press-theme' ) ?></h2>
        <?php
        global $wp_embed;
        $value = rwmb_meta( 'summary' );
        $value = do_shortcode( wpautop( $wp_embed->autoembed( $value ) ) );
        echo $value;
        ?>
        <h2 class="mt-0 is-uppercase"> <?= esc_html__( 'Why is the movie worth seeing?', 'cinema-press-theme' ) ?></h2>
        <?php
        global $wp_embed;
        $value = rwmb_meta( 'worth_to_be_seen' );
        $value = do_shortcode( wpautop( $wp_embed->autoembed( $value ) ) );
        echo $value;
        ?>
        <?php do_action( 'cinema_press_theme_render_reservation_button' ) ?>
</main>
<?php if ( ( get_option( 'cinema-press' )['enable_teamie_screening_assignment'] ?? 0 ) === 1 && ! empty( rwmb_meta( 'proposed_by' ) ) ): ?>
    <aside class="content-container content mt-5">
        <?php
        $proposers = rwmb_meta( 'proposed_by' );
        if ( count( $proposers ) === 1 ) {
            $proposed_by_str = sprintf(/* translators: %s is the name of the proposer */ __( "This movie was proposed by %s", 'cinema-press-theme' ), get_the_title( $proposers[0] ) );
        } else {
            $last_proposer   = array_pop( $proposers );
            $str1            = join( ", ", array_map( function ( $proposer ) {
                return get_the_title( $proposer );
            }, $proposers ) );
            $proposed_by_str = sprintf(/* translators: %s is the name of the proposer */ __( "This movie was proposed by %s and %s", 'cinema-press-theme' ), $str1, get_the_title( $last_proposer ) );
            $proposers[]     = $last_proposer;
        }
        ?>
        <h3><?= $proposed_by_str ?></h3>
        <?php if ( count( $proposers ) === 1 ): ?>
            <div class="is-flex is-align-items-top is-flex-wrap-nowrap is-gap-0.5">
                <picture class="image is-3by4 is-bordered" style="height: 250px">
                    <source srcset="<?= get_the_post_thumbnail_url( get_post( $proposers[0] ) ) ?>"/>
                    <img src="<?= get_theme_file_uri( 'static/img/user-solid-full.svg' ) ?>"/>
                </picture>
                <div class="movie-list is-flex-grow-2">
                    <p class="movie-list-title mb-0"><?= esc_html__( 'Other proposals', 'cinema-press-theme' ) ?></p>
                    <div class="movie-list-entries"
                         style="max-height: 212px; overflow-y: scroll; scrollbar-width: thin;">
                        <?php
                        $query = new WP_Query( array(
                                'post_type'      => 'screening',
                                'posts_per_page' => 5,
                                'meta_query'     => array(
                                        'relation' => 'AND',
                                        array(
                                                'key'     => 'screening_start',
                                                'value'   => date( 'Y-m-d H:i:s' ),
                                                'compare' => '<='
                                        ),
                                        array(
                                                'key'     => 'proposed_by',
                                                'value'   => [ $proposers[0] ],
                                                'compare' => 'IN'
                                        )
                                ),
                                'orderby'        => 'rand',
                        ) );
                        foreach ( $query->posts as $post ): setup_postdata( $post ); ?>
                            <div class="entry is-flex-direction-column is-align-items-flex-start">
                                <p class="m-0"><?= sprintf( esc_html__( /* translators: %s is the year the movie was screened in */'Screened in %s', 'cinema-press-theme' ), rwmb_the_value( 'screening_start', [ 'format' => 'Y' ], $post->ID, false ) ) ?></p>
                                <h2 class="movie-title is-size-5 mt-0"><?php rwmb_the_value( 'german_title', post_id: rwmb_meta( 'movie' ) ) ?></h2>
                            </div>
                        <?php endforeach;
                        wp_reset_postdata(); ?>
                    </div>
                </div>
            </div>
        <?php else: ?>
            <div class="is-flex is-align-items-top is-flex-wrap-wrap is-gap-0.5">
                <div class="is-flex is-flex-wrap-nowrap is-flex-grow-6 is-justify-content-space-around"
                     style="overflow-x: scroll; scrollbar-width: thin; width: 100%;">
                    <?php foreach ( $proposers as $proposer ): ?>
                        <picture class="image is-3by4 is-bordered" style="height: 250px">
                            <source srcset="<?= get_the_post_thumbnail_url( get_post( $proposer ) ) ?>"/>
                            <img src="<?= get_theme_file_uri( 'static/img/user-solid-full.svg' ) ?>"/>
                        </picture>
                    <?php endforeach; ?>
                </div>
                <div class="movie-list is-flex-grow-2">
                    <p class="movie-list-title mb-0"><?= esc_html__( 'Other proposals', 'cinema-press-theme' ) ?></p>
                    <div class="movie-list-entries"
                         style="max-height: 212px; overflow-y: scroll; scrollbar-width: thin;">
                        <?php
                        $query = new WP_Query( array(
                                'post_type'      => 'screening',
                                'posts_per_page' => 10,
                                'meta_query'     => array(
                                        'relation' => 'AND',
                                        array(
                                                'key'     => 'screening_start',
                                                'value'   => date( 'Y-m-d H:i:s' ),
                                                'compare' => '<='
                                        ),
                                        array(
                                                'key'     => 'proposed_by',
                                                'value'   => $proposers,
                                                'compare' => 'IN'
                                        )
                                ),
                                'orderby'        => 'rand',
                        ) );
                        foreach ( $query->posts as $post ): setup_postdata( $post ); ?>
                            <div class="entry is-flex-direction-column is-align-items-flex-start">
                                <p class="m-0"><?= sprintf( esc_html__( 'Screened in %s', 'cinema-press-theme' ), rwmb_the_value( 'screening_start', [ 'format' => 'Y' ], $post->ID, false ) ) ?></p>
                                <h2 class="movie-title is-size-5 mt-0"><?php rwmb_the_value( 'german_title', post_id: rwmb_meta( 'movie' ) ) ?></h2>
                            </div>
                        <?php endforeach;
                        wp_reset_postdata(); ?>
                    </div>
                </div>
            </div>
        <?php endif ?>
    </aside>
<?php endif ?>
<?php get_footer(); ?>
