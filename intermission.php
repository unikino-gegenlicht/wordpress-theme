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
    <div class="movie-list mb-5">
        <p
                class="font-ggl is-uppercase is-size-5 py-1 px-1"
                style="background-color: var(--bulma-body-color); color: var(--bulma-body-background-color)">
			<?= esc_html__( "Intermission Specials", "gegenlicht" ) ?>
        </p>
		<?php while ( $query->have_posts() ) : $query->the_post();
			$showDetails = ( rwmb_meta( 'license_type' ) == 'full' || is_user_logged_in() );
			$programType = rwmb_meta( 'program_type' );
			if ( $programType == "special_program" ) {
				$specialProgram = rwmb_meta( 'special_program' );
			}
			$title = $showDetails ? ( get_locale() == 'de' ? rwmb_meta( 'german_title' ) : rwmb_meta( 'english_title' ) ) : ( $programType == 'special_program' ? get_term( $specialProgram )->name : ( $post->post_type == "movie" ? esc_html__( 'An unnamed movie', 'gegenlicht' ) : esc_html__( 'An unnamed event', 'gegenlicht' ) ) );
			?>
            <a href="<?= get_permalink() ?>"
               role="link"
               aria-label="<?= $title ?>. <?= esc_html__( 'Screening starts: ', 'gegenlicht' ) ?> <?= date( "r", rwmb_meta( 'screening_date' ) ) ?>"
            >
                <div>
                    <p class="is-size-6 m-0 p-0">
                        <time datetime="<?= date( "Y-m-d H:i", rwmb_meta( 'screening_date' ) ) ?>">
							<?= date( GGL_LIST_DATETIME, rwmb_meta( 'screening_date' ) ) ?>
                        </time>
                    </p>
                    <h2 class="is-size-5 has-text-weight-bold is-uppercase no-separator"><?= $title ?></h2>
                </div>

                <span class="icon">
                        <span class="material-symbols">arrow_forward_ios</span>
                    </span>
            </a>
		<?php endwhile;
		wp_reset_postdata(); ?>
    </div>
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


