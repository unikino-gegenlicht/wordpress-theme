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
    <?php if ( defined('WP_DEBUG') && WP_DEBUG ): ?>
    <link rel="preload" href="<?= get_stylesheet_directory_uri()?>/style.css" as="style">
    <link rel="stylesheet" href="<?= get_stylesheet_directory_uri()?>/style.css" as="style">
    <?php else: ?>
    <link rel="preload" href="<?= get_stylesheet_directory_uri()?>/style.min.css" as="style">
    <link rel="stylesheet" href="<?= get_stylesheet_directory_uri()?>/style.min.css" as="style">
    <?php endif; ?>
    <link rel="preload" href="<?= get_stylesheet_directory_uri()?>/assets/fonts/gegenlicht.woff2" as="font" type="font/woff2" crossorigin>
    <link rel="preload" href="<?= get_stylesheet_directory_uri()?>/assets/fonts/inter.woff2" as="font" type="font/woff2" crossorigin>
    <link rel="preload" href="<?= get_stylesheet_directory_uri()?>/assets/fonts/inter_italic.woff2" as="font" type="font/woff2" crossorigin>
    <?php if (is_user_logged_in()): ?>
    <link rel="preload" href="<?= get_stylesheet_directory_uri()?>/assets/icons/logout.svg" as="image" type="image/svg+xml" crossorigin>
    <?php else: ?>
    <link rel="preload" href="<?= get_stylesheet_directory_uri()?>/assets/icons/account.svg" as="image" type="image/svg+xml" crossorigin>
    <?php endif; ?>
    <?php wp_head(); ?>
</head>
<header>
    <nav class="navbar is-spaced" role="navigation" aria-label="main navigation">
        <div class="navbar-brand">
            <a class="navbar-item" href="<?= get_home_url(scheme: 'https') ?>">
                <?php
                    $logoId = get_theme_mod('custom_logo');
                    $alternativeDescription = get_post_meta($logoId, '_wp_attachment_image_alt', true);
                    $logoUrl = wp_get_attachment_image_url($logoId, 'full')
                ?>
                <img src="<?= $logoUrl ?>" alt="<?= $alternativeDescription ?>"/>
            </a>
        </div>
        <div class="navbar-menu">
            <div class="navbar-end">
                <!-- Generated Menu by WordPress -->
                <?php
                $navigationItems = wp_get_nav_menu_items('navigation');
                for ( $i = 0; $i < count($navigationItems); $i ++ ):
                    $navigationItem = $navigationItems[$i];
                ?>
                    <a class="navbar-item" href="<?= $navigationItem->url ?>"><?= $i ?>. <span class="has-text-weight-semibold"><?= $navigationItem->title ?></span></a>
                <?php endfor; ?>
                <?php if (is_user_logged_in()): ?>
                    <a class="navbar-item" href="<?= wp_logout_url(get_page_uri()) ?>"><span class="icon is-large"><img src="<?=get_stylesheet_directory_uri()?>/assets/icons/logout.svg"</span></a>
                <?php else: ?>
                    <a class="navbar-item" href="<?= wp_login_url(get_page_uri()) ?>"><span class="icon is-large"><img src="<?=get_stylesheet_directory_uri()?>/assets/icons/account.svg"</span></a>
                <?php endif ?>
            </div>
        </div>
    </nav>
</header>