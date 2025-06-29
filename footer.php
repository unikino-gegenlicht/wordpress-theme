<?php

/**
 * The footer of all pages
 */
defined('ABSPATH') || exit;
?>

<footer class="footer has-text-primary">
    <p class="is-size-3 font-ggl is-uppercase has-text-primary">
	    <?= str_replace( ' ', '<br class="is-hidden-tablet"/> ', get_bloginfo( 'name' ) ) ?>
    </p>
    <hr class="separator is-primary"/>
    <?php if (get_theme_mod('instagram_username') || get_theme_mod('mastodon_url') || get_theme_mod('letterboxd_username')): ?>
    <div class="is-flex is-align-items-center">
        <?php if (get_theme_mod('instagram_username')): ?>
        <a href="https://instagram.com/<?= get_theme_mod('instagram_username') ?>" class="has-text-primary"><span class="is-underlined">Instagram</span><span class="material-symbols">open_in_new</span> </a>
        <?php endif; ?>
	    <?php if (get_theme_mod('mastodon_url')): ?>
            <a rel="me" href="<?= get_theme_mod('mastodon_url') ?>" class="has-text-primary"><span class="is-underlined">Mastodon</span><span class="material-symbols">open_in_new</span> </a>
	    <?php endif; ?>
	    <?php if (get_theme_mod('letterboxd_username')): ?>
            <a rel="me" href="https://letterboxd.com/<?= get_theme_mod('letterboxd_username') ?>" class="has-text-primary"><span class="is-underlined">Letterboxd</span><span class="material-symbols">open_in_new</span> </a>
	    <?php endif; ?>
    </div>
    <hr class="separator is-primary"/>
    <?php endif; ?>
    <address class="has-text-primary my-2">
        <?= get_theme_mod('address_line_1') ?>
        <?= get_theme_mod('address_line_2') ?>
        <?= get_theme_mod('address_street') ?>
        <?= get_theme_mod('address_zip_code') ?> <?= get_theme_mod('address_city') ?>
    </address>
    <hr class="separator is-primary"/>
    <div class="has-text-primary my-2">
        <h5 class="has-text-weight-semibold"><?= esc_html__('Youth Protection Officer') ?></h5>
        <a href="mailto:<?= get_theme_mod('ypo_email') ?>"><?= get_theme_mod('ypo_name') ?></a>
        <p><?= str_replace("@", " (at) ", get_theme_mod('ypo_email')) ?></p>
    </div>
    <div class="is-flex is-align-items-center my-2 has-text-primary footer-links is-flex-wrap-wrap">
        <a class="has-text-primary" href=""><?= esc_html__('Impress') ?></a>
        <a href=""><?= esc_html__('Privacy') ?></a>
        <a href=""><?= esc_html__('Contact') ?></a>
    </div>
</footer>
<?php wp_footer(); ?>
</body>
</html>