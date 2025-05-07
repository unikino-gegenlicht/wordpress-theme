<?php
/**
 * The HTML header for the theme
 * 
 * This file renders the HTML header block for the wordpress theme which 
 * includes preloading/prefetching stylesheets, common assets and fonts
 */
defined( 'ABSPATH' ) || exit;

$upcoming = get_upcoming();

$isPlayPause = count($upcoming) == 0;
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <title><?php bloginfo('name') ?></title>
    <meta name="description" content="<?php bloginfo('description') ?>"> 
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
    <?php
    foreach ($upcoming as $movie) {
	    post_image_preload_header($movie);
    }
    ?>
    <?php wp_head(); ?>
</head>
<body>
<header>
    <?php if ($isPlayPause): ?>
    <section class="hero is-upcoming is-halfheight" style="background-image: linear-gradient(to bottom, rgba(0,0,0,0), rgba(0,0,0,0.8)), url(<?= $upcoming[0]->imageUrl ?>)">
        <div class="hero-body is-flex p-0 pb-5" style="background-image: url(<?= get_stylesheet_directory_uri()?>/assets/img/gl_gitter.png)">
            <div class="header-content is-flex is-align-self-flex-end">
                <div class="is-align-self-flex-end">
                    <p class="font-ggl has-text-primary is-size-4"><?= $upcoming[0]->screeningDate->format("d.m.Y") . ' ' . __('at') . ' ' . $upcoming[0]->screeningDate->format("H:i") ?></p>
                    <p class="font-ggl has-text-primary is-size-2 is-smallcaps"><?= $upcoming[0]->title ?></p>
                    <p class="font-ggl has-text-primary is-size-4"> <?= $upcoming[0]->mainGenre ?> | <?= $upcoming[0]->duration ?>min | <?= $upcoming[0]->location ?></p>
                </div>
            </div>
        </div>
    </section>
    <?php if (count($upcoming) > 1): ?>
        <?php for($i = 1; $i < count($upcoming); $i++): ?>
            <section class="hero is-upcoming is-following is-small">
            <div class="hero-body is-flex p-0 py-4" style="background-image: url(<?= get_stylesheet_directory_uri()?>/assets/img/gl_gitter.png)">
                <div class="header-content is-flex">
                <div class="is-align-self-flex-end">
                    <p class="font-ggl has-text-primary is-size-5"><?= __('Afterwards at') ?> <?= $upcoming[$i]->screeningDate->format("H:i")  ?></p>
                    <p class="font-ggl has-text-primary is-size-3 is-smallcaps"><?= $upcoming[$i]->title ?></p>
                    <p class="font-ggl has-text-primary is-size-5"> <?= $upcoming[$i]->mainGenre ?> | <?= $upcoming[$i]->duration ?>min | <?= $upcoming[$i]->location ?></p>
                </div>
                </div>
                
            </div>
            </section>
        <?php endfor; ?>
    <?php endif; ?>
    <?php else: ?>
        <section class="hero is-three-quarters" style="background-image: linear-gradient(to bottom, rgba(0,0,0,0), rgba(0,0,0,0.8))">
        <div class="hero-body is-flex is-flex-direction-column is-justify-content-center is-align-items-center" style="background-image: url(<?= get_stylesheet_directory_uri()?>/assets/img/gl_gitter.png)">
            <h1 class="font-ggl has-text-primary is-size-1 is-smallcaps is-not-selectable">Spielpause</h1>
            <p class="font-ggl has-text-primary is-size-2 is-smallcaps is-not-selectable">Leider haben wir gerade Spielpause, aber seid euch sicher, im nächsten Semester werden wir wieder für euch da sein</p>
            <p class="font-ggl has-text-primary is-size-2 is-smallcaps is-not-selectable">Schaut regelmäßig auf unseren Social Medias vobei um Aktionen und das neue Programm nicht zu verpassen</p>
        </div>
    </section>
    <?php endif; ?>
</header>