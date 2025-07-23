<?php
defined( 'ABSPATH' ) || exit;
get_header();
do_action('wp_body_open');
?>
<article class="page-content content">
    <header>
        <h1><?= get_the_title() ?></h1>
    </header>

    <?= apply_filters('the_content', get_the_content()) ?>
</article>
<?php get_footer(); ?>