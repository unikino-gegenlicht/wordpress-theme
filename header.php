<?php
/*
 * Copyright (c) 2026. Unikino GEGENLICHT. Licensed under the EUPL v1.2 or later
 */

defined( 'ABSPATH' ) || exit;
?>
<!DOCTYPE html>
<html lang="<?= get_user_locale() ?>">
<head>
    <!--
    LICENSE DISCLAIMER

    This theme and all files thereof are the original work of the following
    people:
      - Philip Kaufmann <philip@gegenlicht.net> (Design)
      - Jan Eike Suchard <jan@gegenlicht.net> (Implementation)

     The theme's code is licensed under the terms of the EUPL v1.2 or later.

     For further information please check out the LICENSE file that should be
     available at the following address: <?= get_theme_file_uri( 'LICENSE' ) ?>
    -->
    <title><?php bloginfo( 'name' ) ?></title>
    <?php wp_head(); ?>
    <meta charset="<?php bloginfo( 'charset' ) ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="profile" href="http://gmpg.org/xfn/11">
</head>
<body>
<?php do_action( 'wp_body_open' ); ?>
<header>
    <nav class="navbar is-fixed-top is-primary" role="navigation" aria-label="<?= __( 'Main Navigation', 'cine-press-theme' ) ?>">
        <div class="navbar-brand px-1">
            <a class="navbar-item m-0 is-flex is-tab is-hidden-desktop-only"
               href="<?= get_home_url( scheme: 'https' ) ?>">
                <?= get_bloginfo('name') ?>
            </a>
        </div>
        <div class="navbar-menu is-shadowless">
            <div class="navbar-end">
                <?php
                $navigation_items = wp_get_nav_menu_items( wp_get_nav_menu_name( 'header-menu' ) ) ?: [];
                for ( $i = 0; $i < count( $navigation_items ); $i ++ ) {
                    $item         = $navigation_items[ $i ];
                    $isActivePage = $item->url === ( home_url( $wp->request ) . '/' ) || $item->url === ( home_url( $wp->request ) );
                    ?>
                    <a class="navbar-item is-size-5-touch px-2 <?= $isActivePage ? 'is-active' : '' ?>">
                        <span style="font-weight: bold"><?= str_pad( $i + 1, 2, "0", STR_PAD_LEFT ) ?></span><span
                                class="is-uppercase"><?= $item->title ?></span>
                    </a>
                    <?php
                }
                ?>
            </div>
        </div>
    </nav>
</header>
