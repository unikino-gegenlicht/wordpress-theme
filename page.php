<?php
defined( 'ABSPATH' ) || exit;
get_header();
do_action( 'wp_body_open' );
?>
    <article class="page-content content">
        <?php if (is_location_page()) : ?>
            <figure class="image mb-3">
                <picture>
                    <source srcset="<?= wp_get_attachment_image_url(get_theme_mod("p_location_map")["dark"])  ?? ""?>" media="(prefers-color-scheme: dark)">
                    <source srcset="<?= wp_get_attachment_image_url(get_theme_mod("p_location_map")["light"])  ?? ""?>">
                    <img src="<?= wp_get_attachment_image_url(get_theme_mod("p_location_map")["light"]) ?? '' ?>"/>
                </picture>
            </figure>
        <?php endif; ?>
        <header>
            <h1><?= get_the_title() ?></h1>
        </header>
		<?= apply_filters( 'the_content', get_the_content() ) ?>
    </article>
<?php
get_footer();
?>