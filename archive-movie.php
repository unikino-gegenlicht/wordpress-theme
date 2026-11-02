<?php
/*
 * Copyright (c) 2026. Unikino GEGENLICHT. Licensed under the EUPL v1.2 or later
 */
defined( 'ABSPATH' ) || exit;

$movies = array();

while ( have_posts() ) : the_post();
    $title            = str_starts_with( get_user_locale(), "de" ) ? rwmb_meta( 'german_title' ) : rwmb_meta( 'english_title' );
    $movies[ $title ] = $post;
endwhile;

$collator = collator_create( get_user_locale() );
uksort( $movies, [ $collator, 'compare' ] );
get_header();
?>
<main class="content content-container mt-3">
    <h1 class="title"><?php post_type_archive_title() ?></h1>
    <p><?= esc_html__( 'The following movies have been in the program at least once in our cinema. We are always trying to broaden our screening palette so stay tuned for new admissions to this list', 'cinema-press-theme' ) ?></p>
    <hr>
    <div class="fixed-grid has-3-cols">
        <div class="grid">
            <?php foreach ( $movies as $title => $post ) : setup_postdata( $post ); ?>
                <a href="<?= get_post_permalink() ?>" class="cell is-align-content-end">
                    <img src="<?php the_post_thumbnail_url( 'original' ) ?>" height="95"/>
                    <h2 class="is-size-4 mt-1"><?= $title ?></h2>
                </a>
            <?php endforeach; ?>
        </div>
    </div>
</main>
<?php get_footer() ?>
