<?php
$isFollowup = (bool) $args['followUp'] ?? false;
$isLast     = (bool) $args['last'] ?? false;
$post       = get_post( $args['post_id'] ?? - 1 );
$anonymize  = rwmb_get_value( "license_type" ) != "full" && ! is_user_logged_in();


$imageID = - 1;
if ( $anonymize ) {
	$imageID = get_theme_mod( 'anonymous_image' );
	if ( rwmb_get_value( "program_type" ) == "special_program" ) {
		$specialProgram = rwmb_get_value( "special_program" );
		$imageID        = get_term_meta( $specialProgram->term_id, "anonymous_image", single: true );
	}
}

?>
<article class="next-movie pt-2 <?= $isFollowup ? 'follow-up' : '' ?> <?= $isLast ? 'pb-6' : '' ?>">
    <header class="next-movie-header">
        <p>
			<?php
			if ( $isFollowup ):
				echo esc_html__( "Afterwards at", 'gegenlicht' );
			else:
				switch ( $post->post_type ) {
					case "movie" :
						echo esc_html__( "Next Screening", 'gegenlicht' );
						break;
					case "event" :
						echo esc_html__( "Next Event", 'gegenlicht' );
						break;
					default:
						echo esc_html__( "Up next", "gegenlicht" );
				}
			endif;
			?>
        </p>
        <p class="is-size-6 m-0 p-0">
			<?php
			if ( $isFollowup ):
				echo date( GGL_TIME_ONLY, (int) rwmb_get_value( "screening_date" ) );
			else:
				echo date( GGL_LIST_DATETIME, (int) rwmb_get_value( "screening_date" ) );
			endif;
			?>
        </p>
    </header>
    <h2 class="title next-movie-title py-4">
		<?= ggl_get_title() ?>
    </h2>
	<?php
	$imageArgs = [
		"fetch-priority" => "high",
		"lazy-load"      => false,
	];

	if ( $imageID != - 1 ) {
		$imageArgs["image_url"] = wp_get_attachment_url( $imageID );
	} else {
		$imageArgs["post-id"] = $post->ID;
	}

	get_template_part( "partials/responsive-image", args: $imageArgs );
	?>
    <hr class="separator"/>
	<?php
	get_template_part( "partials/button", args: [
		"href"    => get_the_permalink(),
		'content' => $post->post_type == 'movie' ? esc_html__( 'To the movie', 'gegenlicht' ) : esc_html__( 'To the event', 'gegenlicht' )
	] );
	?>
</article>
