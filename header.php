<?php
defined( 'ABSPATH' ) || exit;

$headerImage      = get_theme_mod( 'header_logo' );
$smallHeaderImage = get_theme_mod( 'small_header_logo' );

headers_send( 103 );


$fallbackImage = get_theme_mod( 'anonymous_image' );
$semesterID    = get_theme_mod( 'displayed_semester' );

$semester               = get_term( $semesterID, 'semester' );
$semesterScreeningStart = get_term_meta( $semester->term_id, 'semester_start', true );
$startDate              = date_parse_from_format( "d.m.Y", $semesterScreeningStart );
$supposedStartDate      = mktime( 0, 0, 0, $startDate['month'], $startDate['day'], $startDate['year'] );

if ( time() < strtotime( "-14 days", $supposedStartDate )) {
	define( "GGL_ANNOUNCE_NEW_PROGRAM", true);
	define( "GGL_SEMESTER_BREAK", false);
} else {
	define( "GGL_ANNOUNCE_NEW_PROGRAM", false);
	define( "GGL_SEMESTER_BREAK", false);

}

if ( ! defined( 'GGL_SEMESTER_BREAK' )) {
	$next_meta       = [];
	$next_meta[]     = [
		'key'     => 'screening_date',
		'value'   => time(),
		'compare' => '>=',
	];
	$next_query_args = array(
		'do_preload'     => false,
		'post_type'      => [ 'movie', 'event' ],
		'posts_per_page' => 1,
		'meta_query'     => $next_meta,
		'tax_query'      => array(
			array(
				'taxonomy' => 'semester',
				'terms'    => $semesterID,
			)
		),
		'meta_key'       => 'screening_date',
		'orderby'        => 'meta_value_num',
		'order'          => 'ASC',
	);

	$next = new WP_Query( $next_query_args );

	define( "GGL_SEMESTER_BREAK", ! $next->have_posts() || get_theme_mod( "manual_semester_break" ) );
}


/**
 * Configure the page title used in the HTML <head> element
 */
$args               = $args ?? [ "hideBreakBanner" => false ];
$hideBreakBanner    = ( $args['hideBreakBanner'] ?? false ) == true;
$manualPartialTitle = trim( $args["title"] ?? "" );
$blogName           = get_bloginfo( "name" );


if ( $manualPartialTitle ) {
	define( "GGL_PAGE_TITLE", join( GGL_TITLE_SEPARATOR, [ $manualPartialTitle, $blogName ] ) );
}

if ( is_singular( [ "movie", "event" ] ) && ! defined( "GGL_PAGE_TITLE" ) ) {
	$showDetails      = ( rwmb_meta( 'license_type' ) == 'full' || is_user_logged_in() );
	$title            = get_locale() == "de" ? rwmb_meta( 'german_title' ) : rwmb_meta( 'english_title' );
	$inSpecialProgram = rwmb_meta( 'program_type' ) == 'special_program';

	if ( ! $showDetails && $inSpecialProgram ) {
		$specialProgramID = rwmb_meta( 'special_program' );
		$specialProgram   = get_term( $specialProgramID, 'special-program' );
		$title            = $specialProgram->name;
	}

	if ( ! $showDetails ) {
		$title = __( "An unnamed movie", "gegenlicht" );
	}
	define( "GGL_PAGE_TITLE", join( GGL_TITLE_SEPARATOR, [ $title, $blogName ] ) );
}

if ( is_archive() && ! defined( "GGL_PAGE_TITLE" ) ) {
	define( "GGL_PAGE_TITLE", join( GGL_TITLE_SEPARATOR, [
		post_type_archive_title( display: false ),
		get_bloginfo( "name" )
	] ) );
}

if ( is_privacy_policy() && ! defined( "GGL_PAGE_TITLE" ) ) {
	define( "GGL_PAGE_TITLE", join( GGL_TITLE_SEPARATOR, [
		__( "Privacy Policy", "gegenlicht" ),
		get_bloginfo( "name" )
	] ) );
}

if ( is_location_page() && ! defined( "GGL_PAGE_TITLE" ) ) {
	define( "GGL_PAGE_TITLE", join( GGL_TITLE_SEPARATOR, [
		__( "Location", "gegenlicht" ),
		get_bloginfo( "name" )
	] ) );
}

if ( ! defined( "GGL_PAGE_TITLE" ) ) {
	if ( is_front_page() ) {
		define( "GGL_PAGE_TITLE", get_bloginfo( "name" ) . " – " . get_bloginfo( "description" ) );
	} else {
		define( "GGL_PAGE_TITLE", join( GGL_TITLE_SEPARATOR, [
			get_the_title(),
			get_bloginfo( "name" )
		] ) );
	}
}

?>
<!DOCTYPE html>
<html lang="<?= substr( get_locale(), 0, 2 ) ?>" data-theme="">
<head>
    <title><?= esc_html( GGL_PAGE_TITLE ) ?></title>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width,initial-scale=1.0,shrink-to-fit=no">
    <link rel="profile" href="http://gmpg.org/xfn/11">
	<?php wp_head(); ?>
