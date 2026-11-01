<?php
/*
 * Copyright (c) 2026. Unikino GEGENLICHT. Licensed under the EUPL v1.2 or later
 */
defined( 'ABSPATH' ) || exit;

$semesters = get_terms( array(
        'taxonomy'   => 'semester',
        'hide_empty' => false,
) );
get_header();
?>
<main class="content-container content mt-3">
    <header>
        <h1><?php post_type_archive_title( __( 'Past', 'cinema-press-theme' ) . "&nbsp;" ) ?></h1>
    </header>
    <?= apply_filters( "the_content", get_theme_mod( 'archive_text' ) ) ?>
    <hr>
    <?php

    if ( have_posts() ) :
        $semester_screenings = array();
        while ( have_posts() ) : the_post();
            $semester = rwmb_meta( 'semester' );
            if ( $semester ):
                if ( empty( $semester_screenings[ $semester->slug ]["term"] ) ):
                    $semester_screenings[ $semester->slug ]["term"] = $semester;
                endif;
                $semester_screenings[ $semester->slug ]["posts"][] = $post;
            endif;
        endwhile;


        usort( $semester_screenings, function ( $a, $b ) {
            $a_start = rwmb_get_value( 'semester_start', [ 'object_type' => 'term' ], $a['term']->term_id );
            $b_start = rwmb_get_value( 'semester_start', [ 'object_type' => 'term' ], $b['term']->term_id );
            return $b_start - $a_start;
        } );

        foreach ( $semester_screenings as $semester ):
            $posts = $semester['posts'];
            usort( $posts, function ( $a, $b ) {
                $a_date = date_create(rwmb_meta('screening_start', post_id: $a->ID));
                $b_date = date_create(rwmb_meta('screening_start', post_id: $b->ID));

                return $a_date->getTimestamp() - $b_date->getTimestamp();
            });
            ?>
            <div class="movie-list mb-5">
                <p class="movie-list-title mb-0"><?= $semester['term']->name ?></p>
                <div class="movie-list-entries"
                     style="max-height: 212px; overflow-y: scroll; scrollbar-width: thin;">
                    <?php foreach ( $posts as $post ): setup_postdata( $post ); ?>
                        <div class="entry is-flex-direction-column is-align-items-flex-start">
                            <p class="m-0"><?php rwmb_the_value( 'screening_start', [ 'format' => CINEMA_PRESS__DATETIME_FORMAT ], $post->ID ) ?></p>
                            <h2 class="movie-title is-size-5 mt-0"><?php rwmb_the_value( 'german_title', post_id: rwmb_meta( 'movie' ) ) ?></h2>
                        </div>
                    <?php endforeach;
                    wp_reset_postdata(); ?>
                </div>
            </div>

        <?php endforeach;


    else:
        ?>
        <p><?= esc_html__( 'For now the archive remains empty, as there have been no recorded screenings. Check back later', 'cinema-press-theme' ) ?></p>
    <?php
    endif;
    ?>
</main>
