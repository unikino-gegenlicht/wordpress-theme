<?php
$specialProgramID = $args['id'] ?? null;
$semesterID = $args['semester'] ?? null;
?>
<style>
    #special-program-<?= $specialProgramID ?> {
        --bulma-body-background-color: <?= get_term_meta($specialProgramID, 'background_color', true) ?? "inherit" ?>;
        --bulma-body-color: <?= get_term_meta($specialProgramID, 'text_color', true) ?? "inherit" ?>;

        background-color: var(--bulma-body-background-color);
        color: var(--bulma-body-color);
        border-color: var(--bulma-body-color) !important;

        background-clip: padding-box;

        .page-content {
            margin: 0 auto !important;
        }

        .movie-list {
            border-width: var(--border-thickness) 0;
        }

        .image {
            text-align: center !important;

            picture {
                object-position: center !important;
                object-fit: scale-down !important;
            }

            img {
                max-height: 125px !important;
                object-fit: scale-down !important;
            }
        }
    }

    @media (prefers-color-scheme: dark) {
        #special-program-<?= $specialProgramID ?> {
            --bulma-body-background-color: <?= get_term_meta($specialProgramID, 'dark_background_color', true) ?? "inherit" ?> !important;
            --bulma-body-color: <?= get_term_meta($specialProgramID, 'dark_text_color', true) ?? "inherit" ?> !important;
        }
    }
</style>
<article id="special-program-<?= $specialProgramID ?>" class="py-5">
    <div class="page-content content">
        <header>
            <a href="<?= get_term_link( $specialProgramID, 'special-program' ) ?>" aria-label="<?= esc_html__('Show details of', 'gegenlicht') . ' '. get_term( $specialProgramID )->name ?>">
                <figure class="image mb-6">
                    <picture>
                        <source
                                srcset="<?= wp_get_attachment_image_srcset( get_term_meta( $specialProgramID, 'logo_dark', true ), 'full' ) ?>"
                                media="(prefers-color-scheme: dark)"/>
                        <source
                                srcset="<?= wp_get_attachment_image_srcset( get_term_meta( $specialProgramID, 'logo', true ), 'full' ) ?>"/>

                        <img alt=""
                             src=""/>
                    </picture>
                </figure>
            </a>
        </header>
		<?php
		$query = new WP_Query( array(
			'post_type'      => [ 'movie', 'event' ],
			'posts_per_page' => - 1,
			'tax_query'      => array(
				array(
					'taxonomy' => 'semester',
					'terms'    => $semesterID,
				),
				array(
					'taxonomy' => 'special-program',
					'terms'    => $specialProgramID,
				),
			),
			'meta_key'       => 'screening_date',
			'orderby'        => 'meta_value_num',
			'order'          => 'ASC',
			'meta_query'     => array(
				[
					'key'   => 'program_type',
					'value' => 'special',
				]
			)
		) );
		?>

		<?php if ( ! $query->have_posts() ) :
			$paragraphs = preg_split( "/\R\R/", get_term( (int) $specialProgramID )->description );
			foreach ( $paragraphs as $paragraph ) :
				?>
                <p>
					<?= $paragraph ?>
                </p>
			<?php
			endforeach;
			?>
            <p class="is-italic"><?= esc_html__( 'Sadly, all movies of this special program have been screened for the current semester. Check back next semester.' ) ?></p>

		<?php
		else: while ( $query->have_posts() ) : $query->the_post();
			$showDetails = ( rwmb_meta( 'license_type' ) == 'full' || is_user_logged_in() );
			$title       = get_locale() == 'de' ? rwmb_meta( 'german_title' ) : rwmb_meta( 'english_title' );

			?>
            <a href="<?= get_permalink() ?>" aria-label="<?= $showDetails ? $title : get_term( $specialProgramID )->name ?>">
                <div>
                    <time datetime="<?= date( "Y-m-d H:i", rwmb_meta( 'screening_date' ) ) ?>"><p
                                class="is-size-6 m-0 p-0"><?= date( "d.m.Y | H:i", rwmb_meta( 'screening_date' ) ) ?></p>
                    </time>
                    <p class="is-size-5 has-text-weight-bold is-uppercase"><?= $showDetails ? $title : get_term( $specialProgramID )->name ?></p>
                </div>
                <span class="icon">
                        <span class="material-symbols">arrow_forward_ios</span>
            </a>
		<?php endwhile; endif; ?>
    </div>
</article>