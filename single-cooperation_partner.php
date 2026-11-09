<?php
/*
 * Copyright (c) 2026. Unikino GEGENLICHT. Licensed under the EUPL v1.2 or later
 */

defined( 'ABSPATH' ) || exit;
get_header();
?>
    <main class="content-container content mt-4">
        <header>
            <?php the_post_thumbnail() ?>
            <h1 class="has-border-bottom"><?php the_title() ?></h1>
            <div class="is-hidden-tablet mt-0 mb-3">
                <div class="buttons is-justify-content-space-between">
                    <?php if ( ! empty( rwmb_get_value( 'website' ) ) ): ?>
                        <a rel="me" class="button is-flex-grow-1 is-outlined"
                           href="<?= trim( rwmb_get_value( 'website' ) ) ?>" target="_blank">
                        <span class="icon-text">
                            <span class="icon mr-0">
                                <i class="fa-regular fa-globe-www"></i>
                            </span>
                            <span>Website</span>
                        </span>
                        </a>
                    <?php endif; ?>
                    <?php if ( ! empty( rwmb_get_value( 'instagram_username' ) ) ): ?>
                        <a rel="me" class="button is-flex-grow-1 is-outlined"
                           href="https://instagram.com/<?= trim( rwmb_get_value( 'instagram_username' ) ) ?>"
                           target="_blank">
                        <span class="icon-text">
                            <span class="icon mr-0">
                                <i class="fa-brands fa-instagram"></i>
                            </span>
                            <span><?= trim( rwmb_get_value( 'instagram_username' ) ) ?></span>
                        </span>
                        </a>
                    <?php endif; ?>
                    <?php if ( ! empty( rwmb_get_value( 'mastodon_profile_url' ) ) ): ?>
                        <a rel="me" class="button is-flex-grow-1 is-outlined"
                           href="//<?= trim( rwmb_get_value( 'mastodon_profile_url' ) ) ?>" target="_blank">
                        <span class="icon-text">
                            <span class="icon mr-0">
                                <i class="fa-brands fa-mastodon"></i>
                            </span>
                            <span>
                                <?php
                                $mastodon_host     = parse_url( "https://" . rwmb_get_value( 'mastodon_profile_url' ), PHP_URL_HOST );
                                $mastodon_username = trim( parse_url( "https://" . rwmb_get_value( 'mastodon_profile_url' ), PHP_URL_PATH ), "@/" );

                                echo "@$mastodon_username@$mastodon_host";
                                ?>
                            </span>
                        </span>
                        </a>
                    <?php endif; ?>
                    <?php if ( ! empty( rwmb_get_value( 'bluesky_username' ) ) ): ?>
                        <a rel="me" class="button is-flex-grow-1 is-outlined"
                           href="https://bsky.app/profile/<?= trim( rwmb_get_value( 'bluesky_username' ) ) ?>"
                           target="_blank">
                        <span class="icon-text">
                            <span class="icon mr-0">
                                <i class="fa-brands fa-bluesky"></i>
                            </span>
                            <span><?= trim( rwmb_get_value( 'bluesky_username' ) ) ?></span>
                        </span>
                        </a>
                    <?php endif; ?>
                </div>
            </div>

        </header>
        <?php if ( ! empty( rwmb_get_value( 'website' ) ) || ! empty( rwmb_get_value( 'instagram_username' ) ) || ! empty( rwmb_get_value( 'mastodon_profile_url' ) ) || ! empty( rwmb_get_value( 'bluesky_username' ) ) ): ?>
            <div class="is-hidden-mobile mt-0 mb-3 ml-5" style="width: 20% !important; float: right !important;">
                <h2 class="mb-1"><?= __( 'Links', 'cinema-press-theme' ) ?></h2>
                <div class="buttons is-justify-content-space-between">
                    <?php if ( ! empty( rwmb_get_value( 'website' ) ) ): ?>
                        <a rel="me" class="button is-flex-grow-1 is-outlined"
                           href="<?= trim( rwmb_get_value( 'website' ) ) ?>" target="_blank">
                        <span class="icon-text">
                            <span class="icon">
                                <i class="fa-regular fa-globe-www"></i>
                            </span>
                            <span>Website</span>
                        </span>
                        </a>
                    <?php endif; ?>
                    <?php if ( ! empty( rwmb_get_value( 'instagram_username' ) ) ): ?>
                        <a rel="me" class="button is-outlined"
                           href="https://instagram.com/<?= trim( rwmb_get_value( 'instagram_username' ) ) ?>"
                           target="_blank">
                        <span class="icon">
                                <i class="fa-brands fa-instagram"></i>
                            </span>
                        </a>
                    <?php endif; ?>
                    <?php if ( ! empty( rwmb_get_value( 'mastodon_profile_url' ) ) ): ?>
                        <a rel="me" class="button is-outlined"
                           href="//<?= trim( rwmb_get_value( 'mastodon_profile_url' ) ) ?>" target="_blank">
                        <span class="icon-text">
                            <span class="icon">
                                <i class="fa-brands fa-mastodon"></i>
                            </span>
                        </span>
                        </a>
                    <?php endif; ?>
                    <?php if ( ! empty( rwmb_get_value( 'bluesky_username' ) ) ): ?>
                        <a rel="me" class="button is-outlined"
                           href="https://bsky.app/profile/<?= trim( rwmb_get_value( 'instagram_username' ) ) ?>"
                           target="_blank">
                        <span class="icon">
                            <i class="fa-brands fa-bluesky"></i>
                        </span>
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        <?php endif; ?>

        <?= apply_filters( 'the_content', get_the_content() ) ?>

    </main>
<?php
$query = new WP_Query( array(
        'post_type'      => 'screening',
        'posts_per_page' => - 1,
        'meta_query'     => array(
                'relation' => 'AND',
                array(
                        'key'     => 'screening_start',
                        'value'   => date( 'Y-m-d H:i:s' ),
                        'compare' => '<='
                ),
                array(
                        'key'     => 'proposed_by',
                        'value'   => [ get_the_ID() ],
                        'compare' => 'IN'
                )
        ),
        'meta_key'       => 'screening_start',
        'orderby'        => 'meta_value',
        'order'          => 'DESC',
) ); ?>
<?php if ( ! empty( $query->posts ) ): ?>
    <article class="content-container content mb-3">
        <?php get_template_part( 'templates/screening-list', args: [
                "title"             => esc_html__( 'Past Cooperations', 'cinema-press-theme' ),
                "screenings"        => $query->posts,
                "future_screenings" => false,
        ] ); ?>

    </article>
<?php endif; ?>
<?php get_footer(); ?>