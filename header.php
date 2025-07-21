<?php
/**
 * Template Name: Page Header
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

$headerImage = get_theme_mod('header_logo');


?>
<!DOCTYPE html>
<html lang="<?= $outputLanguage ?>" data-theme="">
<head>
    <title><?php bloginfo( 'name' ) ?></title>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width,initial-scale=1.0,shrink-to-fit=no">
    <link rel="profile" href="http://gmpg.org/xfn/11">
	<?php wp_head(); ?>
</head>
<body>
<header>
    <nav class="navbar px-4 mb-5" role="navigation" aria-label="main navigation">
        <div class="navbar-brand px-2">
            <a class="navbar-item p-0 my-2 is-tab" href="<?= get_home_url( scheme: 'https' ) ?>" style="border-bottom: none !important;" aria-label="Back To Start">
				<?php
				if ( $headerImage != false ):
					$alternativeDescription = get_post_meta( $headerImage, '_wp_attachment_image_alt', true );

                    $slement = file_get_contents(get_attached_file($headerImage, true));

                    echo $slement;
					?>

				<?php else: ?>
                    <p class="title has-text-black">
						<?= str_replace( ' ', '<br/>', get_bloginfo( 'name' ) ) ?>
                    </p>
				<?php endif; ?>
            </a>

            <a role="button" id="burger" class="navbar-burger" aria-label="menu" aria-expanded="false"
               style="color: var(--bulma-body-color);"
               onclick="toggleMenu()">
                <span aria-hidden="true"></span>
                <span aria-hidden="true"></span>
                <span aria-hidden="true"></span>
                <span aria-hidden="true"></span>
            </a>
        </div>
        <div class="navbar-menu" id="menu">
            <div class="navbar-end">
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
                        <a class="navbar-item is-size-5 <?= $currentPage ? 'is-active' : '' ?>"
                           href="<?= $navigationItem->url ?>"><span><?= $num ?>&nbsp;<span
                                    class="is-size-5 has-text-weight-semibold is-uppercase "><?= $navigationItem->title ?></span></span></a>
					<?php endfor; ?>
				<?php endif; ?>
                <hr class="separator is-hidden-desktop">
	            <?php if (is_user_logged_in() && current_user_can('edit_posts')): ?>
                    <a class="navbar-item no-hover" href="<?= get_admin_url(scheme: 'https') ?>">
                        <span class="icon-text is-size-5">
                            <span class="icon">
                                <span class="si is-size-5 si-wordpress"></span>
                            </span>
                            <span class="is-size-5 has-text-weight-semibold is-uppercase is-hidden-desktop">
                                <?= esc_html__('To the backend') ?>
                            </span>
                        </span>
                    </a>
	            <?php endif; ?>
				<?php if ( is_user_logged_in() ): ?>
                    <a class="navbar-item no-hover" href="<?= wp_logout_url( is_home() ? home_url() : home_url( $wp->request ) ) ?>">
                        <span class="icon-text">
                        <span
                                class="icon"><span class="material-symbols">logout</span></span>
                        <span class="is-size-5 has-text-weight-semibold is-uppercase is-hidden-desktop"><?= esc_html__( 'Logout', 'gegenlicht' ) ?></span></span>
                    </a>
				<?php else: ?>
                    <a class="navbar-item no-hover" href="<?= wp_login_url( is_home() ? home_url() : home_url( $wp->request ) ) ?>">
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