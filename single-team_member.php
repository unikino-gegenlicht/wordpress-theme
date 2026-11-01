<?php
/*
 * Copyright (c) 2026. Unikino GEGENLICHT. Licensed under the EUPL v1.2 or later
 */

use CinemaPress\post_types\Team_Member;

defined( 'ABSPATH' ) || exit;

get_header() ?>
<main class="content-container content mt-3">
    <div class="is-flex is-flex-wrap-wrap is-gap-3">
        <div style="flex-basis: 188px">
            <?php the_post_thumbnail() ?>
            <div class="tags are-medium mt-3 is-hidden-mobile">
                <?php if ( ! empty( rwmb_get_value( 'letterboxd_username' ) ) ): ?>
                    <a class="tag is-flex-grow-1 has-background-primary"
                       href="https://letterboxd.com/<?= trim( rwmb_get_value( 'letterboxd_username' ) ) ?>"
                       target="_blank">
                        <span class="icon-text">
                            <span class="icon">
                                <i class="fa-brands fa-letterboxd"></i>
                            </span>
                            <span>Letterboxd</span>
                        </span>
                    </a>
                <?php endif; ?>
                <?php if ( ! empty( rwmb_get_value( 'instagram_username' ) ) ): ?>
                    <a class="tag is-flex-grow-1 has-background-primary"
                       href="https://instagram.com/<?= trim( rwmb_get_value( 'instagram_username' ) ) ?>"
                       target="_blank">
                        <span class="icon-text">
                            <span class="icon">
                                <i class="fa-brands fa-instagram"></i>
                            </span>
                            <span>Instagram</span>
                        </span>
                    </a>
                <?php endif; ?>
                <?php if ( ! empty( rwmb_get_value( 'trakt_tv_username' ) ) ): ?>
                    <a class="tag is-flex-grow-1 has-background-primary"
                       href="https://app.trakt.tv/profile/<?= trim( rwmb_get_value( 'trakt_tv_username' ) ) ?>"
                       target="_blank">
                        <span class="icon-text" style="align-items: center !important;">
                            <span class="icon is-small" style="fill: var(--bulma-body-color)">
                                <?= file_get_contents( get_theme_file_path( 'static/img/social/trakt-tv.svg' ) ) ?>
                            </span>
                            <span>Trakt.tv</span>
                        </span>
                    </a>
                <?php endif; ?>
            </div>
        </div>
        <div class="is-flex-grow-1" style="flex-basis: 360px">
            <h1 class="has-border-bottom mb-1"><?= the_title() ?></h1>
            <?php if ( rwmb_meta( 'status' ) == 'active' ): ?>
                <p class="is-italic mb-2"><?= sprintf(/* translators: %s is the year the member joined */ esc_html__( 'Active since %1$s', 'cinema-press-theme' ), rwmb_meta( 'joined_in' ) ) ?></p>
            <?php endif; ?>
            <?php if ( rwmb_meta( 'status' ) == 'former' ): ?>
                <p class="is-italic mb-2"><?= sprintf(/* translators: %1$s is the year the member joined and %2$s is the year the member lef3 */ esc_html__( 'Active between %1$s and %2$s', 'cinema-press-theme' ), rwmb_meta( 'joined_in' ) ) ?></p>
            <?php endif; ?>
            <?php if ( rwmb_meta( 'status' ) == 'honorary' ): ?>
                <p class="is-italic mb-2"><?= sprintf(/* translators: %1$s is the year the member was made an honorary member */ esc_html__( 'Honorary member since %1$s', 'cinema-press-theme' ), rwmb_meta( 'honorary_since' ) ) ?></p>
            <?php endif; ?>
            <?php Team_Member::the_description(); ?>
            <?php ?>
        </div>
    </div>
    <div class="tags are-medium mt-3 is-hidden-tablet">
        <?php if ( ! empty( rwmb_get_value( 'letterboxd_username' ) ) ): ?>
            <a rel="me" class="tag is-flex-grow-1 has-background-primary"
               href="https://letterboxd.com/<?= trim( rwmb_get_value( 'letterboxd_username' ) ) ?>" target="_blank">
                        <span class="icon-text">
                            <span class="icon">
                                <i class="fa-brands fa-letterboxd"></i>
                            </span>
                            <span>Letterboxd</span>
                        </span>
            </a>
        <?php endif; ?>
        <?php if ( ! empty( rwmb_get_value( 'instagram_username' ) ) ): ?>
            <a rel="me" class="tag is-flex-grow-1 has-background-primary"
               href="https://instagram.com/<?= trim( rwmb_get_value( 'instagram_username' ) ) ?>" target="_blank">
                        <span class="icon-text">
                            <span class="icon">
                                <i class="fa-brands fa-instagram"></i>
                            </span>
                            <span>Instagram</span>
                        </span>
            </a>
        <?php endif; ?>
        <?php if ( ! empty( rwmb_get_value( 'trakt_tv_username' ) ) ): ?>
            <a rel="me" class="tag is-flex-grow-1 has-background-primary"
               href="https://app.trakt.tv/profile/<?= trim( rwmb_get_value( 'trakt_tv_username' ) ) ?>" target="_blank">
                        <span class="icon-text" style="align-items: center !important;">
                            <span class="icon is-small" style="fill: var(--bulma-body-color)">
                                <?= file_get_contents( get_theme_file_path( 'static/img/social/trakt-tv.svg' ) ) ?>
                            </span>
                            <span>Trakt.tv</span>
                        </span>
            </a>
        <?php endif; ?>
    </div>
</main>
<article class="content-container">
    <div class="movie-list is-flex-grow-2">
        <p class="movie-list-title mb-0"><?= sprintf(/* translators: %s is the name of the team member */esc_html__( 'Proposals by %s', 'cinema-press-theme' ), get_the_title()) ?></p>
        <div class="movie-list-entries"
             style="max-height: 100%; overflow-y: scroll; scrollbar-width: thin;">
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
                                    'value'   => [ $post->ID ],
                                    'compare' => 'IN'
                            )
                    ),
                    'meta_key'       => 'screening_start',
                    'orderby'        => 'meta_value',
                    'order'          => 'DESC',
            ) );
            foreach ( $query->posts as $post ): setup_postdata( $post ); ?>
                <div class="entry is-flex-direction-column is-align-items-flex-start">
                    <p class="m-0"><?= sprintf( esc_html__( /* translators: %s is the date the movie was screened on */ 'Screened on %s', 'cinema-press-theme' ), rwmb_the_value( 'screening_start', [ 'format' => CINEMA_PRESS__DATE_FORMAT ], $post->ID, false ) ) ?></p>
                    <h2 class="movie-title is-size-5 mt-0 has-text-weight-semibold"><?php rwmb_the_value( 'german_title', post_id: rwmb_meta( 'movie' ) ) ?></h2>
                </div>
            <?php endforeach;
            wp_reset_postdata(); ?>
        </div>
    </div>
</article>
<?php get_footer() ?>
