<?php
defined( 'ABSPATH' ) || exit;

$taxonomy = get_queried_object();
get_header(args: ["title" => $taxonomy->name]);
do_action( 'wp_body_open' );


$anonymousImage      = get_term_meta( $taxonomy->term_id, 'anonymous_image', true );
$backgroundColor     = get_term_meta( $taxonomy->term_id, 'background_color', true );
$textColor           = get_term_meta( $taxonomy->term_id, 'text_color', true );
$backgroundColorDark = get_term_meta( $taxonomy->term_id, 'dark_background_color', true );
$textColorDark       = get_term_meta( $taxonomy->term_id, 'dark_text_color', true );

$logo = get_term_meta( $taxonomy->term_id, 'logo', true );
$logoDark = get_term_meta( $taxonomy->term_id, 'logo_dark', true );
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
<main class="page-content mt-4">

    <article class="content">
        <header class="has-text-centered">
            <picture>
                <source srcset="<?= wp_get_attachment_image_url( $logo, 'full' ); ?>)">
                <source srcset="<?= wp_get_attachment_image_url( $logoDark, 'full' ); ?>)" media="(prefers-color-scheme: dark)">
                <img style="max-height: 300px" height="300" src="<?= wp_get_attachment_image_url($logo) ?>">
            </picture>
            <h1 class="is-size-2 has-text-left"><?= $taxonomy->name ?></h1>
        </header>
		<?php
		echo apply_filters("the_content", $taxonomy->description);
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
                <p class="mt-2 has-text-weight-bold font-ggl is-uppercase is-size-5"><?= $post->post_title ?></p>
			<?php endwhile; ?>
        </article>
	<?php else: ?>
        <article class="mt-6">
            <?= apply_filters("the_content", get_theme_mod( 'special_program_anonymous_explainer' )[get_locale()] ?? ""); ?>
        </article>
    <?php endif; ?>
</main>