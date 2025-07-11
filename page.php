<?php
/**
 * Template Name: Plain Content Page
 */

defined( 'ABSPATH' ) || exit;
get_header();
do_action('wp_body_open');
?>
<article class="page-content content">
    <?= get_the_content() ?>
</article>
<?php get_footer(); ?>