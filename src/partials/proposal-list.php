<?php
$post        = $args['post'] ?? get_post(); // get the post that should be hidden. defaults to current post
$proposal_by = $args["proposal_by"] ?? null; // default to null as this value is retrievable by the post
$proposer_id = $args["proposer_id"] ?? null; // default to null as this value is retrievable by the post

$metaKey = "";

$max_entries = 5;

if ( $post !== null ) {
    $proposal_by  = rwmb_get_value( "selected_by", post_id: $post->ID );
    $proposer_ids = match ( $proposal_by ) {
        "member" => rwmb_get_value( "team_member_id", post_id: $post->ID ),
        "coop" => rwmb_get_value( "cooperation_partner_id", post_id: $post->ID ),
        default => [],
    };
}
$metaKey = match ( $proposal_by ) {
    "member" => "team_member_id",
    "coop" => "cooperation_partner_id",
    default => ""
};
if ( empty( $metaKey ) || empty( $proposer_ids ) ) {
    return;
}

$show_all_entries = apply_filters( "ggl__show_full_details", false, $post );

$proposed_movies = [];
$proposer_names  = [];
shuffle( $proposer_ids );
foreach ( $proposer_ids as $proposer_id ) {
    $proposer_names[] = ggl_get_title( $proposer_id );
    if ( $proposal_by === "member" ) {
        $proposed_movies = array_merge( $proposed_movies, ggl_get_teamie_movies( $proposer_id ) );
    } else {
        $proposed_movies = array_merge( $proposed_movies, ggl_get_partner_movies( $proposer_id ) );
    }
}

$filtered_proposals = array_filter( $proposed_movies, function ( $movie ) use ( $post ) {
    return $movie->ID !== $post->ID;
} );
shuffle( $filtered_proposals );
$proposals = array_slice( $filtered_proposals, 0, 5 );


$proposer_name_list = implode( ", ", array_slice( $proposer_names, 0, count( $proposer_names ) - 1 ) );
$proposer_name_str  = count( $proposer_names ) > 1 ? $proposer_name_list . " " . __( "and", "gegenlicht" ) . " " . end( $proposer_names ) : $proposer_names[0];

?>
<article class="page-content">
    <header class="content">
        <h3>
            <?= sprintf( $proposal_by == "member" ? /* translators: %s is the Team Members name */ esc_html__( "Selected by %s", "gegenlicht" ) : /* translators: %s is the Cooperation Partner's name */ esc_html__( "In cooperation with %s", "gegenlicht" ), $proposer_name_str ) ?>
        </h3>
    </header>
    <?php if ( count( $proposer_ids ) > 1 ) : ?>
    <div class="mt-3">
        <div class="is-flex is-justify-content-space-around is-flex-grow-5 is-gap-3 <?= $proposal_by === "member" ? 'is-align-items-center' : 'is-align-items-center' ?>"
             style="height: min-content !important; overflow: scroll;">
            <?php foreach ( $proposer_ids as $proposer_id ) : ?>
                <?php if ( $proposal_by === "member" ) : ?>
                    <?php ggl_the_teamie_image( $proposer_id, min_height: "250px" ); ?>
                <?php else: ?>
                    <?php ggl_the_partner_image( $proposer_id, min_width: "250px" ); ?>
                <?php endif; ?>
            <?php endforeach; ?>
        </div>
        <?php else: ?>
        <div class="is-flex is-align-items-top is-flex-wrap-wrap is-gap-1 mt-3">
            <figure class="image is-3by4  is-flex-grow-1 <?= $proposal_by == "member" ? "member-picture" : "coop-logo" ?>">
                <img alt=""
                     src="<?= get_the_post_thumbnail_url( $proposer_ids[0], "member-crop" ) ?: wp_get_attachment_image_url( get_theme_mod( 'anonymous_team_image' ), 'member-crop' ) ?>"/>
            </figure>
            <?php endif; ?>
            <?php if ( ! empty( $proposals ) ): ?>
                <div class="movie-list is-flex-grow-3"
                     style="<?= count( $proposer_names ) > 1 ? 'width: auto; margin-top: 0.5em;' : '' ?>">
                    <div class="movie-list-entries">
                        <?php foreach ( $proposals as $proposal ) : ?>
                            <div class="entry is-flex-direction-column is-align-items-flex-start">
                                <h2 class="is-size-6 no-separator is-uppercase movie-title">
                                    <?php ggl_the_localized_title($proposal) ?>
                                </h2>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php else: ?>
                <div class="proposal-list is-flex-grow-3"
                     style="<?= count( $proposer_names ) > 1 ? 'width: auto; margin-top: 0.5em' : '' ?>">
                    <?= esc_html__( "No other entries found here. Please check back next semester or log in…", "gegenlicht" ) ?>
                </div>
            <?php endif; ?>
        </div>
</article>
