<div class="content">
    <h2 class="title next-movie-title pb-5"><?= esc_html__( 'Intermission in the Cinema', 'gegenlicht' ) ?></h2>
	<?php
	get_template_part( 'partials/responsive-image', args: [
		'image_url'        => wp_get_attachment_image_url( get_theme_mod( 'semester_break_image' ), 'desktop' ),
		'mobile_image_url' => wp_get_attachment_image_url( get_theme_mod( 'semester_break_image' ), 'mobile' ),
		'fetch-priority'   => "high",
		"loading"          => "eager"
	] );
	?>
    <hr class="separator" style="margin-top: 1rem !important;"/>
    <h3 class="mt-0 pb-2"><?= get_theme_mod( "semester_break_tagline" )[ get_locale() ] ?? "" ?></h3>
</div>
<?php
/**
 * Load possible events and screenings that take olace during the semester break
 */
$args = array(
	"do_preload"     => false,
	'post_type'      => [ 'movie', 'event' ],
	'posts_per_page' => - 1,
	"meta_key"       => "screening_date",
	"orderby"        => "meta_value_num",
	"order"          => "ASC",
	"meta_query"     => array(
		[
			'key'     => 'screening_date',
			'value'   => time(),
			'compare' => '>=',
		]
	),
	"tax_query"      => array(
		[
			"taxonomy" => "semester",
			"field"    => "id",
			"operator" => "NOT EXISTS",
		]
	)
);

$query = new WP_Query( $args );

if ( $query->have_posts() ):
	?>
    <div class="content pt-2">
		<?= apply_filters( "the_content", get_theme_mod( "semester_break_pre_events_text" )[ get_locale() ] ?? "" ) ?>
    </div>
	<?php
	get_template_part( 'partials/movie-list', args: [
		"posts" => $query->posts,
		"title" => esc_html__( "Intermission Specials", "gegenlicht" )
	] );
	?>
    <div class="content pt-2">
		<?= apply_filters( "the_content", get_theme_mod( "semester_break_post_events_text" )[ get_locale() ] ?? "" ) ?>
		<?= apply_filters( "the_content", get_theme_mod( "semester_break_text" )[ get_locale() ] ?? "" ) ?>
    </div>
<?php endif; ?>
<div class="content pt-2">
	<?= apply_filters( "the_content", get_theme_mod( "semester_break_text" )[ get_locale() ] ?? "" ) ?>
</div>
<div class="content">
    <h3><?= esc_html__( "What we have shown so far", "gegenlicht" ) ?></h3>
    <div class="pt-2">
		<?= apply_filters( "the_content", get_theme_mod( "semester_break_archive_text" )[ get_locale() ] ?? "" ) ?>
		<?php get_template_part( 'partials/button', args: [
			'href'    => get_post_type_archive_link( 'movie' ),
			'content' => __( 'To the Archive', 'gegenlicht' )
		] ) ?>
    </div>
</div>
</main>


