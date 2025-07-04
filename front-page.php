<?php

defined( 'ABSPATH' ) || exit;
get_header();
do_action( 'wp_body_open' );

$fallbackImage = get_theme_mod( 'anonymous_image' );

$meta_query   = [];
$meta_query[] = [
	'key'     => 'screening_date',
	'value'   => time(),
	'compare' => '>=',
];
$meta_query[] = [
	'key'     => 'license_type',
	'value'   => [ 'full', 'pool', is_user_logged_in() ? 'none' : null, ],
	'compare' => 'IN',
];

$query_args = array(
	'post_type'  => [ 'movie', 'event' ],
	'meta_query' => $meta_query,
	'meta_key'   => 'screening_date',
	'orderby'    => 'meta_value_num',
	'order'      => 'ASC',
);

$query = new WP_Query( $query_args );
?>
    <main class="pt-2 px-2 mb-6 page-content">
		<?php if ( $query->have_posts() ) : $query->the_post();

			$showDetails    = ( rwmb_meta( 'license_type' ) == 'full' || is_user_logged_in() );
			$title          = get_locale() == 'de' ? rwmb_meta( 'german_title' ) : rwmb_meta( 'english_title' );
			$anonymousImage = get_theme_mod( 'anonymous_image' );

			?>
            <div class="next-movie">
                <hr class="separator"/>
                <div class="next-movie-header">
                    <p><?= esc_html__( 'Next Screening', 'gegenlicht' ) ?></p>
                    <time datetime="<?= date( "Y-m-d H:i", rwmb_meta( 'screening_date' ) ) ?>"><p
                                class="is-size-6 m-0 p-0"><?= date( "d.m.Y | H:i", rwmb_meta( 'screening_date' ) ) ?></p>
                    </time>
                </div>
                <hr class="separator"/>
                <h2 class="title is-uppercase next-movie-title"><?= $showDetails ? $title : esc_html__('An unnamed movie', 'gegenlicht') ?></h2>
                <hr class="separator"/>
                <div>
                    <figure class="image is-hidden-tablet is-4by5 movie-image">
                        <img src="<?= $showDetails ? get_the_post_thumbnail_url( size: 'full' ) : wp_get_attachment_image_url($anonymousImage, 'full') ?>"/>
                    </figure>
                    <figure class="image is-hidden-mobile is-16by9 movie-image">
                        <img src="<?= $showDetails ? get_the_post_thumbnail_url( size: 'full' ) : wp_get_attachment_image_url($anonymousImage, 'full') ?>"/>
                    </figure>
                </div>
                <div>
                    <a class="button is-outlined is-black is-fullwidth mt-2" href="<?= get_the_permalink() ?>">
                        <p class="is-size-4 is-uppercase py-1"><?= esc_html__( 'To the movie' ) ?></p>
                    </a>
                </div>
                <hr class="separator"/>
            </div>
		<?php endif; ?>
        <!-- Display of next movie will be setup later -->
        <h1 class="title is-uppercase"><?= esc_html__( 'Our Semester Program' ) ?></h1>
        <div class="movie-list">
			<?php while ( $query->have_posts() ) : $query->the_post();

				$showDetails    = ( rwmb_meta( 'license_type' ) == 'full' || is_user_logged_in() );
				$title          = get_locale() == 'de' ? rwmb_meta( 'german_title' ) : rwmb_meta( 'english_title' );
            ?>
                <a class="is-flex is-justify-content-space-between is-align-items-center" href="<?= get_permalink() ?>">
                    <div>
                        <time datetime="<?= date( "Y-m-d H:i", rwmb_meta( 'screening_date' ) ) ?>"><p
                                    class="is-size-6 m-0 p-0"><?= date( "d.m.Y | H:i", rwmb_meta( 'screening_date' ) ) ?></p>
                        </time>
                        <p class="is-size-5 has-text-weight-bold is-uppercase"><?= $showDetails ? $title : esc_html__('An unnamed movie', 'gegenlicht') ?></p>
                    </div>
                    <span class="icon">
                        <span class="material-symbols">arrow_forward_ios</span>
                    </span>
                </a>
			<?php endwhile; ?>
        </div>
    </main>

<?php get_footer(); ?>