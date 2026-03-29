<?php
defined( 'ABSPATH' ) || exit;
do_action( 'wp_body_open' );

if ( is_location_page() ) {
    get_header();
    get_template_part( 'src/templates/page', 'location' );
    get_footer();

    return;
}

if ( is_impress_page() ) {
    get_header( args: [ "hideBreakBanner" => true ] );
    get_template_part( 'src/templates/page', 'impress' );
    get_footer();

    return;
}

if ( is_contact_page() ) {
    get_header();
    get_template_part( 'src/templates/page', 'contact' );
    get_footer();

    return;
}

get_header();
?>
    <article class="page-content content mt-4">
        <header>
            <?php if (has_post_thumbnail()): ?>
                <picture>
                    <source srcset="<?= wp_get_attachment_image_srcset(get_post_thumbnail_id(), 'mobile') ?>" media="(width <= 768px)"/>
                    <source srcset="<?= wp_get_attachment_image_srcset(get_post_thumbnail_id(), 'desktop') ?>" media="(width > 768px)"/>
                    <img alt="" src="<?= get_the_post_thumbnail_url(size: 'desktop') ?>"
                </picture>
            <?php endif; ?>
            <h1 class="mt-2"><?= get_the_title() ?></h1>
        </header>
        <?= apply_filters( 'the_content', get_the_content() ) ?>
    </article>
<?php
get_footer();
?>