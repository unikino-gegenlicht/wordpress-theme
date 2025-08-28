<?php
/**
 * Movie List Partial
 *
 *
 * @type array $args {
 *      Required. Array of arguments for the movie list
 *
 * @type string $title Required. Title of the List
 * @type WP_Post[] $posts Required. The movies and event posts that should be in the list
 * @type bool $allowFiltering Optional. Defaults to `true`. Allow filtering the list for special programs
 * }
 */

/**
 * Title of the List
 *
 * @type string $title
 */
$title = $args["title"] ?? "";

/**
 * Posts included in the List
 *
 * Defaults to an empty array
 *
 * @type WP_Post[] $posts
 */
$posts = $args["posts"] ?? [];

/**
 * Allow filtering of movie list
 *
 * @type bool $allowFiltering
 */
$allowFiltering = $args["allowFiltering"] ?? true;

if ( empty( $posts ) ) {
	return;
}
?>
<div class="movie-list mb-5 <?= $allowFiltering ? "is-filterable" : "" ?>">
    <?php if (!empty($title)): ?>
    <p class="movie-list-title">
		<?= $title ?>
    </p>
    <?php endif; ?>
    <div class="movie-list-entries">
		<?php foreach ( $posts as $post ) :
			$post = get_post( $post->ID );

			// get the metadata that is being displayed
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
            <a role="link"
               aria-label="<?= $title ?>. <?= esc_html__( 'Screening starts: ', 'gegenlicht' ) ?> <?= date( 'r', $startDateTime ) ?>"
               data-program-type="<?= $programType ?>"
               href="<?= get_permalink() ?>">
                <div>
                    <p>
                        <time datetime="<?= date( 'Y-m-d H:i:s', $startDateTime ) ?>">
							<?= date( GGL_LIST_DATETIME, $startDateTime ) ?>
                        </time>
                    </p>
                    <h2 class="is-size-5 no-separator is-uppercase">
						<?= $title ?>
                    </h2>
                </div>
                <span class="icon">
                    <span class="material-symbols">arrow_forward_ios</span>
                </span>
            </a>
		<?php endforeach; wp_reset_postdata();?>
    </div>
</div>

