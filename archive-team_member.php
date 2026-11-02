<?php
/*
 * Copyright (c) 2026. Unikino GEGENLICHT. Licensed under the EUPL v1.2 or later
 */

defined( 'ABSPATH' ) || exit;

$members = array();
while ( have_posts() ) :
    the_post();
    $status               = rwmb_meta( 'status' );
    $members[ $status ][] = $post;
endwhile;

get_header();
?>
    <main class="content-container content mt-3">
        <header class="mb-5">
            <h1><?= get_theme_mod( 'team_page_title', get_the_archive_title() ) ?></h1>
            <?= apply_filters( 'the_content', get_theme_mod( 'team_page_text' ) ) ?>
        </header>
        <?php if ( ! empty( $members['active'] ) ) : ?>
            <article id="aktiv">
                <h2 class="has-border-bottom"><?= esc_html__( 'Active Members', 'cinema-press-theme' ) ?></h2>
                <div class="grid is-col-min-7 teamie-grid">
                    <?php foreach ( $members['active'] as $post ) : setup_postdata( $post ); ?>
                        <a href="<?= get_permalink() ?>" class="cell pb-3">
                            <?php the_post_thumbnail(); ?>
                            <hr class="my-1">
                            <?php the_title( '<h3 class="my-0">', '</h3>' ) ?>
                            <p class="my-0"><?= sprintf( esc_html__( 'active since %1$s', 'cinema-press-theme' ), rwmb_meta( 'joined_in' ) ) ?></p>
                            <hr class="my-1">
                        </a>
                    <?php endforeach;
                    wp_reset_postdata(); ?>
                </div>
            </article>
        <?php endif; ?>
        <?php if ( ! empty( $members['former'] ) ) : ?>
            <article id="ehemalig">
                <h2 class="has-border-bottom"><?= esc_html( get_theme_mod( "team_page_former_members_heading" ) ) ?></h2>
                <?= apply_filters( 'the_content', get_theme_mod( 'team_page_former_members_text' ) ) ?>

                <div class="grid is-col-min-7 teamie-grid">
                    <?php foreach ( $members['former'] as $post ) : setup_postdata( $post ); ?>
                        <a href="<?= get_permalink() ?>" class="cell pb-3">
                            <?php the_post_thumbnail(); ?>
                            <hr class="my-1">
                            <?php the_title( '<h3 class="my-0">', '</h3>' ) ?>
                            <p class="my-0"><?= sprintf( esc_html__( 'active from %1$s until %2$s', 'cinema-press-theme' ), rwmb_meta( 'joined_in' ), rwmb_meta( 'left_in' ) ) ?></p>
                            <hr class="my-1">
                        </a>
                    <?php endforeach;
                    wp_reset_postdata(); ?>
                </div>
            </article>
        <?php endif; ?>
        <?php if ( ! empty( $members['honorary'] ) ) : ?>
            <article id="ehrenmitglied" class="content">
                <h2 class="has-border-bottom"><?= esc_html( get_theme_mod( "team_page_honorary_members_heading" ) ) ?></h2>
                <?= apply_filters( 'the_content', get_theme_mod( 'team_page_honorary_members_text' ) ) ?>

                <div class="grid is-col-min-9 teamie-grid">
                    <?php foreach ( $members['honorary'] as $post ) : setup_postdata( $post ); ?>
                        <a href="<?= get_permalink() ?>" class="cell pb-3">
                            <?php the_post_thumbnail( attr: [ "style" => "width: 250px;" ] ); ?>
                            <hr class="my-1">
                            <?php the_title( '<h3 class="my-0">', '</h3>' ) ?>
                            <p class="my-0"><?= sprintf( esc_html__( 'active from %1$s until %2$s', 'cinema-press-theme' ), rwmb_meta( 'joined_in' ), rwmb_meta( 'left_in' ) ) ?></p>
                            <p class="my-0"><?= sprintf( esc_html__( 'honorary member since %1$s', 'cinema-press-theme' ), rwmb_meta( 'honorary_since' ) ) ?></p>
                            <hr class="my-1">
                        </a>
                    <?php endforeach;
                    wp_reset_postdata(); ?>
                </div>
            </article>
        <?php endif; ?>
    </main>
<?php get_footer(); ?>