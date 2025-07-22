<?php
defined( 'ABSPATH' ) || exit;
get_header();

$semesters = get_terms( array(
	'taxonomy'   => 'semester',
	'hide_empty' => false,
) );

$map = [];

foreach ( $semesters as $semester ) {
	$start             = (string) get_term_meta( $semester->term_id, 'semester_start', true );
	$date              = date_parse_from_format( "d.m.Y", $start );
	$timestamp         = mktime( $date['hour'] ?: '0', null, null, $date['month'], $date['day'], $date['year'] );
	$map[ $timestamp ] = $semester;
}
ksort( $map );
?>

<main class="page-content">
    <div class="content">
        <h1><?= get_theme_mod( 'archive_header' )[ get_locale() ] ?? "AAAAAA" ?></h1>
		<?= get_theme_mod( 'archive_text' )[ get_locale() ] ?? "" ?>
    </div>
    <hr class="separator"/>
	<?php
	foreach ( $map
	as $timestamp => $semester ) :
	$manualEntries      = get_term_meta( $semester->term_id, 'semester_shown_movies', true );
	$add_manual_entries = (bool) get_term_meta( $semester->term_id, 'semester_add_archival_data', true );
	if ( ! empty( $manualEntries ) && ! $add_manual_entries ):
	$entries = [];
	foreach ( $manualEntries as $manual_entry ):
		$date                  = date_parse_from_format( "d.m.Y", $manual_entry[0] );
		$timestamp             = mktime( $date['hour'] ?: '0', null, null, $date['month'], $date['day'], $date['year'] );
		$entries[ $timestamp ] = $manual_entry[1];
	endforeach;
	ksort( $entries, SORT_NUMERIC );
	?>
    <div class="archive-list mb-6">
        <p style="background-color: var(--bulma-body-color); color: var(--bulma-body-background-color)"
           class="font-ggl is-uppercase is-size-5 py-1 pl-1"><?= $semester->name ?></p>

		<?php
		foreach ( $entries as $date => $name ):
			?>
            <p aria-label="<?= $name ?>"
               class="has-text-weight-bold is-uppercase font-ggl py-1"><?= $name ?></p>
		<?php
		endforeach;
		endif;

		$movies = new WP_Query( array(
			'post_type'      => [ 'movie', 'event' ],
			'posts_per_page' => - 1,
			'meta_query'     => [
				[
					'key'     => 'screening_date',
					'value'   => time(),
					'compare' => '<=',
				]
			],
			'tax_query'      => [
				[
					'taxonomy' => 'semester',
					'field'    => 'term_id',
					'terms'    => $semester->term_id,
				]
			],
			'meta_key'       => 'screening_date',
			'orderby'        => 'meta_value_num',
			'order'          => 'ASC',
		) );

		if ( $movies->have_posts() ) :
			?>
            <div class="archive-list mb-6">
                <p style="background-color: var(--bulma-body-color); color: var(--bulma-body-background-color)"
                   class="font-ggl is-uppercase is-size-5 py-1 pl-1"><?= $semester->name ?></p>
				<?php
				while ( $movies->have_posts() ): $movies->the_post();
					$programType    = rwmb_meta( 'program_type' );
					$specialProgram = rwmb_meta( 'special_program' );
					$showDetails    = ( rwmb_meta( 'license_type' ) == 'full' || is_user_logged_in() );
					$title          = $showDetails ? ( get_locale() == 'de' ? rwmb_meta( 'german_title' ) : rwmb_meta( 'english_title' ) ) : ( $programType == 'special_program' ? get_term( $specialProgram )->name : esc_html__( 'An unnamed movie', 'gegenlicht' ) ) ?>
                    <p aria-label="<?= $title ?>"
                       class="has-text-weight-bold is-uppercase font-ggl py-1"><?= $title ?></p>

				<?php endwhile; ?>
            </div>
		<?php endif ?>
		<?php endforeach ?>
</main>

<?php get_footer(); ?>
