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

$args = [
        "post_type"      => [ "movie", "event" ],
        "posts_per_page" => $max_entries,
        "post__not_in"   => [ $post->ID ],
        "orderby"        => "rand",
        "meta_query"     => [
                [
                        "key"   => "selected_by",
                        "value" => $proposal_by,
                ],
                [
                        "key"     => $metaKey,
                        "value"   => $proposer_ids,
                        "compare" => "IN"
                ],
                [
                        'key'     => 'program_type',
                        'value'   => apply_filters( "ggl__show_full_details", false, $post ) ? [
                                "main",
                                "special_program"
                        ] : [ "main" ],
                        "compare" => "IN"
                ],
                [
                        'key'     => 'license_type',
                        'value'   => apply_filters( "ggl__show_full_details", false, $post ) ? [
                                "full",
                                "pool",
                                "none",
                                null
                        ] : [ "full" ],
                        "compare" => "IN"
                ],
                [
                        'key'     => "screening_date",
                        'value'   => time(),
                        "compare" => "<"
                ]
        ]
];

$query = new WP_Query( $args );

$proposals = [];
while ( $query->have_posts() ) : $query->the_post();
    $proposals[] = ggl_get_title( $query->post );
endwhile;

if ( count( $proposals ) < $max_entries ) {
    $metaKey = match ( $proposal_by ) {
        "member" => "team-member_shown_movies",
        "coop" => "cooperation-partner_shown_movies",
    };

    $manualEntries = rwmb_get_value( $metaKey, post_id: $proposer_id ) ?: [];
    foreach ( $manualEntries as $entry ) {
        if ( count( $proposals ) >= $max_entries ) {
            break;
        }
        $proposals[] = $entry[1];
    }
}

$proposer_names = [];
foreach ( $proposer_ids as $proposer_id ) {
    $proposer_names[] = get_post( $proposer_id )->post_title;
}
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
        <div class="is-flex is-align-items-top is-justify-content-space-around is-flex-grow-5 is-gap-3"
             style="height: 250px !important;">
            <?php foreach ( $proposer_ids as $proposer_id ) : ?>
                <figure class="image is-3by4 <?= $proposal_by == "member" ? "member-picture" : "coop-logo" ?>">
                    <img alt=""
                         src="<?= get_the_post_thumbnail_url( $proposer_id, "member-crop" ) ?: wp_get_attachment_image_url( get_theme_mod( 'anonymous_team_image' ), 'member-crop' ) ?>"/>
                </figure>
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
                <div class="movie-list proposal-list is-flex-grow-3"
                     style="<?= count( $proposer_names ) > 1 ? 'width: auto; margin-top: 0.5em;' : '' ?>">
                    <div class="movie-list-entries">
                        <?php foreach ( $proposals as $proposal ) : ?>
                            <div class="entry">
                                <p class="py-1 is-uppercase has-text-weight-bold"><?= $proposal ?></p>
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
