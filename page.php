<?php
defined( 'ABSPATH' ) || exit;
do_action( 'wp_body_open' );

if ( is_location_page() ) {
	get_header();
	get_template_part( 'templates/page', 'location' );
	get_footer();

	return;
}

if ( is_impress_page() ) {
	get_header( args: [ "hideBreakBanner" => true ] );
	get_template_part( 'templates/page', 'impress' );
	get_footer();

	return;
}

if ( is_contact_page() ) {
	get_header();
	get_template_part( 'templates/page', 'contact' );
	get_footer();

	return;
}

get_header();
?>
    <article class="page-content content mt-4">
        <header>
            <h1><?= get_the_title() ?></h1>
        </header>
		<?= apply_filters( 'the_content', get_the_content() ) ?>
    </article>
<?php
get_footer();
?>