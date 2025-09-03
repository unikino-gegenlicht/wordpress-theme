<?php
defined( 'ABSPATH' ) || exit;
define( "DONOTCACHEPAGE", true );


$fallbackImage = get_theme_mod( 'anonymous_image' );
$semesterID    = get_theme_mod( 'displayed_semester' );
$upcoming      = ggl_get_advertisements( $semesterID );


get_header();

?>
    <main class="px-2 mb-6 page-content">
<?php if ( empty( $upcoming ) || GGL_SEMESTER_BREAK || ( defined( "GGL_ANNOUNCE_NEW_PROGRAM" ) && GGL_ANNOUNCE_NEW_PROGRAM ) ):
	get_template_part( "intermission" );
	?>
    <div class="page-content">
        <hr class="separator">
    </div>
<?php
else:
	for ( $i = 0; $i < count( $upcoming ); $i ++ ):
		get_template_part( "partials/movie-advertisement", args: [
			"post_id"  => $upcoming[ $i ]->ID,
			"last"     => $i == ( count( $upcoming ) - 1 ),
			"followUp" => $i != 0
		] );
	endfor;
	wp_reset_postdata(); ?>

	<?php

	$lastDisplayed                   = end( $upcoming );
	$lastAdvertisementScreeningStart = (int) rwmb_get_value( "screening_date", post_id: $lastDisplayed->ID );

	while ( have_posts() ): the_post();
		if ( ( (int) rwmb_get_value( "screening_date" ) ) <= $lastAdvertisementScreeningStart ) {
			continue;
		}
		$screeningMonth                     = date( 'F', (int) rwmb_get_value( 'screening_date' ) );
		$monthlyMovies[ $screeningMonth ][] = $post;
	endwhile;
	?>

    <h1 id="program"
        class="title is-uppercase mb-1"><?= esc_html__( 'Our Semester Program', 'gegenlicht' ) ?></h1>

	<?php if ( ! empty( $monthlyMovies ) ) : ?>

    <div class="is-flex is-justify-content-space-between is-align-items-center">
        <div>
            <p><?= esc_html__( 'Main Program Only', 'gegenlicht' ) ?></p>
        </div>
        <span class="icon is-large  is-clickable" role="button"
              onclick="toggleSpecialProgramDisplay()">
                <span class="material-symbols" id="programSwitcher"
                      style="font-size: 48px; font-variation-settings: 'wght' 100, 'GRAD' 0, 'opsz' 48; font-weight: 100;">toggle_off</span>
            </span>
    </div>
    <hr class="separator mb-6" style="margin-top: 0.25rem;"/>
	<?php foreach ( $monthlyMovies as $month => $posts ) : get_template_part( "partials/movie-list", args: [
		"title" => esc_html__( $month, 'gegenlicht' ),
		"posts" => $posts
	] ) ?>
	<?php endforeach; ?>
<?php else: ?>
    <div class="content">
		<?= apply_filters( "the_content", get_theme_mod( "semester_break_premonition_text" )[ get_locale() ] ?? "" ) ?>
    </div>
<?php endif; ?>
	<?php get_template_part( 'partials/button', args: [
	'href'    => get_post_type_archive_link( 'movie' ),
	'content' => __( 'To the Archive', 'gegenlicht' )
] ) ?>
    </main>
	<?php foreach ( get_theme_mod( 'displayed_special_programs' ) as $termID ) : $termID = (int) $termID;
	get_template_part( "partials/special-program", args: [ "id" => $termID, "semester" => $semesterID ] );
