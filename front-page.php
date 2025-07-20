<?php
/**
 * Template Name: Front Page
 */

defined( 'ABSPATH' ) || exit;

$fallbackImage = get_theme_mod( 'anonymous_image' );
$semesterID    = get_theme_mod( 'displayed_semester' );


$next_meta       = [];
$next_meta[]     = [
	'key'     => 'screening_date',
	'value'   => time(),
	'compare' => '>=',
];
$next_query_args = array(
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

$upcoming = [];
if ( $next->have_posts() ) {
	$next->the_post();
	$upcoming[] = $post;

	$followingQuery = new WP_Query( array(
		'post_type'      => [ 'movie', 'event' ],
		'posts_per_page' => - 1,
		'meta_query'     => [
			[
				'key'     => 'screening_date',
				'value'   => [
					(int) rwmb_meta( 'screening_date' ) + 1,
					( (int) strtotime( 'tomorrow', (int) rwmb_meta( 'screening_date' ) ) )
				],
				'compare' => 'BETWEEN',
			]
		],
	) );
	wp_reset_postdata();

	while ( $followingQuery->have_posts() ) {
		$followingQuery->the_post();
		$upcoming[] = $post;
	}
	wp_reset_postdata();

}
get_header();
do_action( 'wp_body_open' );
?>
    <main class="px-2 mb-6 page-content">
        <div class="marquee">
            <div class="marquee-content">
                <p>***&emsp;Semesterpause</p>
                <p>***&emsp;Semesterpause</p>
                <p>***&emsp;Semesterpause</p>
                <p>***&emsp;Semesterpause</p>
                <p>***&emsp;Semesterpause</p>
            </div>
            <div class="marquee-content">
                <p>***&emsp;Semesterpause</p>
                <p>***&emsp;Semesterpause</p>
                <p>***&emsp;Semesterpause</p>
                <p>***&emsp;Semesterpause</p>
                <p>***&emsp;Semesterpause</p>
            </div>
        </div>
		<?php for ( $i = 0; $i < count( $upcoming ); $i ++ ): $post = get_post( $upcoming[ $i ] );
			$showDetails = ( rwmb_meta( 'license_type' ) == 'full' || is_user_logged_in() );
			$title = get_locale() == 'de' ? rwmb_meta( 'german_title' ) : rwmb_meta( 'english_title' );
			?>
            <article class="next-movie <?= $i == count( $upcoming ) - 1 ? 'pb-6' : '' ?>">
                <hr class="separator"/>
                <div class="next-movie-header">
                    <p><?= $i == 0 ? esc_html__( 'Next Screening', 'gegenlicht' ) : esc_html__( 'Afterwards at', 'gegenlicht' ) ?></p>
                    <p class="is-size-6 m-0 p-0"><?= $i == 0 ? date( "d.m.Y | H:i", (int) rwmb_meta( 'screening_date' ) ) : date( "H:i", (int) rwmb_meta( 'screening_date' ) ) ?></p>
                </div>
                <hr class="separator"/>
                <h2 class="title next-movie-title py-4"><?= $showDetails ? $title : esc_html__( 'An unnamed movie', 'gegenlicht' ) ?></h2>
				<?php get_template_part( 'partials/responsive-image', args: [ 'image_url' => $showDetails ? get_the_post_thumbnail_url( size: 'full' ) : wp_get_attachment_image_url( $fallbackImage, 'large' ) ] ) ?>
                <hr class="separator"/>
                <a class="button is-outlined is-size-5 is-fullwidth mt-2"
                   style="padding: 0.75rem 0 !important;" href="<?= get_the_permalink() ?>">
                    <p class="has-text-weight-bold is-uppercase"><?= $post->post_type == 'movie' ? esc_html__( 'To the movie', 'gegenlicht' ) : esc_html__( 'To the event', 'gegenlicht' ) ?></p>
                </a>
            </article>
		<?php endfor;
		wp_reset_postdata(); ?>

		<?php

		$lastDisplayed = end( $upcoming );

		$meta_query   = [];
		$meta_query[] = [
			'key'     => 'screening_date',
			'value'   => (int) rwmb_meta( 'screening_date', post_id: $lastDisplayed->ID ),
			'compare' => '>',
		];
		$programQuery = array(
			'post_type'      => [ 'movie', 'event' ],
			'posts_per_page' => - 1,
			'meta_query'     => $meta_query,
			'meta_key'       => 'screening_date',
			'orderby'        => 'meta_value_num',
			'order'          => 'ASC',
		);

		$query = new WP_Query( $programQuery );

		$monthlyMovies = array();

		while ( $query->have_posts() ): $query->the_post();
			$screeningMonth                     = date( 'F', (int) rwmb_meta( 'screening_date' ) );
			$monthlyMovies[ $screeningMonth ][] = $post;
		endwhile;
		?>

		<?php if ( ! empty( $monthlyMovies ) ) : ?>
            <h1 id="program"
                class="title is-uppercase mb-1"><?= esc_html__( 'Our Semester Program', 'gegenlicht' ) ?></h1>
            <div class="is-flex is-justify-content-space-between is-align-items-center is-clickable"
                 onclick="toggleSpecialProgramDisplay()">
                <div>
                    <p><?= esc_html__( 'Main Program Only', 'gegenlicht' ) ?></p>
                </div>
                <span class="icon is-large">
                <span class="material-symbols" id="programSwitcher"
                      style="font-size: 48px; font-variation-settings: 'wght' 100, 'GRAD' 0, 'opsz' 48; font-weight: 100;">toggle_off</span>
            </span>
            </div>
            <hr class="separator mb-6" style="margin-top: 0.25rem;"/>
			<?php foreach ( $monthlyMovies as $month => $posts ) : ?>
                <div class="movie-list mb-5 is-filterable">
                    <p style="background-color: var(--bulma-body-color); color: var(--bulma-body-background-color)"
                       class="font-ggl is-uppercase is-size-5 py-1 pl-1"><?= esc_html__( $month, 'gegenlicht' ) ?></p>
					<?php foreach ( $posts as $post ) : $post = get_post( $post );
						$programType = rwmb_meta( 'program_type' );
						$specialProgram = rwmb_meta( 'special_program' );
						$showDetails = ( rwmb_meta( 'license_type' ) == 'full' || is_user_logged_in() );
						$title = get_locale() == 'de' ? rwmb_meta( 'german_title' ) : rwmb_meta( 'english_title' ); ?>
                        <a data-program-type="<?= $programType ?>" href="<?= get_permalink() ?>">
                            <div>
                                <time datetime="<?= date( "Y-m-d H:i", rwmb_meta( 'screening_date' ) ) ?>"><p
                                            class="is-size-6 m-0 p-0"><?= date( "d.m.Y | H:i", rwmb_meta( 'screening_date' ) ) ?></p>
                                </time>
                                <p class="is-size-5 has-text-weight-bold is-uppercase"><?= $showDetails ? $title : ( $programType == 'special_program' ? get_term( $specialProgram )->name : esc_html__( 'An unnamed movie', 'gegenlicht' ) ) ?></p>
                            </div>
                            <span class="icon">
                        <span class="material-symbols">arrow_forward_ios</span>
                    </span>
                        </a>
					<?php endforeach; ?>
                </div>
			<?php endforeach; ?>
		<?php endif; ?>

        <a class="button is-outlined is-black is-size-5 is-fullwidth mt-2" style="padding: 0.75rem 0 !important;"
           href="<?= get_post_type_archive_link( 'movie' ) ?>">
            <p class="has-text-weight-bold is-uppercase"><?= esc_html__( 'To the archive', 'gegenlicht' ) ?></p>
        </a>
    </main>
<?php foreach ( get_theme_mod( 'displayed_special_programs' ) as $termID ) : $termID = (int) $termID;
    get_template_part("partials/special-program", args: ["id" => $termID] );
endforeach; ?>

<?php if ( in_array( 'team', get_theme_mod( 'displayed_blocks', [] ) ) ): ?>
    <style>
        #team {
            background-color: <?= get_theme_mod('fp_team_block_color') ?> !important;
            color: <?= get_theme_mod('fp_team_text_color') ?> !important;
            background-clip: padding-box;


            > * {
                color: <?= get_theme_mod('fp_team_text_color') ?> !important;
            }

            .button {
                border-color: <?= get_theme_mod('fp_team_text_color') ?> !important;

                > * {
                    color: <?= get_theme_mod('fp_team_text_color') ?> !important;
                }
            }
        }

        @media (prefers-color-scheme: dark) {
            #team {
                background-color: <?= get_theme_mod('fp_team_block_color_dark') ?> !important;
                color: <?= get_theme_mod('fp_team_text_color_dark') ?> !important;
                background-clip: padding-box;


                > * {
                    color: <?= get_theme_mod('fp_team_text_color_dark') ?> !important;
                }

                .button {
                    border-color: <?= get_theme_mod('fp_team_text_color_dark') ?> !important;

                    > * {
                        color: <?= get_theme_mod('fp_team_text_color_dark') ?> !important;
                    }
                }
            }
        }
    </style>
    <article id="team" class="py-5">
        <div class="page-content content">
			<?php if ( get_theme_mod( 'team_image' ) ):
				get_template_part( 'partials/responsive-image', args: [ 'image_url' => get_theme_mod( 'team_image' ) ] );
			endif; ?>
            <h2><?= esc_html__( 'Who is the GEGENLICHT', 'gegenlicht' ) ?></h2>
            <div>
				<?php
				$introTextRaw = get_theme_mod( 'team_block_text' )[ get_locale() ];
				$paragraphs   = preg_split( "/\R\R/", $introTextRaw, flags: PREG_SPLIT_NO_EMPTY );
				foreach ( $paragraphs as $paragraph ):
					?>
                    <p><?= $paragraph ?? esc_html__( 'Some content is missing here' ) ?></p>
				<?php endforeach; ?>
            </div>
            <a class="button is-outlined is-black is-size-5 is-fullwidth mt-2" style="padding: 0.75rem 0 !important;"
               href="<?= get_post_type_archive_link( 'team-member' ) ?>">
                <p class="has-text-weight-bold is-uppercase"><?= esc_html__( 'To the team', 'gegenlicht' ) ?></p>
            </a>
        </div>
    </article>
