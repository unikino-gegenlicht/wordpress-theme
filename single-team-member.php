<?php
defined( 'ABSPATH' ) || exit;

get_header();
?>
<main class="page-content mt-4">
    <article>
        <div class="is-flex is-dynamic-flex is-align-items-top is-justify-content-space-evenly is-flex-wrap-wrap is-gap-2 mt-3">
            <figure class="image is-3by4 member-picture">
                <img alt=""
                     src="<?= get_the_post_thumbnail_url( size: 'member-crop' ) ?: wp_get_attachment_image_url( get_theme_mod( 'anonymous_team_image' ), 'member-crop' ) ?>"/>
            </figure>
            <header class="is-flex-grow-1 main-content">
                <div class="content mb-0" >
                    <h1><?php the_title() ?></h1>
                    <?php
                    if (rwmb_get_value("status") == "active"):
                        $templateText = get_theme_mod("active_text")[get_locale()] ?? "";

                        $outputText = str_replace("%%name%%", get_the_title(), $templateText);
                        $outputText = str_replace("%%joinedIn%%", rwmb_get_value("joined_in"), $outputText);
                    else:
	                    $templateText = get_theme_mod("former_text")[get_locale()] ?? "";
	                    $outputText = str_replace("%%name%%", get_the_title(), $templateText);
	                    $outputText = str_replace("%%joinedIn%%", rwmb_get_value("joined_in"), $outputText);
	                    $outputText = str_replace("%%leftIn%%", rwmb_get_value("left_in"), $outputText);
                    endif;

                    echo apply_filters( 'the_content', $outputText );
                    ?>
                </div>
            </header>
        </div>

        <div class="my-5">
	        <?php
	        $status = rwmb_meta( 'status' );
	        if ( $status == "active" ):
		        $currentSemester = get_theme_mod( "displayed_semester" );

		        $args = array(
			        "post_type"      => [ "movie", "event" ],
			        "posts_per_page" => 3,
			        'tax_query'      => array(
				        'relation' => "AND",
				        array(
					        'taxonomy' => 'semester',
					        'terms'    => $currentSemester,
				        ),
			        ),
			        "meta_query"     => [
				        [
					        "key"   => "team_member_id",
					        "value" => get_the_ID(),
				        ],
				        [
					        "key"     => "screening_date",
					        "value"   => time(),
					        "compare" => ">",
				        ]
			        ],
			        "meta_key"       => "screening_date",
			        "orderby"        => "meta_value_num",
			        "order"          => "ASC"
		        );

		        $query = new WP_Query( $args );

		        if ( $query->have_posts() ) :
			        get_template_part( 'partials/movie-list', args: [
				        "posts" => $query->posts,
				        "title" => __( "Upcoming Screenings", "gegenlicht" )
			        ] );
			        ?>

		        <?php endif; ?>
	        <?php else: ?>

	        <?php endif; ?>
            <div class="movie-list mt-4">
                <p class="movie-list-title">
					<?= esc_html__( "Past Screenings" ) ?>
                </p>
                <div class="movie-list-entries">
					<?php
					$args = array(
						"post_type"      => [ "movie", "event" ],
						"posts_per_page" => - 1,
						'tax_query'      => array(
							'relation' => "AND",
							array(
								'taxonomy' => 'semester',
								'terms'    => $currentSemester,
								"operator" => "!="
							),
						),
						"meta_query"     => [
							[
								"key"   => "team_member_id",
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
					?>
                </div>
				<?php
				endif;
				?>
            </div>

        </div>
    </article>
</main>
<?php
get_footer();
?>
