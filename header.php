<?php
/*
 * Copyright (c) 2026. Unikino GEGENLICHT. Licensed under the EUPL v1.2 or later
 */

defined( 'ABSPATH' ) || exit;
?>
<!DOCTYPE html>
<html <?php language_attributes() ?>>
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
    <title><?= wp_get_document_title() ?></title>
    <?php wp_head(); ?>
    <meta charset="<?php bloginfo( 'charset' ) ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="profile" href="http://gmpg.org/xfn/11">
</head>
<body>
<?php do_action( 'wp_body_open' ); ?>
<header>
    <?php get_template_part( 'navigation' ) ?>
</header>
<?php if ( function_exists( 'yoast_breadcrumb' ) && ! is_front_page() ): ?>
    <?php yoast_breadcrumb( '<div class="content-container mt-3"><p id="breadcrumbs">', '</p></div>' ); ?>
<?php endif ?>
