<?php
/**
 * Template Name: Privacy Policy
 */

defined('ABSPATH') || exit;
get_header();
?>
<main id="privacyPolicy" class="page-content">
	<header>
		<h1><?= esc_html__('Privacy Policy') ?></h1>
	</header>
	<div class="pt-3 content privacy-policy">
		<?=	apply_filters('the_content', get_the_content()) ?>
	</div>
</main>

