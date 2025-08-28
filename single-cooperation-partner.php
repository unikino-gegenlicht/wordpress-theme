<?php
defined( 'ABSPATH' ) || exit;

get_header();
?>
<main class="page-content mt-4">
    <article>
        <div class="is-flex is-align-items-top is-flex-wrap-wrap is-gap-1 mt-3">
            <figure class="image is-3by4 member-picture">
                <img alt=""
                     src="<?= get_the_post_thumbnail_url( size: 'full' ) ?: wp_get_attachment_image_url( get_theme_mod( 'member_fallback_image' ), 'full' ) ?>"
                     style="object-fit: scale-down; border: var(--bulma-body-color) solid var(--border-thickness)"/>
            </figure>
            <header class="is-flex-grow-1" style="width: min-content !important;">
                <div class="content mb-0">
                    <h1><?php the_title() ?></h1>
					<?php the_content(); ?>
                </div>
            </header>
        </div>
		<?php
		get_template_part( "partials/button", args: [
			"href"    => rwmb_get_value( "cooperation-partner_website" ),
			"content" => __( "Open Website" ),
		] )
		?>

        <div class="my-5">
			<?php

			$args = array(
				"post_type"      => [ "movie", "event" ],
				"posts_per_page" => - 1,
				"meta_query"     => [
					[
						"key"   => "cooperation_partner_id",
						"value" => get_the_ID(),
					]
				],
				"meta_key"       => "screening_date",
				"orderby"        => "meta_value_num",
				"order"          => "ASC"
			);

			$query = new WP_Query( $args );

			if ( $query->have_posts() ) :
				while ( $query->have_posts() ) : $query->the_post();
					$programType   = (string) rwmb_get_value( 'program_type' );
					$startDateTime = (int) rwmb_get_value( 'screening_date' );
					// now resolve the name that shall be displayed
					$anonymizeTitle = ( rwmb_get_value( 'license_type' ) !== "full" && ! is_user_logged_in() );
					$title          = ( get_locale() === "de" ? rwmb_get_value( "german_title" ) : rwmb_get_value( "english_title" ) );
					if ( $anonymizeTitle ) {
						if ( $programType === "special_program" ) {
							$specialProgram = rwmb_get_value( "special_program" );
							$title          = $specialProgram->name;
						} else {
							switch ( $post->post_type ) {
								case "movie":
									$title = esc_html__( 'An unnamed movie', 'gegenlicht' );
									break;
								case "event":
									$title = esc_html__( 'An unnamed event', 'gegenlicht' );
							}
						}
					}
					?>
                    <div class="entry">
                        <div>
                            <p style="font-feature-settings: unset !important;">
								<?= date( "Y", $startDateTime ) ?>
                            </p>
                            <h2 class="is-size-5 no-separator is-uppercase">
								<?= $title ?>
                            </h2>
                        </div>
                    </div>

				<?php
				endwhile;
			else:
				?>
                <div class="content">
                    <?= apply_filters("the_content", get_theme_mod("missing_coop_entries")[get_locale()] ?? "") ?>
                </div>
			<?php endif; ?>
        </div>
    </article>
</main>
<?php
get_footer();
?>