endforeach; ?>
<?php endif; ?>
<?php if ( in_array( 'team', get_theme_mod( 'displayed_blocks', [] ) ) ): ?>
    <style>
        #team {
            --bulma-body-color: <?= get_theme_mod('teamBlock_text_color')['light'] ?? 'inherit' ?> !important;
            --bulma-body-background-color: <?= get_theme_mod('teamBlock_background_color')['light'] ?? 'inherit' ?> !important;

            background-color: var(--bulma-body-background-color) !important;

            color: var(--bulma-body-color) !important;
            background-clip: padding-box;


            > * {
                color: var(--bulma-body-color) !important;
            }

            .button {
                border-color: var(--bulma-body-color) !important;

                > * {
                    color: var(--bulma-body-color) !important;
                }
            }
        }

        @media (prefers-color-scheme: dark) {
            #team {
                --bulma-body-color: <?= get_theme_mod('teamBlock_text_color')['dark'] ?? 'inherit' ?> !important;
                --bulma-body-background-color: <?= get_theme_mod('teamBlock_background_color')['dark'] ?? 'inherit' ?> !important;
            }
        }
    </style>
    <article id="team" class="py-5">
        <div class="page-content content">
			<?php if ( get_theme_mod( 'team_block_image' ) ):
				get_template_part( 'partials/responsive-image', args: [
					'image_url'        => wp_get_attachment_image_url( get_theme_mod( 'team_block_image' ), 'desktop' ),
					'mobile_image_url' => wp_get_attachment_image_url( get_theme_mod( 'team_block_image' ), 'mobile' )
				] );
			endif; ?>
            <h2><?= get_theme_mod( "team_block_title" )[ get_locale() ] ?? "Please set this value!" ?></h2>
			<?php
			$raw = get_theme_mod( 'team_block_text' )[ get_locale() ] ?? "";
			echo apply_filters( "the_content", $raw );
			?>
			<?php get_template_part( 'partials/button', args: [
				'href'    => get_post_type_archive_link( 'team-member' ),
				'content' => __( 'To the team', 'gegenlicht' )
			] ) ?>
        </div>
    </article>
    <div class="page-content">
        <hr class="separator is-only-darkmode"/>
    </div>
<?php endif; ?>

<?php if ( in_array( 'location', get_theme_mod( 'displayed_blocks', [] ) ) ): ?>
    <style>
        #location {

            --bulma-body-background-color: <?= get_theme_mod('location_block_bg_color')['light'] ?? "inherit" ?>;
            --bulma-body-color: <?= get_theme_mod('location_block_text_color')['light'] ?? "inherit" ?>;

            color: var(--bulma-body-color) !important;
            background-color: var(--bulma-body-background-color) !important;

            background-clip: padding-box;


            > * {
                color: var(--bulma-body-color) !important;
            }

            .button {
                border-color: var(--bulma-body-color) !important;

                > * {
                    color: var(--bulma-body-color) !important;
                }
            }
        }

        @media (prefers-color-scheme: dark) {
            #location {
                --bulma-body-color: <?= get_theme_mod('location_block_text_color')['dark'] ?? 'inherit' ?> !important;
                --bulma-body-background-color: <?= get_theme_mod('location_block_bg_color')['dark'] ?? 'inherit' ?> !important;
            }
        }
    </style>
    <article id="location" class="py-5">
        <div class="page-content content">
            <div class="">
				<?php if ( get_theme_mod( 'location_block_image' ) ): ?>
					<?php get_template_part( 'partials/responsive-image', args: [
						'image_url'    => wp_get_attachment_image_url( get_theme_mod( 'location_block_image' ), 'full' ),
						'disable16by9' => true,
						'style'        => 'object-position: 15%;'
					] ) ?>
				<?php endif; ?>
                <h2><?= get_theme_mod( "location_block_title" )[ get_locale() ] ?? "Content Not Set" ?></h2>
				<?php
				$raw = get_theme_mod( 'location_block_text' )[ get_locale() ] ?? "";
				echo apply_filters( "the_content", $raw );
				?>
				<?php
				get_template_part( "partials/button", args: [
					"href"     => get_page_link( get_theme_mod( 'location_detail_page' ) ?? '#' ),
					"content"  => esc_html__( 'To our Location', 'gegenlicht' ),
					"external" => false
				] );
				?>
            </div>

        </div>
    </article>
