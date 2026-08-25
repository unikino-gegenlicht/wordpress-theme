<?php
defined( 'ABSPATH' ) || exit;
do_action( 'wp_body_open' );

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
            <div class="has-text-right my-3">
                <p class="m-0"><?= esc_html__('Published on') ?>:&nbsp;<?= get_post_datetime(field: 'date')->format(GGL_ENGLISH_DATETIME_FORMAT)?></p>
                <p class="m-0"><?= esc_html__('Last edit') ?>:&nbsp;<?= get_post_datetime(field: 'modified')->format(GGL_ENGLISH_DATETIME_FORMAT)?></p>
            </div>
        </header>
        <?= apply_filters( 'the_content', get_the_content() ) ?>
    </article>
<?php
get_footer();
?>