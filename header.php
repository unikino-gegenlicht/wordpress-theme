<?php
/**
 * The HTML header for the theme
 *
 * This file renders the HTML header block for the wordpress theme which
 * includes preloading/prefetching stylesheets, common assets and fonts
 */
defined( 'ABSPATH' ) || exit;

$langs = array();
$acceptHeader = $_SERVER['HTTP_ACCEPT_LANGUAGE'];
$acceptedLanguage = explode(',', $acceptHeader)[0];

$outputLanguage = "";
if (str_starts_with($acceptedLanguage, 'de')) {
    $outputLanguage = 'de';
} else if (str_starts_with($acceptedLanguage, 'en')) {
    $outputLanguage = 'en';
} else {
    $outputLanguage = 'en';
}
?>
<!DOCTYPE html>
<html lang="<?= $outputLanguage ?>" data-theme="">
<head>
    <title><?php bloginfo( 'name' ) ?></title>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width,initial-scale=1.0,shrink-to-fit=no">
    <link rel="profile" href="http://gmpg.org/xfn/11">
	<?php if ( defined( 'WP_DEBUG' ) && WP_DEBUG ): ?>
        <link rel="preload" href="<?= get_stylesheet_directory_uri() ?>/style.css" as="style">
        <link rel="stylesheet" href="<?= get_stylesheet_directory_uri() ?>/style.css" as="style">
	<?php else: ?>
        <link rel="preload" href="<?= get_stylesheet_directory_uri() ?>/style.min.css" as="style">
        <link rel="stylesheet" href="<?= get_stylesheet_directory_uri() ?>/style.min.css" as="style">
	<?php endif; ?>
    <link rel="preload" href="<?= get_stylesheet_directory_uri() ?>/assets/fonts/gegenlicht.woff2" as="font"
          type="font/woff2" crossorigin>
    <link rel="preload" href="<?= get_stylesheet_directory_uri() ?>/assets/fonts/inter.woff2" as="font"
          type="font/woff2" crossorigin>
    <link rel="preload" href="<?= get_stylesheet_directory_uri() ?>/assets/fonts/inter_italic.woff2" as="font"
          type="font/woff2" crossorigin>
    <script src="<?= get_stylesheet_directory_uri() ?>/assets/js/menu-toggle.js"></script>
	<?php wp_head(); ?>
</head>
<body>
<header>
    <nav class="navbar is-spaced is-primary" role="navigation" aria-label="main navigation">
        <div class="navbar-brand px-2">
            <a class="navbar-item p-0" href="<?= get_home_url( scheme: 'https' ) ?>">
				<?php
				if ( get_theme_mod( 'header_logo' ) != false ):
					$logoId = get_theme_mod( 'header_logo' );
					$alternativeDescription = get_post_meta( $logoId, '_wp_attachment_image_alt', true );
					$logoUrl = wp_get_attachment_image_url( $logoId, 'full' )
					?>
                    <img style="height: 56px" src="<?= $logoUrl ?>" alt="<?= $alternativeDescription ?>"/>
				<?php else: ?>
                    <p class="title has-text-black">
						<?= str_replace( ' ', '<br/>', get_bloginfo( 'name' ) ) ?>
                    </p>
				<?php endif; ?>
            </a>

            <a role="button" id="burger" class="navbar-burger" aria-label="menu" aria-expanded="false"
               style="color:black;"
               onclick="toggleMenu()">
                <span aria-hidden="true"></span>
                <span aria-hidden="true"></span>
                <span aria-hidden="true"></span>
                <span aria-hidden="true"></span>
            </a>
        </div>
        <div class="navbar-menu" id="menu">
            <div class="navbar-end">
                <!-- Generated Menu by WordPress -->
				<?php
				$navigationItems = wp_get_nav_menu_items( wp_get_nav_menu_name( 'navigation-menu' ) );
				if ( $navigationItems ):
					for ( $i = 0; $i < count( $navigationItems ); $i ++ ):
						$navigationItem = $navigationItems[ $i ];

						$num = '';
						if ( ( $i + 1 ) < 9 ) {
							$num = '0' . $i + 1;
						} else {
							$num = $i + 1;
						}
						global $wp;
						$currentPage = $navigationItem->url == home_url( $wp->request ) || $navigationItem->url == home_url( $wp->request ).'/';
						?>
                        <!-- <?= $navigationItem->url ?> -->
                        <!-- <?= home_url( $wp->request ) ?> -->
                        <a class="navbar-item is-size-5 is-tab <?= $currentPage ? 'is-active' : '' ?>"
                           href="<?= $navigationItem->url ?>"><?= $num ?> <span
                                    class="is-size-5 has-text-weight-semibold is-uppercase "><?= $navigationItem->title ?></span></a>
					<?php endfor; ?>
				<?php endif; ?>
                <hr>
				<?php if ( is_user_logged_in() ): ?>
                    <a class="navbar-item" href="<?= wp_logout_url( is_home() ? home_url() : home_url( $wp->request ) ) ?>">
                        <span class="icon-text">
                        <span
                                class="icon"><span class="material-symbols">logout</span></span>
                        <span class="is-size-5 has-text-weight-semibold is-uppercase is-hidden-desktop"><?= esc_html__( 'Logout', 'gegenlicht' ) ?></span></span>
                    </a>
				<?php else: ?>
                    <a class="navbar-item" href="<?= wp_login_url( is_home() ? home_url() : home_url( $wp->request ) ) ?>">
                        <span class="icon-text">
                        <span
                                class="icon"><span class="material-symbols">login</span></span>
                        <span class="is-size-5 has-text-weight-semibold is-uppercase is-hidden-desktop"><?= esc_html__( 'Login', 'gegenlicht' ) ?></span></span>
                    </a>
				<?php endif ?>
            </div>
        </div>
    </nav>
</header>
</body>
