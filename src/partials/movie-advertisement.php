<?php
$isFollowup = (bool) $args['followUp'] ?? false;
$isLast     = (bool) $args['last'] ?? false;
$post       = get_post( $args['post_id'] ?? - 1 );
$anonymize  = ! apply_filters( "ggl_show_full_details", false );


$imageID = - 1;
if ( $anonymize ) {
    $imageID = get_theme_mod( 'anonymous_image' );
    if ( rwmb_get_value( "program_type" ) == "special_program" ) {
        $specialProgram = rwmb_get_value( "special_program" );
        $imageID        = get_term_meta( $specialProgram->term_id, "anonymous_image", single: true );
    }
}

?>
<article class="next-movie pt-0 <?= $isFollowup ? 'follow-up' : '' ?> <?= $isLast ? 'pb-5' : '' ?>">
    <header class="next-movie-header">
        <p>
            <?php
            if ( $isFollowup ):
                echo esc_html__( "Afterwards at", 'gegenlicht' );
            else:
                echo match ( $post->post_type ) {
                    "movie" => esc_html__( "Next Screening", 'gegenlicht' ),
                    "event" => esc_html__( "Next Event", 'gegenlicht' ),
                    default => esc_html__( "Up next", "gegenlicht" ),
                };
            endif;
            ?>
        </p>
        <p class="is-size-6 m-0 p-0">
            <?php
            echo ggl_get_starting_time( $post )->format( $isFollowup ? GGL_TIME_ONLY : GGL_LIST_DATETIME );
            ?>
        </p>
    </header>
    <div class="content">
        <h2 class="title next-movie-title py-4">
            <?= ggl_get_localized_title( $post ) ?>
        </h2>
    </div>
    <?php ggl_the_movie_thumbnail( $post ); ?>
    <hr class="separator"/>
    <?php
    get_template_part( "src/partials/button", args: [
            "href"    => get_the_permalink(),
            'content' => $post->post_type == 'movie' ? esc_html__( 'To the movie', 'gegenlicht' ) : esc_html__( 'To the event', 'gegenlicht' )
    ] );
    ?>
</article>
