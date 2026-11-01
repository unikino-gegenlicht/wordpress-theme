<?php
/*
 * Copyright (c) 2026. Unikino GEGENLICHT. Licensed under the EUPL v1.2 or later
 */
get_header( 'not_found' );
?>
<main class="content-container content mt-3 is-flex is-justify-content-center is-align-items-center"
      style="min-height: 85vh;">
    <div class="has-text-centered has-background-white has-text-black py-5 px-3">
        <h1 class="has-text-centered has-background-white has-text-black py-3"><?= __( 'Page not found' ) ?></h1>
        <p><?= sprintf( /* translators: %s is the email of the page administrator */ esc_html__( "Sadly, we couldn't find the page you were trying to access. If you are sure, that a page should exist here, please contact the page administrator (%s)", 'cinema-press-theme' ), get_bloginfo( 'admin_email' ) ) ?></p>
        <p><?= esc_html__( "You now have the choice to either go back to the last page you opened", "cinema-press-theme" ) ?></p>
        <a href="javascript:history.back()" class="button is-fullwidth is-black">
            <?= esc_html__( "Go back", 'cinema-press-theme' ) ?>
        </a>
        <p class="mt-3 mb-1"><?= esc_html__("or go to the landing page which showcases our program", "cinema-press-theme") ?></p>
        <a href="<?= get_home_url() ?>" class="button is-fullwidth is-black">
            <?= esc_html__( "To the landing page", 'cinema-press-theme' ) ?>
        </a>
    </div>

</main>
<?php get_footer(); ?>
