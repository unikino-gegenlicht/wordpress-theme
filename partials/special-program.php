<?php
$specialProgramID = $args['id'] ?? null;
$semesterID       = $args['semester'] ?? null;
?>
<style>
    #special-program-<?= $specialProgramID ?> {
        --bulma-body-background-color: <?= get_term_meta($specialProgramID, 'background_color', true) ?? "inherit" ?>;
        --bulma-body-color: <?= get_term_meta($specialProgramID, 'text_color', true) ?? "inherit" ?>;
        --bulma-link-text: var(--bulma-body-color) !important;

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
    <div class="page-content">
        <header class="content">
            <a href="<?= get_term_link( $specialProgramID, 'special-program' ) ?>"
               aria-label="<?= esc_html__( 'Show details of', 'gegenlicht' ) . ' ' . get_term( $specialProgramID )->name ?>">
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
        <div class="content">
            <?= apply_filters("the_content", get_term( (int) $specialProgramID )->description) ?>
        </div>
		<?php
		$query = new WP_Query( array(
			'post_type'      => [ 'movie', 'event' ],
			'posts_per_page' => - 1,
			'tax_query'      => array(
				'relation' => "AND",
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
					'value' => 'special_program',
				],
				[
					"key"     => "screening_date",
					"value"   => time(),
					"compare" => ">=",
				]
			)
		) );
		?>

		<?php if ( ! $query->have_posts() ) : ?>
            <p class="is-italic"><?= esc_html__( 'Sadly, all movies of this special program have been screened for the current semester. Check back next semester.', "gegenlicht" ) ?></p>
		<?php
		else:
			get_template_part("partials/movie-list", args: ["posts" => $query->posts, "allowFiltering" => false] );
		endif; ?>
    </div>
</article>