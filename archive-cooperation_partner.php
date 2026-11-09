<?php
/*
 * Copyright (c) 2026. Unikino GEGENLICHT. Licensed under the EUPL v1.2 or later
 */

defined( 'ABSPATH' ) || exit;
get_header();
?>
    <main class="content-container content mt-4">
        <header class="content">
            <h1><?= get_theme_mod( 'cooperation_page_title', post_type_archive_title( display: false ) ) ?></h1>
            <?= apply_filters( 'the_content', get_theme_mod( 'cooperation_page_text', "" ) ) ?>
        </header>
        <section class="rules" id="rules">
            <?php
            $rulesRaw = get_theme_mod( 'cooperation_rules' );
            $rules    = preg_split( "/\|/", $rulesRaw, flags: PREG_SPLIT_NO_EMPTY );
            ?>
            <ul>
                <?php for ( $i = 0; $i < count( $rules ); $i ++ ) : ?>
                    <li class="px-2"><?= esc_html( $rules[ $i ] ) ?></li>
                <?php endfor; ?>
            </ul>
            <?= apply_filters( 'the_content', get_theme_mod( 'cooperation_rules_closer', "" ) ) ?>
        </section>
        <a href="<?= esc_attr( get_theme_mod( 'cooperation_form_url' ) ) ?>"
           class="button is-outlined is-fullwidth my-4">
            <?= esc_html( get_theme_mod( 'cooperation_form_button_text', __( 'Get Started', 'cinema-press-theme' ) ) ) ?>
        </a>
        <section class="mt-5">
            <h3><?= esc_html__( 'Our Cooperation Parnters', 'cinema-press-theme' ) ?></h3>
            <?= apply_filters( 'the_content', get_theme_mod( 'cooperation_partner_text', "" ) ) ?>
            <hr>
            <div class="fixed-grid has-2-cols-mobile has-3-cols-tablet">
                <div class="grid" style="grid-template-rows: auto">
                    <?php while ( have_posts() ) : the_post(); ?>
                        <a class="cell is-align-self-flex-start is-flex is-flex-direction-column is-justify-content-space-between is-gap-0" href="<?= get_the_permalink() ?>">
                            <div class="is-flex-grow-1 is-flex is-align-items-center is-justify-content-center" style="height: 10rem">
                                <?php the_post_thumbnail(); ?>
                            </div>
                            <div>
                                <hr>
                                <h4 class="is-size-5 is-uppercase" style="word-break: break-word">
                                    <?php the_title() ?>
                                </h4>
                            </div>
                        </a>
                    <?php endwhile; ?>
                </div>
            </div>
        </section>
    </main>
<?php get_footer(); ?>