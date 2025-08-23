<?php
$navItems = wp_get_nav_menu_items( wp_get_nav_menu_name( 'navigation-menu' ) ) ?: [];
for ( $i = 0; $i < count( $navItems ); $i ++ ) {
	$item = $navItems[ $i ];

	global $wp;
	$isActivePage = $item->url === (home_url( $wp->request) . '/') || $item->url === (home_url( $wp->request));
	$page         = get_page_by_path( $item->url );

	$title = $item->title;

    if ($item->url == home_url()) {
        $title = __("Program", "gegenlicht");
    }

	if ( $page != null && $page->ID == get_theme_mod( "location_page" ) ) {
		$title = __( "Location", "gegenlicht" );
	}

    if ($item->url == get_post_type_archive_link("movie")) {
        $title = __( "Archive", "gegenlicht" );
    }

	if ($item->url == get_post_type_archive_link("team-member")) {
		$title = __( "Team", "gegenlicht" );
	}

	if ($item->url == get_post_type_archive_link("cooperation-partner")) {
		$title = __( "Cooperation", "gegenlicht" );
	}

	if ($item->url == get_post_type_archive_link("supporter")) {
		$title = __( "Supporters", "gegenlicht" );
	}
	?>
    <a class="navbar-item is-size-5 py-0 px-2 <?= $isActivePage ? 'is-active' : '' ?>"
       href="<?= $item->url ?>">
        <span><?= str_pad($i+1, 2, "0", STR_PAD_LEFT) ?>&nbsp;</span>
        <span class="font-ggl is-uppercase"><?= $title ?></span>
    </a>
	<?php
}
