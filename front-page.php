<?php

defined( 'ABSPATH' ) || exit;

$fallbackImage = get_theme_mod( 'anonymous_image' );
$semesterID = get_theme_mod('displayed_semester');

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
    <main class="pt-2 px-2 mb-6 page-content">
		<?php for ( $i = 0; $i < count( $upcoming ); $i ++ ): $post = get_post( $upcoming[ $i ] );
			$showDetails = ( rwmb_meta( 'license_type' ) == 'full' || is_user_logged_in() );
			$title = get_locale() == 'de' ? rwmb_meta( 'german_title' ) : rwmb_meta( 'english_title' );
			?>
            <article class="next-movie <?= $i == count( $upcoming ) - 1 ? 'pb-6' : '' ?>">
                <hr class="separator"/>
                <div class="next-movie-header">
                    <p><?= $i == 0 ? esc_html__( 'Next Screening', 'gegenlicht' ) : esc_html__( 'Afterwards at', 'gegenlicht' ) ?></p>
                    <p class="is-size-6 m-0 p-0"><?= $i == 0 ? date( "d.m.Y | H:i", (int) rwmb_meta( 'screening_date' ) ) : date( "H:i", (int) rwmb_meta( 'screening_date' )) ?></p>
                </div>
                <hr class="separator"/>
                <h2 class="title next-movie-title"><?= $showDetails ? $title : esc_html__( 'An unnamed movie', 'gegenlicht' ) ?></h2>
                <hr class="separator"/>
                <figure class="image is-hidden-tablet is-4by5 movie-image">
                    <img fetchpriority="high"
                         src="<?= $showDetails ? get_the_post_thumbnail_url( size: 'full' ) : wp_get_attachment_image_url( $fallbackImage, 'large' ) ?>"/>
                </figure>
                <figure class="image is-hidden-mobile is-16by9 movie-image">
                    <img fetchpriority="high"
                         src="<?= $showDetails ? get_the_post_thumbnail_url( size: 'full' ) : wp_get_attachment_image_url( $fallbackImage, 'large' ) ?>"/>
                </figure>
                <hr class="separator"/>
                <a class="button is-outlined is-black is-fullwidth mt-2" href="<?= get_the_permalink() ?>">
                    <p class="is-size-4 is-uppercase py-1"><?= esc_html__( 'To the movie' ) ?></p>
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
			'posts_per_page' => -1,
			'meta_query'     => $meta_query,
			'meta_key'       => 'screening_date',
			'orderby'        => 'meta_value_num',
			'order'          => 'ASC',
		);

		$query = new WP_Query( $programQuery );

        $monthlyMovies = array();

        while ( $query->have_posts()): $query->the_post();
            $screeningMonth = date('F', (int) rwmb_meta( 'screening_date' ) );
            $monthlyMovies[$screeningMonth][] = $post;
        endwhile;
        ?>

		<?php if ( !empty($monthlyMovies) ) : ?>
        <h1 class="title is-uppercase pb-0 mb-0"><?= esc_html__( 'Our Semester Program' ) ?></h1>
        <hr class="separator" style="margin-bottom: 0.25rem;"/>
        <div class="is-flex is-justify-content-space-between is-align-items-center is-clickable" onclick="toggleSpecialProgramDisplay()">
            <div>
                <p><?= esc_html__( 'Main Program Only', 'gegenlicht' ) ?></p>
            </div>
            <span class="icon is-large">
                <span class="material-symbols" id="programSwitcher" style="font-size: 48px; font-variation-settings: 'wght' 100, 'GRAD' 0, 'opsz' 48;">toggle_off</span>
            </span>
        </div>
        <hr class="separator mb-6" style="margin-top: 0.25rem;"/>
        <?php foreach ( $monthlyMovies as $month => $posts ) : ?>
            <div class="movie-list mb-5">
                <p class="has-background-black has-text-primary font-ggl is-uppercase is-size-5 py-1 pl-1"><?= esc_html__($month, 'gegenlicht') ?></p>
                <?php foreach ( $posts as $post ) : $post = get_post( $post );
	                $programType = rwmb_meta( 'program_type' );
                    $specialProgram = rwmb_meta('special_program');
	                $showDetails = ( rwmb_meta( 'license_type' ) == 'full' || is_user_logged_in() );
	                $title       = get_locale() == 'de' ? rwmb_meta( 'german_title' ) : rwmb_meta( 'english_title' ); ?>
                    <a data-program-type="<?= $programType ?>" href="<?= get_permalink() ?>">
                        <div>
                            <time datetime="<?= date( "Y-m-d H:i", rwmb_meta( 'screening_date' ) ) ?>"><p
                                        class="is-size-6 m-0 p-0"><?= date( "d.m.Y | H:i", rwmb_meta( 'screening_date' ) ) ?></p>
                            </time>
                            <p class="is-size-5 has-text-weight-bold is-uppercase"><?= $showDetails ? $title : ($programType == 'special_program' ? get_term($specialProgram)->name : esc_html__( 'An unnamed movie', 'gegenlicht' )) ?></p>
                        </div>
                        <span class="icon">
                        <span class="material-symbols">arrow_forward_ios</span>
                    </span>
                    </a>
                <?php endforeach; ?>
            </div>
        <?php endforeach; ?>
		<?php endif; ?>

    </main>
<?php foreach ( get_option( 'displayed_special_programs' ) as $termID => $show ) : if ( ! $show ) {
	continue;
} ?>
    <style>
        #special-program_<?= $termID ?> {
            background-color: <?= get_term_meta( $termID, 'background_color', true ) ?>;
            color: <?= get_term_meta( $termID, 'text_color', true ) ?>;
            padding-top: 2rem;

            div.page-content {
                margin: 0 auto !important;
            }

            > div > hr.separator {
                border: 1px solid <?= get_term_meta( $termID, 'text_color', true ) ?> !important;
                background-color: <?= get_term_meta( $termID, 'text_color', true ) ?> !important;
            }
        }

        @media (prefers-color-scheme: dark) {
            #special-program_<?= $termID ?> {
                background-color: <?= get_term_meta( $termID, 'dark_background_color', true ) ?>;
                color: <?= get_term_meta( $termID, 'dark_text_color', true ) ?>;

                div.page-content {
                    margin: 0 auto !important;
                }

                > div > hr.separator {
                    border: 1px solid <?= get_term_meta( $termID, 'dark_text_color', true ) ?> !important;
                    background-color: <?= get_term_meta( $termID, 'dark_text_color', true ) ?> !important;
                }
            }
        }
    </style>
    <article id="special-program_<?= $termID ?>">
        <div class="page-content">
            <figure class="figure pb-4" style="text-align: center;">
                <picture style="object-position: center;">
                    <source width="500"
                            srcset="<?= wp_get_attachment_image_srcset( get_term_meta( $termID, 'logo_dark', true ), 'full' ) ?>"
                            media="(prefers-color-scheme: dark)"/>
                    <source width="500"
                            srcset="<?= wp_get_attachment_image_srcset( get_term_meta( $termID, 'logo', true ), 'full' ) ?>"/>
                    <img src="<?= wp_get_attachment_image_url( get_term_meta( $termID, 'logo', true ), 'full' ) ?>"/>
                </picture>
            </figure>
            <hr class="separator"/>
            <h2 class="is-size-3"><?= get_term( $termID )->name ?></h2>
        </div>
    </article>
<?php endforeach; ?>

<?php get_footer(); ?>