<?php endif; ?>
<div class="page-content">
    <hr class="separator is-only-darkmode"/>
</div>
<?php if ( in_array( 'cooperations', get_theme_mod( 'displayed_blocks', [] ) ) ): ?>
    <style>
        #cooperations {
            --bulma-body-background-color: <?= get_theme_mod('cooperations_background_color')['light'] ?? 'inherit' ?>;
            --bulma-body-color: <?= get_theme_mod('cooperations_text_color')['light'] ?? 'inherit' ?>;


            color: var(--bulma-body-color) !important;
            background-color: var(--bulma-body-background-color) !important;

            background-clip: padding-box;


            & > * {
                color: var(--bulma-body-color) !important;

            }

            .marquee-content {
                animation: scroll 60s linear infinite;
                gap: 1.5rem;
            }

            figure {
                align-content: space-around;
            }
        }

        @media (prefers-color-scheme: dark) {
            #cooperations {
                --bulma-body-background-color: <?= get_theme_mod('cooperations_background_color')['dark'] ?? 'inherit' ?>;
                --bulma-body-color: <?= get_theme_mod('cooperations_text_color')['dark'] ?? 'inherit' ?>;
            }
        }
    </style>
    <article id="cooperations" class="py-5">
        <div class="page-content">
			<?php

			$externalsQuery = new WP_Query( [
				'post_type'  => [ 'supporter', 'cooperation-partner' ],
				'orderby'    => 'rand',
				"do_preload" => false,
			] );

			if ( $externalsQuery->have_posts() ) :

				$postImages = array();

				while ( $externalsQuery->have_posts() ) : $externalsQuery->the_post();
					get_post( $externalsQuery->post );
					if ( ! rwmb_meta( "supporter_display_first" ) ) {
                        if (count($postImages) > 6) {
                            break;
                        }
						$postImages[] = [get_the_post_thumbnail_url(size: "full"), get_permalink()];
					}
				endwhile;
				wp_reset_postdata();
				?>
                <div role="marquee" aria-live="off"
                     aria-label="<?= esc_html__( 'Logos of ours Cooperation partners', 'gegenlicht' ) ?>"
                     class="marquee" style="--height: 200px;">
                    <div class="marquee-content is-align-items-center" style="height: 200px;">
						<?php foreach ( $postImages as $postImage ) : ?>
                            <a href="<?= $postImage[1] ?>">
                                <figure class="image marquee-image">
                                    <img src="<?= $postImage[0] ?>"/>
                                </figure>
                            </a>
						<?php endforeach; ?>
                    </div>
                    <div class="marquee-content" style="height: 200px;">
						<?php foreach ( $postImages as $postImage ) : ?>
                        <a href="<?= $postImage[1] ?>">
                            <figure class="image marquee-image">
								<img src="<?= $postImage[0] ?>"/>
                            </figure>
                        </a>
						<?php endforeach; ?>
                    </div>
                </div>
			<?php endif; ?>
            <div class="content">
                <h2><?= esc_html__( 'Our Cooperation Partners' ) ?></h2>
                <p>
					<?php
					$raw = get_theme_mod( 'coop_block_text' )[ get_locale() ] ?? "";
					echo apply_filters( "the_content", $raw );
					?>
                <p>
					<?php get_template_part( 'partials/button', args: [
						'href'    => get_post_type_archive_link( 'cooperation-partner' ),
						'content' => __( 'Our Cooperation Partners', 'gegenlicht' )
					] ) ?>
                </p>
                <p>
					<?php get_template_part( 'partials/button', args: [
						'href'    => get_post_type_archive_link( 'supporter' ),
						'content' => __( 'Our supporters', 'gegenlicht' ),
					] ) ?>
                </p>
            </div>

        </div>
    </article>
<?php endif; ?>
<?php get_footer(); ?>