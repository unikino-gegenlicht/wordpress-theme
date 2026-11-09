<?php
/*
 * Copyright (c) 2026. Unikino GEGENLICHT. Licensed under the EUPL v1.2 or later
 */

defined( 'ABSPATH' ) || exit;
get_header(); ?>
    <main class="content content-container mt-3">
        <h1><?= post_type_archive_title( __( 'Our', 'cinema-press-theme' ) . "&nbsp;" ) ?></h1>
        <div class="fixed-grid">
            <div class="grid" style="grid-template-rows: auto">
                <?php while ( have_posts() ) : the_post(); ?>
                    <a href="<?php the_permalink() ?>"
                       class="cell is-align-self-flex-start is-flex is-flex-direction-column is-justify-content-space-between is-gap-0"
                       id="<?= $post->post_name ?>">
                        <div class="is-flex-grow-1 is-flex is-align-items-center is-justify-content-center"
                             style="height: 10rem">
                            <?php the_post_thumbnail(); ?>
                        </div>
                        <div>
                            <hr>
                            <h2 class="is-size-4 mt-0"><?php the_title() ?></h2>
                        </div>
                    </a>
                <?php endwhile; ?>
            </div>
        </div>

    </main>
<?php get_footer(); ?>