<?php

/**
 * The footer of all pages
 */
defined( 'ABSPATH' ) || exit;
?>

<footer class="footer has-text-primary">
    <p class="is-size-3 font-ggl is-uppercase has-text-primary">
		<?= str_replace( ' ', '<br class="is-hidden-tablet"/> ', get_bloginfo( 'name' ) ) ?>
    </p>
    <hr class="separator is-primary"/>
	<?php if ( get_theme_mod( 'instagram_username' ) || get_theme_mod( 'mastodon_url' ) || get_theme_mod( 'letterboxd_username' ) ): ?>
        <div class="is-flex is-align-items-center my-2 links are-primary is-flex-wrap-wrap">
			<?php if ( get_theme_mod( 'instagram_username' ) ): ?>
                <a href="https://instagram.com/<?= get_theme_mod( 'instagram_username' ) ?>"><span
                            class="icon-text"><span class="is-underlined">Instagram</span><span
                                class="material-symbols ml-1">open_in_new</span></span> </a>
			<?php endif; ?>
			<?php if ( get_theme_mod( 'mastodon_url' ) ): ?>
                <a href="<?= get_theme_mod( 'mastodon_url' ) ?>"><span class="icon-text"><span
                                class="is-underlined">Mastodon</span><span
                                class="material-symbols ml-1">open_in_new</span></span> </a>
			<?php endif; ?>
			<?php if ( get_theme_mod( 'letterboxd_username' ) ): ?>
                <a href="https://letterboxd.com/<?= get_theme_mod( 'letterboxd_username' ) ?>"><span
                            class="icon-text"><span class="is-underlined">Letterboxd</span><span
                                class="material-symbols ml-1">open_in_new</span></span> </a>
			<?php endif; ?>
        </div>
        <hr class="separator is-primary"/>
	<?php endif; ?>
    <address class="has-text-primary my-2">
		<?= nl2br( get_theme_mod( 'address' ) ) ?>
    </address>
    <hr class="separator is-primary"/>
    <div class="has-text-primary my-2">
        <h5 class="has-text-weight-semibold"><?= esc_html__( 'Youth Protection Officer' ) ?></h5>
        <a class="has-text-primary"
           href="mailto:<?= get_theme_mod( 'ypo_email' ) ?>"><?= get_theme_mod( 'ypo_name' ) ?></a>
        <p><?= str_replace( "@", " (at) ", get_theme_mod( 'ypo_email' ) ) ?></p>
    </div>
    <div class="is-flex is-align-items-center my-2 has-text-primary links are-primary is-flex-wrap-wrap">
        <a class="" href=""><?= esc_html__( 'Impress' ) ?></a>
        <a class="" href="<?= get_privacy_policy_url() ?>"
           rel="privacy-policy"><?= esc_html__( 'Privacy Policy' ) ?></a>
        <a class="" href=""><?= esc_html__( 'Contact' ) ?></a>
    </div>
    <div class="mt-4">
        <img alt="made by humans. not by ai" src="<?= get_stylesheet_directory_uri() ?>/assets/img/no-ai.svg"/>
    </div>
</footer>
<?php wp_footer(); ?>
</body>
</html>