</head>
<?php do_action( 'wp_body_open' ); ?>
<body>
<header>
    <nav class="navbar" role="navigation" aria-label="main navigation">
        <div class="navbar-brand px-2">
            <a class="navbar-item m-0 p-0 my-2 is-flex is-tab is-hidden-desktop-only"
               href="<?= get_home_url( scheme: 'https' ) ?>"
               style="border-bottom: none !important;" aria-label="Back To Start">
				<?php
				if ( $headerImage != false ):
					$alternativeDescription = get_post_meta( $headerImage, '_wp_attachment_image_alt', true );

					$slement = file_get_contents( get_attached_file( $headerImage, true ) );

					echo $slement;
					?>

				<?php else: ?>
                    <p class="title has-text-black">
						<?= str_replace( ' ', '<br/>', get_bloginfo( 'name' ) ) ?>
                    </p>
				<?php endif; ?>
            </a>
            <a class="navbar-item p-0 my-2 is-tab is-hidden-mobile is-hidden-tablet-only is-hidden-widescreen"
               href="<?= get_home_url( scheme: 'https' ) ?>"
               style="border-bottom: none !important;" aria-label="Back To Start">
				<?php
				if ( $smallHeaderImage ):
					$alternativeDescription = get_post_meta( $smallHeaderImage, '_wp_attachment_image_alt', true );

					$slement = file_get_contents( get_attached_file( $smallHeaderImage, true ) );

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
        <div class="navbar-menu is-shadowless" id="menu">
            <div class="navbar-end">
				<?php
				get_template_part( "partials/header-menu" ) ?>
                <hr class="separator is-hidden-desktop">
				<?php if ( is_user_logged_in() && current_user_can( 'edit_posts' ) ): ?>
                    <a class="navbar-item no-hover" href="<?= get_admin_url( scheme: 'https' ) ?>">
                        <span class="icon-text is-size-5">
                            <span class="icon">
                                <span class="si is-size-5 si-wordpress"></span>
                            </span>
                            <span class="is-size-5 has-text-weight-semibold is-uppercase is-hidden-desktop">
                                <?= esc_html__( 'To the backend' ) ?>
                            </span>
                        </span>
                    </a>
				<?php endif; ?>
				<?php if ( is_user_logged_in() ): ?>
                    <a class="navbar-item no-hover"
                       href="<?= wp_logout_url( is_home() ? home_url() : home_url( $wp->request ) ) ?>">
                        <span class="icon-text">
                        <span
                                class="icon"><span class="material-symbols">logout</span></span>
                        <span class="is-size-5 has-text-weight-semibold is-uppercase is-hidden-desktop"><?= esc_html__( 'Logout', 'gegenlicht' ) ?></span></span>
                    </a>
				<?php else: ?>
                    <a class="navbar-item no-hover"
                       href="<?= wp_login_url( is_home() ? home_url() : home_url( $wp->request ) ) ?>">
                        <span class="icon-text">
                        <span
                                class="icon"><span class="material-symbols">login</span></span>
                        <span class="is-size-5 has-text-weight-semibold is-uppercase is-hidden-desktop"><?= esc_html__( 'Login', 'gegenlicht' ) ?></span></span>
                    </a>
				<?php endif ?>
            </div>
        </div>
    </nav>
	<?php if ( GGL_SEMESTER_BREAK && ! $hideBreakBanner && ! GGL_ANNOUNCE_NEW_PROGRAM ): ?>
        <div class="page-content semester-break mb-5">
            <div class="marquee py-5">
                <div class="marquee-content">
					<?php for ( $i = 0; $i < 4; $i ++ ) {
						echo "<p>" . esc_html__( "Semester Break", 'gegenlicht' ) . " </p>";
						echo "<p>&#xE0A4;&ensp;&#xE0A4;&ensp;&#xE0A4;</p>";
					} ?>
                </div>
                <div class="marquee-content">
					<?php for ( $i = 0; $i < 4; $i ++ ) {
						echo "<p>" . esc_html__( "Semester Break", 'gegenlicht' ) . "</p>";
						echo "<p>&#xE0A4;&ensp;&#xE0A4;&ensp;&#xE0A4;</p>";
					} ?>
                </div>
            </div>
        </div>
	<?php endif; ?>
	<?php if ( GGL_ANNOUNCE_NEW_PROGRAM && ! $hideBreakBanner  ):
		$timeRemaining = $supposedStartDate - time();
		$daysRemaining = floor( $timeRemaining / ( 60 * 60 * 24 ) );
		$hoursRemaining = floor( ( $timeRemaining - ( $daysRemaining * 60 * 60 * 24 ) ) / ( 60 * 60 ) );
		?>
        <div class="page-content semester-break mb-5">
            <div class="marquee py-5">
                <div class="marquee-content">
					<?php for ( $i = 0; $i < 2; $i ++ ) {
						echo "<p>" . esc_html__( "New Program Releases In", 'gegenlicht' ) . "&nbsp;" . $daysRemaining . "&nbsp;" . ( $daysRemaining == 1 ? esc_html__( "Day", "gegenlicht" ) : esc_html__( "Days", "gegenlicht" ) ) . "&nbsp;" . $hoursRemaining . "&nbsp;" . ( $hoursRemaining == 1 ? esc_html__( "Hour", "gegenlicht" ) : esc_html__( "Hours", "gegenlicht" ) ) . "</p>";
						echo "<p>&#xE0A4;&ensp;&#xE0A4;&ensp;&#xE0A4;</p>";
					} ?>
                </div>
                <div class="marquee-content">
					<?php for ( $i = 0; $i < 2; $i ++ ) {
						echo "<p>" . esc_html__( "New Program Releases In", 'gegenlicht' ) . "&nbsp;" . $daysRemaining . "&nbsp;" . ( $daysRemaining == 1 ? esc_html__( "Day", "gegenlicht" ) : esc_html__( "Days", "gegenlicht" ) ) . "&nbsp;" . $hoursRemaining . "&nbsp;" . ( $hoursRemaining == 1 ? esc_html__( "Hour", "gegenlicht" ) : esc_html__( "Hours", "gegenlicht" ) ) . "</p>";
						echo "<p>&#xE0A4;&ensp;&#xE0A4;&ensp;&#xE0A4;</p>";
					} ?>
                </div>
            </div>
        </div>
		<?php endif; ?>
</header>