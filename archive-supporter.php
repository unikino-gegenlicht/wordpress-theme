<?php

defined( 'ABSPATH' ) || exit;
get_header();
do_action( 'wp_body_open' );
?>

    <article class="page-content">
        <div class="content">
            <h1 class="is-size-3 is-uppercase"><?= esc_html__( 'You want to support us?', 'gegenlicht' ) ?></h1>
			<?php
			$raw        = get_theme_mod( 'supporter_page_text' )[ get_locale() ] ?? "Some content is missng";
			$paragraphs = preg_split( "/\R\R/", $raw, flags: PREG_SPLIT_NO_EMPTY );
			foreach ( $paragraphs as $paragraph ) :
				?>
                <p>
					<?= $paragraph ?>
                </p>
			<?php
			endforeach;
			?>
        </div>
    </article>
    <article class="mt-5"
             style="--bulma-body-color: var(--bulma-primary); --bulma-body-background-color: black; color: var(--bulma-primary); background-color: black;">
        <div class="page-content pb-5">
			<?php if ( get_theme_mod( 'association_logo' ) ): ?>
                <figure class="image py-6" style="text-align: center;">
					<?php
					$slement = file_get_contents( get_attached_file( get_theme_mod( 'association_logo' ), true ) );
					echo $slement;
					?>
                </figure>
                <div class="content">
                    <h2 class="is-size-3 is-uppercase"><?= esc_html__( 'The Association', 'gegenlicht' ) ?></h2>
					<?php
					$raw        = get_theme_mod( 'association_text' )[ get_locale() ] ?? "Some content is missng";
					$paragraphs = preg_split( "/\R\R/", $raw, flags: PREG_SPLIT_NO_EMPTY );
					foreach ( $paragraphs as $paragraph ) :
						?>
                        <p>
							<?= $paragraph ?>
                        </p>
					<?php
					endforeach;

					get_template_part( 'partials/button', args: [
						'href'     => get_theme_mod( 'association_url' ),
						'content'  => __( 'To the Website', 'gegenlicht' ),
						'external' => true,
					] );
					?>
                </div>
			<?php endif; ?>
        </div>
    </article>
    <main class="page-content mb-6">
		<?php

		$query = new WP_Query( [
			'post_type'      => 'supporter',
			'posts_per_page' => - 1,
			'orderby'        => 'title',
			'order'          => 'ASC',
		] );

		while ( $query->have_posts() ) : $query->the_post();
			?>

            <article id="<?= basename( get_post_permalink() ) ?>" class="content my-6">
                <header>
                    <figure class="image supporter-logo">
                        <img src="<?= get_the_post_thumbnail_url( size: 'full' ) ?>" decoding="async" loading="lazy"/>
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


		<?php endwhile; ?>
    </main>

<?php get_footer(); ?>