<?php endif; ?>

<?php if ( in_array( 'location', get_theme_mod( 'displayed_blocks', [] ) ) ): ?>
    <style>
        #location {

            background-color: <?= get_theme_mod('location_background_color')['light'] ?>;
            color: <?= get_theme_mod('location_text_color')['light'] ?>;
            background-clip: padding-box;


            & > * {
                color: <?= get_theme_mod('location_text_color')['light'] ?>;

            }

            .button {
                --bulma-body-color: <?= get_theme_mod('location_text_color')['light'] ?>;

                > * {
                    color: <?= get_theme_mod('location_text_color')['light'] ?>;
                }
            }
        }

        @media (prefers-color-scheme: dark) {
            #location {
                background-color: <?=get_theme_mod('location_background_color')['dark'] ?> !important;
                color: <?= get_theme_mod('location_text_color')['dark'] ?> !important;
                background-clip: padding-box;


                > * {
                    color: <?= get_theme_mod('location_text_color')['dark'] ?> !important;

                }

                .button {
                    --bulma-body-color: <?= get_theme_mod('location_text_color')['dark'] ?> !important;

                    > * {
                        color: <?= get_theme_mod('location_text_color')['dark'] ?> !important;
                    }
                }
            }
        }
    </style>
    <article class="has-text-primary py-5" id="location">
        <div class="page-content">
            <div class="content">
				<?php if ( get_theme_mod( 'location_map' ) ): ?>
					<?php get_template_part( 'partials/responsive-image', args: [
						'image_url'    => get_theme_mod( 'location_map' ),
						'disable16by9' => true
					] ) ?>
				<?php endif; ?>
                <h2 class="has-text-primary border-is-primary"><?= esc_html__( 'Where is the GEGENLICHT', 'gegenlicht' ) ?></h2>
                <div>
					<?php
					$introTextRaw = get_theme_mod( 'location_text' )[ get_locale() ];
					$paragraphs   = preg_split( "/\R\R/", $introTextRaw, flags: PREG_SPLIT_NO_EMPTY );
					foreach ( $paragraphs as $paragraph ):
						?>
                        <p><?= $paragraph ?? esc_html__( 'Some content is missing here' ) ?></p>
					<?php endforeach; ?>
                </div>
            </div>
            <a class="button is-outlined is-primary is-size-5 is-fullwidth mt-2" style="padding: 0.75rem 0 !important;"
               href="<?= get_page_link( get_theme_mod( 'location_detail_page' ) ?? '#' ) ?>">
                <p class="has-text-weight-bold is-uppercase"><?= esc_html__( 'Read more', 'gegenlicht' ) ?></p>
            </a>
        </div>
    </article>
