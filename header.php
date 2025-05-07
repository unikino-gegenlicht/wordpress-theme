<?php
/**
 * The HTML header for the theme
 * 
 * This file renders the HTML header block for the wordpress theme which 
 * includes preloading/prefetching stylesheets, common assets and fonts
 */
defined( 'ABSPATH' ) || exit;

?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <title><?php bloginfo('name') ?></title>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width,initial-scale=1.0,shrink-to-fit=no">
	<link rel="profile" href="http://gmpg.org/xfn/11">
    <?php if (defined('WP_DEBUG') && WP_DEBUG == true): ?>
    <link rel="preload" href="<?= get_stylesheet_directory_uri()?>/style.css" as="style">
    <link rel="stylesheet" href="<?= get_stylesheet_directory_uri()?>/style.css" as="style">
    <?php else: ?>
    <link rel="preload" href="<?= get_stylesheet_directory_uri()?>/style.min.css" as="style">
    <link rel="stylesheet" href="<?= get_stylesheet_directory_uri()?>/style.min.css" as="style">
    <?php endif; ?>
    <link rel="preload" href="<?= get_stylesheet_directory_uri()?>/assets/fonts/gegenlicht.woff2" as="font" type="font/woff2" crossorigin>
    <link rel="preload" href="<?= get_stylesheet_directory_uri()?>/assets/fonts/open-sans.woff2" as="font" type="font/woff2" crossorigin>
    <link rel="preload" href="<?= get_stylesheet_directory_uri()?>/assets/fonts/open-sans-italic.woff2" as="font" type="font/woff2" crossorigin>
    <?php wp_head(); ?>
</head>