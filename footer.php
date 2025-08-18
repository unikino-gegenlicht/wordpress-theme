<?php
defined( 'ABSPATH' ) || exit;
?>

<footer class="footer has-text-primary">
    <p class="is-size-3 font-ggl is-uppercase has-text-primary">
		<?= str_replace( ' ', '<br class="is-hidden-tablet"/> ', get_bloginfo( 'name' ) ) ?>
    </p>
    <hr class="separator is-primary"/>
	<?php if ( ! empty( get_theme_mod( 'displayed_social_medias' ) ?? [] ) ): ?>
        <div class="is-flex is-align-items-center my-3 are-primary is-flex-wrap-wrap links">
			<?php foreach ( get_theme_mod( 'displayed_social_medias' ) as $socialMedia => $entry ): ?>
                <a rel="me" href="<?= get_theme_mod( "social_medias" )[ $entry ] ?>"><span
                            class="icon-text"><span class="is-underlined"><?= ucfirst( $entry ) ?></span><span
                                class="material-symbols ml-1">open_in_new</span></span> </a>
			<?php endforeach; ?>
        </div>
        <hr class="separator is-primary"/>
	<?php endif; ?>
    <div class="has-text-primary my-5 is-flex is-align-items-top is-flex-wrap-wrap is-justify-content-space-evenly is-row-gap-2.5">
        <?php if (in_array("postal", get_theme_mod("displayed_addresses") ?? [])): ?>
        <div class="is-flex-grow-1">
            <h5 class="has-text-weight-semibold no-separator is-size-6 is-underlined"><?= esc_html__( 'Postal Address', 'gegenlicht' ) ?></h5>
            <address class="has-text-primary" style="user-select: text">
				<?= nl2br( get_theme_mod( 'address' )["postal"] ) ?>
            </address>
        </div>
        <?php endif; ?>
	    <?php if (in_array("visitor", get_theme_mod("displayed_addresses") ?? [])): ?>
        <div class="is-flex-grow-1">
            <h5 class="has-text-weight-semibold no-separator is-size-6 is-underlined"><?= esc_html__( 'Visitor Address', 'gegenlicht' ) ?></h5>
            <address class="has-text-primary" style="user-select: text">
				<?= nl2br( get_theme_mod( 'address' )["postal"] ) ?>
            </address>
        </div>
	    <?php endif; ?>
    </div>
    <hr class="separator is-primary"/>
    <div class="has-text-primary my-5">
        <h5 class="is-size-6 has-text-weight-semibold no-separator"><?= esc_html__( 'Youth Protection Officer', 'gegenlicht' ) ?></h5>
        <p><?= get_theme_mod( 'youth_protection_officer' )['name'] ?? 'MISSING' ?></p>
        <p><?= str_replace( "@", " (at) ", ( get_theme_mod( 'youth_protection_officer' )['email'] ?? "missing@missing" ) ) ?></p>
    </div>
    <div class="is-flex is-align-items-center mb-2 mt-5 has-text-primary links are-primary is-flex-wrap-wrap">
        <a class="" role="link" href=""><?= esc_html__( 'Impress', 'gegenlicht' ) ?></a>
        <a class="" role="link" href="<?= get_privacy_policy_url() ?>"
           rel="privacy-policy"><?= esc_html__( 'Privacy Policy', 'gegenlicht' ) ?></a>
        <a class="" role="link" href=""><?= esc_html__( 'Contact', 'gegenlicht' ) ?></a>
    </div>
    <div class="mt-4">
        <img loading="lazy" width="150" height="39" style="object-fit: scale-down" alt="made by humans. not by ai"
             src="<?= get_stylesheet_directory_uri() ?>/assets/img/no-ai.svg"/>
    </div>
</footer>
<?php wp_footer(); ?>
</body>
</html>