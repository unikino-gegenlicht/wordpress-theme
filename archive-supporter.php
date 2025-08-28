<?php

defined( 'ABSPATH' ) || exit;
get_header();
do_action( 'wp_body_open' );
?>

    <article class="page-content">
        <div class="content">
            <h1 class="is-size-3 is-uppercase"><?= get_theme_mod( "supporter_page_title" )[ get_locale() ] ?? "Configure Me!" ?></h1>
			<?php
			$raw = get_theme_mod( 'supporter_page_text' )[ get_locale() ] ?? "Some content is missing";
			echo apply_filters( "the_content", $raw );
			?>
        </div>
    </article>
    <main class="mb-6">
		<?php
        while ( have_posts() ) : the_post();
			if ( rwmb_meta( "supporter_display_first" ) ) {
				$displayFirst[] = $post;
			} else {
				$others[] = $post;
			}
		endwhile;

		foreach ( $displayFirst as $post ) :
			setup_postdata( $post );
			?>
            <div class="my-6 pb-5"
                 style="--bulma-body-color: var(--bulma-primary); --bulma-body-background-color: black; color: var(--bulma-primary); background-color: black;">
                <article id="<?= basename( get_post_permalink() ) ?>" class="page-content content pb-5">
                    <header>
                        <figure class="has-text-centered py-6">
                            <img style="max-height: 250px; object-fit: scale-down" src="<?= get_the_post_thumbnail_url( size: 'full' ) ?>" decoding="async"
                                 loading="lazy"/>
                        </figure>
                        <h2 class="mt-2"><?php the_title() ?></h2>
                    </header>
					<?php the_content();


					get_template_part( 'partials/button', args: [
						'href'     => rwmb_the_value( 'supporter_website', echo: false ),
						'content'  => __( 'To the Website', 'gegenlicht' ),
						'external' => true
					] );
					?>
                </article>
            </div>
		<?php endforeach; ?>
		<?php foreach ( $others as $post ) : setup_postdata( $post ); ?>
            <article id="<?= basename( get_post_permalink() ) ?>" class="page-content content my-6">
                <header>
                    <a href="<?= get_post_permalink() ?>"> <figure class="image supporter-logo">
                        <img height="150" src="<?= get_the_post_thumbnail_url( size: 'full' ) ?>" decoding="async" loading="lazy"/>
                    </figure></a>
                    <h2 class="mt-2"><?php the_title() ?></h2>
                </header>
				<?php the_content();


				get_template_part( 'partials/button', args: [
					'href'     => rwmb_the_value( 'supporter_website', echo: false ),
					'content'  => __( 'To the Website', 'gegenlicht' ),
					'external' => true
				] );
				?>
            </article>
		<?php endforeach; ?>
    </main>

<?php get_footer(); ?>