<?php endif; ?>
<?php if ( in_array( 'cooperations', get_theme_mod( 'displayed_blocks', [] ) ) ): ?>
    <style>
        #cooperations {
            --bulma-body-background-color: <?= get_theme_mod('cooperations_background_color')['light'] ?>;
            --bulma-body-color: <?= get_theme_mod('cooperations_text_color')['light'] ?>;
            background-clip: padding-box;


            & > * {
                color: <?= get_theme_mod('cooperations_text_color')['light'] ?>;

            }

            .button {
                --bulma-body-color: <?= get_theme_mod('cooperations_text_color')['light'] ?>;

                > * {
                    color: <?= get_theme_mod('cooperations_text_color')['light'] ?>;
                }
            }

            .marquee-content {
                animation: scroll 40s linear infinite;
                gap: 2rem;
            }

            figure {

                align-content: space-around;

                svg {
                    object-fit: scale-down;
                    width: 10vw;

                }
            }
        }

        @media (prefers-color-scheme: dark) {
            #cooperations {
                background-color: <?= get_theme_mod('cooperations_background_color')['dark'] ?> !important;
                color: <?= get_theme_mod('cooperations_text_color')['dark'] ?> !important;

                background-clip: padding-box;


                > * {
                    color: <?= get_theme_mod('cooperations_text_color')['dark'] ?> !important;

                }

                .button {
                    --bulma-body-color: <?= get_theme_mod('cooperations_text_color')['dark'] ?> !important;

                    > * {
                        color: <?= get_theme_mod('cooperations_text_color')['dark'] ?> !important;
                    }
                }

            }
        }
    </style>
    <article id="cooperations" class="py-5">
        <div class="page-content content">
			<?php

			$externalsQuery = new WP_Query( [
				'post_type'      => [ 'supporter', 'cooperation-partner' ],
				'posts_per_page' => 6,
				'orderby'        => 'rand'
			] );

			if ( $externalsQuery->have_posts() ) :

				$postImages = array();

				while ( $externalsQuery->have_posts() ) : $externalsQuery->the_post();
					$postImages[] = get_attached_file( attachment_url_to_postid( get_the_post_thumbnail_url() ), true );
				endwhile;
				wp_reset_postdata();


				?>
                <div class="marquee" style="--height: 200px;">
                    <div class="marquee-content" style="height: 200px;">
						<?php foreach ( $postImages as $postImage ) : ?>
                            <figure class="image is-3by4">
								<?= file_get_contents( $postImage ) ?>
                            </figure>
						<?php endforeach; ?>
                    </div>
                    <div class="marquee-content" style="height: 200px;">
						<?php foreach ( $postImages as $postImage ) : ?>
                            <figure class="image is-3by4">
								<?= file_get_contents( $postImage ) ?>
                            </figure>
						<?php endforeach; ?>
                    </div>
                </div>
			<?php endif; ?>
            <h2><?= esc_html__( 'Our Cooperation Partners' ) ?></h2>
            <div>
				<?php
				$introTextRaw = get_theme_mod( 'coop_block_text' )[ get_locale() ];
				$paragraphs   = preg_split( "/\R\R/", $introTextRaw, flags: PREG_SPLIT_NO_EMPTY );
				foreach ( $paragraphs as $paragraph ):
					?>
                    <p><?= $paragraph ?? esc_html__( 'Some content is missing here' ) ?></p>
				<?php endforeach; ?>
            </div>
            <a class="button is-outlined is-primary is-size-5 is-fullwidth mt-2" style="padding: 0.75rem 0 !important;"
               href="<?= get_post_type_archive_link( 'cooperation-partner' ) ?>">
                <p class="has-text-weight-bold is-uppercase"><?= esc_html__( 'To our Cooperations', 'gegenlicht' ) ?></p>
            </a>
            <a class="button is-outlined is-primary is-size-5 is-fullwidth mt-2" style="padding: 0.75rem 0 !important;"
               href="<?= get_post_type_archive_link( 'supporter' ) ?>">
                <p class="has-text-weight-bold is-uppercase"><?= esc_html__( 'See our Supporterss', 'gegenlicht' ) ?></p>
            </a>
        </div>
    </article>
<?php endif; ?>
<?php get_footer(); ?>