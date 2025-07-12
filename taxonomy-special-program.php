<?php
/**
 * Template Name: Special Program Detail Page
 */
defined( 'ABSPATH' ) || exit;


get_header();
do_action( 'wp_body_open' );

$taxonomy = get_queried_object();

$anonymousImage      = get_term_meta( $taxonomy->term_id, 'anonymous_image', true );
$backgroundColor     = get_term_meta( $taxonomy->term_id, 'background_color', true );
$textColor           = get_term_meta( $taxonomy->term_id, 'text_color', true );
$backgroundColorDark = get_term_meta( $taxonomy->term_id, 'dark_background_color', true );
$textColorDark       = get_term_meta( $taxonomy->term_id, 'dark_text_color', true );
?>
<style>
    :root {
        --bulma-body-background-color: <?= $backgroundColor ?> !important;
        --bulma-body-color: <?= $textColor ?> !important;
    }

    .navbar {
        background-color: var(--bulma-body-background-color) !important;
    }

    a.navbar-item {
        color: var(--bulma-body-color) !important;
    }

    @media (prefers-color-scheme: dark) {
        :root {
            --bulma-body-background-color: <?= $backgroundColorDark ?> !important;
            --bulma-body-color: <?= $textColorDark ?> !important;
        }
    }
</style>
<main class="page-content">

    <article class="content">
        <header>
            <h1 class="is-size-2"><?= $taxonomy->name ?></h1>
        </header>
		<?php
		$paragraphs = preg_split( "/\R\R/", $taxonomy->description );
		foreach ( $paragraphs as $paragraph ) :
			?>
            <p>
				<?= $paragraph ?>
            </p>
		<?php
		endforeach;
		?>
    </article>
	<?php if ( is_user_logged_in() ) : ?>
        <article class="mt-6">
            <header>
                <h2 class="is-size-3"><?= esc_html__( 'The program hosted', 'gegenlicht' ) ?></h2>
            </header>
			<?php
			$query = new WP_Query( array(
				'post_type'      => [ 'movie' ],
				'posts_per_page' => - 1,
				'tax_query'      => array(
					array(
						'taxonomy' => 'special-program',
						'terms'    => $taxonomy->term_id,
					)
				)
			) );
			while ( $query->have_posts() ) : $query->the_post(); ?>
                <hr class="separator"/>
                <p class="mt-2 has-text-weight-bold font-ggl is-uppercase is-size-5"><?= $post->post_title ?></p>
			<?php endwhile; ?>
            <hr class="separator"/>
        </article>
	<?php endif; ?>
</main>