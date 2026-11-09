<?php
/*
 * Copyright (c) 2026. Unikino GEGENLICHT. Licensed under the EUPL v1.2 or later
 */
?>

<nav class="navbar is-transparent" role="navigation"
     aria-label="<?= __( 'Main Navigation', 'cinema-press-theme' ) ?>">
    <div class="navbar-brand px-1">
        <a class="navbar-item m-0 p-1"
           href="<?= get_home_url( scheme: 'https' ) ?>">
            <?php if ( get_header_image() ): ?>
                <img src="<?php header_image() ?>" width="150"
                     height="30"
                     alt="<?= get_bloginfo( 'name', 'display' ) ?>" style="object-fit: contain">
            <?php endif; ?>
        </a>
        <a class="navbar-burger" role="button" aria-label="menu" aria-expanded="false" data-target="navMenu">
            <span aria-hidden="true"></span>
            <span aria-hidden="true"></span>
            <span aria-hidden="true"></span>
            <span aria-hidden="true"></span>
        </a>
    </div>
    <div class="navbar-menu is-shadowless" id="navMenu">
        <div class="navbar-end">
            <?php
            $navigation_items = wp_get_nav_menu_items( wp_get_nav_menu_name( 'header' ) ) ?: [];
            for ( $i = 0; $i < count( $navigation_items ); $i ++ ) {
                $item         = $navigation_items[ $i ];
                $isActivePage = $item->url === ( home_url( $wp->request ) . '/' ) || $item->url === ( home_url( $wp->request ) );
                ?>
                <a class="navbar-item is-size-5-touch px-2 <?= $isActivePage ? 'is-active' : '' ?>"
                   href="<?= $item->url ?>">
                    <span style="font-weight: 600"><?= str_pad( $i + 1, 2, "0", STR_PAD_LEFT ) ?></span>
                    <span class="is-hidden-tablet">&nbsp;</span>
                    <span class="is-uppercase font-heading"><?= $item->title ?></span>
                </a>
                <?php
            }
            ?>
            <?php if (is_single() && current_user_can('edit_post', $post->ID)): ?>
                <a class="navbar-item is-size-5-touch px-2" href="<?= get_edit_post_link($post) ?>">
                    <i class="fa-solid fa-pen-to-square"></i>
                    <span class="is-hidden-desktop is-uppercase font-heading"><?= esc_html__( 'Edit' ) ?></span>
                </a>
            <?php endif; ?>

            <?php if ( current_user_can_for_site( 0, 'edit_posts' ) ): ?>
                <a class="navbar-item is-size-5-touch px-2" href="<?= admin_url() ?>">

                    <i class="fa-brands fa-lg fa-wordpress-simple"></i>
                    <span class="is-hidden-desktop is-uppercase font-heading"><?= esc_html__( 'Show Dashboard', 'cinema-press-theme' ) ?></span>
                </a>
                <a class="navbar-item is-size-5-touch px-2"
                   href="<?= wp_logout_url( home_url( $wp->request, 'https' ) ) ?>">
                    <span class="icon-text">
                        <span class="icon is-medium">
                            <i class="fa-solid fa-arrow-right-to-arc"></i>
                        </span>
                        <span class="is-hidden-desktop is-uppercase font-heading"><?= esc_html__( 'Log out' ) ?></span>
                    </span>
                </a>
            <?php else: ?>
                <a class="navbar-item is-size-5-touch px-2"
                   href="<?= wp_login_url( home_url( $wp->request, 'https' ) ) ?>">
                    <span class="icon-text">
                        <span class="icon is-medium">
                            <i class="fa-solid fa-arrow-right-to-arc"></i>
                        </span>
                        <span class="is-hidden-desktop is-uppercase font-heading"><?= esc_html__( 'Log in' ) ?></span>
                    </span>
                </a>
            <?php endif; ?>
        </div>
    </div>
</nav>
