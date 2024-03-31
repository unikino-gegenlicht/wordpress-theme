<?php

/******************************************************************************
 * Copyright (c) 2024. Philip Kaufmann, Jan Eike Suchard, Benjamin Witte      *
 * This file is copyright under the latest version of the EUPL.               *
 * Please see the LICENSE file for your rights.                               *
 ******************************************************************************/

/**
 * The template for the 404 (Not found) pages
 *
 * @package gegenlicht
 */

// exit the handling if the page is directly accessed.
defined('ABSPATH') || exit;

get_header();
?>

<div class="wrapper p-0" id="page-wrapper">
    <div id="content" tabindex="-1">
        <div class="row">
            <div class="col-12 p-0" id="header-404">
                <div class="px-3 glitter">
                    <header id="wrapper-navbar">
                        <a class="skip-link sr-only sr-only-foucusabl" href="#content">
                            <?php
                            esc_html_e('Skip to content', 'ggl-theme');
                            ?>
                        </a>
                        <?php
                        get_template_part('includes/navbar')
                        ?>
                    </header>
                    <div class="row align-items-end" style="min-height: 400px;">
                        <div class="col-xl-1 d-none d-xl-block"></div> <!-- Spacer links -->
                        <div class="col-xl-10 col-12 p-3">
                            <h1 class="text-uppercase ggl-font text-primary">404</h1>
                            <h2 class="text-uppercase ggl-font text-primary">
                                <?php
                                esc_html_e("This shouldn't have been shown to you", 'ggl-theme');
                                ?>
                            </h2>
                        </div>
                        <div class="col-xl-1 d-none d-xl-block"></div> <!-- Spacer rechts -->
                    </div>
                </div>
            </div>
            <div class="col-12 bg-primary">
                <div class="row align-items-start">
                    <div class="col-0 col-lg-1"></div> <!-- Spacer -->
                    <div class="col-12 col-lg-6 p-2">
                        <h4 class="text-secondary ggl-font text-uppercase">
                            <?php
                            esc_html_e("because something went wrong here. The requested page could not be found", 'ggl-theme');
                            ?>
                        </h4>
                        <p class="text-secondary lead">
                            <?php
                            esc_html_e('Usually you are not supposed to see this page. Possibly some gremlins were fed by us after midnight... Just choose a page in the menu above and get right back on track', 'ggl-theme');
                            ?>
                        </p>
                        <p class="text-secondary lead">
                            <?php
                            esc_html_e("If you don't like secrets, tell us how you got here, maybe we will try to fix it. You can reach us via social media or our e-mail address", 'ggl-theme');
                            ?>
                        </p>
                    </div>
                    <div class="col-12 col-md-6 col-lg-4 p-2">
                        <a class="text-secondary text-decoration-none" href="https://gegenlicht.net/facebook/">
                            <div class="ggl-button m-3">
                                <p>
                                    <span class="icon">
                                        <img alt="facebook brand icon"
                                             src="<?= get_theme_root() ?>/assets/icons/facebook.svg">
                                    </span>
                                    <span class="text-uppercase">Facebook</span>
                                </p>
                            </div>
                        </a>
                        <a class="text-secondary text-decoration-none" href="https://gegenlicht.net/mastodon/">
                            <div class="ggl-button m-3">
                                <p>
                                    <span class="icon">
                                        <img alt="mastodon brand icon"
                                             src="<?= get_theme_root() ?>/assets/icons/mastodon.svg">
                                    </span>
                                    <span class="text-uppercase">Mastodon</span>
                                </p>
                            </div>
                        </a>
                        <a class="text-secondary text-decoration-none" href="https://gegenlicht.net/instagram/">
                            <div class="ggl-button m-3">
                                <p>
                                    <span class="icon">
                                        <img alt="instagram brand icon"
                                             src="<?= get_theme_root() ?>/assets/icons/instagram.svg">
                                    </span>
                                    <span class="text-uppercase">Instagram</span>
                                </p>
                            </div>
                        </a>
                        <a class="text-secondary text-decoration-none" href="https://gegenlicht.net/whatsapp/">
                            <div class="ggl-button m-3">
                                <p>
                                    <span class="icon">
                                        <img alt="whatsapp brand icon"
                                             src="<?= get_theme_root() ?>/assets/icons/whatsapp.svg">
                                    </span>
                                    <span class="text-uppercase">WhatsApp</span>
                                </p>
                            </div>
                        </a>
                    </div>
                    <div class="col-0 col-lg-1"></div> <!-- Spacer -->
                </div>
            </div>
        </div>
    </div>
</div>
<?php
get_footer();

