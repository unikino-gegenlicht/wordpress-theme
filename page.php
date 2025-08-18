<?php
defined( 'ABSPATH' ) || exit;
get_header();
do_action( 'wp_body_open' );
?>
    <article class="page-content content">
		<?php if ( is_location_page() ) : ?>
            <figure class="image mb-3">
                <picture>
                    <source srcset="<?= wp_get_attachment_image_url( get_theme_mod( "location_map" )["dark"] ) ?? "" ?>"
                            media="(prefers-color-scheme: dark)">
                    <source srcset="<?= wp_get_attachment_image_url( get_theme_mod( "location_map" )["light"] ) ?? "" ?>">
                    <img src="<?= wp_get_attachment_image_url( get_theme_mod( "location_map" )["light"] ) ?? '' ?>"/>
                </picture>
            </figure>
		<?php endif; ?>
        <header>
            <h1><?= get_the_title() ?></h1>
        </header>
		<?= apply_filters( 'the_content', get_the_content() ) ?>
    </article>
<?php if ( is_location_page() ) : ?>
    <article class="mb-6"
            style="--bulma-body-color: var(--bulma-primary); --bulma-body-background-color: black; color: var(--bulma-body-color); background-color: var(--bulma-body-background-color);">
        <div class="page-content">
			<?php if ( get_theme_mod( "location_unikum_logo" ) ): ?>
                <figure class="image py-4">
                    <img style="max-height: 250px; object-fit: scale-down" src="<?= wp_get_attachment_image_url( get_theme_mod( "location_unikum_logo" ) ) ?? '' ?>"/>
                </figure>
			<?php endif; ?>
            <h2><?= get_theme_mod( "location_unikum_block_title" )[ get_locale() ] ?? "" ?></h2>
            <p class="pt-2"><?= get_theme_mod( "location_unikum_block_tagline" )[ get_locale() ] ?? "" ?></p>
            <hr class="separator"/>
            <div class="content pb-3">
				<?= apply_filters( "the_content", get_theme_mod( "location_unikum_block_content" )[ get_locale() ] ?? "" ) ?>
				<?php
				get_template_part( 'partials/button', args: [
					'href'     => get_theme_mod( "location_unikum_block_website_url" ),
					'content'  => __( 'To the Website', 'gegenlicht' ),
					'external' => true
				] ); ?>
            </div>
        </div>
    </article>
<?php endif; ?>
<?php
get_footer();
?>