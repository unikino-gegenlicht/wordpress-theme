<?php
$currentPostID = $args['post-id'] ?? null;
$proposalBy    = $args['proposal-by'] ?? null;
$proposerID    = $args['proposer-id'] ?? null;

// constructing the meta query wich checks that the
$metaQuery = [
	'relation' => 'AND',
	array(
		[
			'key'   => 'program_type',
			'value' => 'main',
		],
		[
			'key'     => 'license_type',
			'value'   => [
				'full',
				is_user_logged_in() ? 'pool' : null, // only show pool licensed proposals if logged in
				is_user_logged_in() ? 'none' : null, // only show unlicensed proposals if logged in
			],
			'compare' => 'IN',
		],
		array(
			'relation' => 'OR',
			[
				'key'   => 'team_member_id',
				'value' => $proposerID,
			],
			[
				'key'   => 'cooperation_partner_id',
				'value' => $proposerID,
			]
		)
	)
];

$proposals = new WP_Query( [
	'post_type'      => [ 'movie', 'event' ],
	'posts_per_page' => get_theme_mod( 'displayed_proposal_count', 6 ),
	'post__not_in'   => [ $currentPostID ], // do not display the currently shown movie or event again
	'meta_query'     => $metaQuery,
	'orderby'        => 'rand',
] );
?>

<article class="page-content">
    <header>
        <h3>
			<?= $proposalBy == 'member' ? esc_html__( 'Selected by', 'gegenlicht' ) : esc_html__( 'Also shown together with', 'gegenlicht' ) ?>
            <?= get_post( $proposerID )->post_title ?>
        </h3>
    </header>
    <div class="is-flex is-align-items-top is-flex-wrap-wrap is-gap-1 mt-3">
        <figure class="image is-3by4 member-picture">
            <img alt=""
                 src="<?= get_the_post_thumbnail_url( $proposerID, 'full' ) ?: wp_get_attachment_image_url(get_theme_mod( 'anonymous_team_image' ), 'full') ?>"/>
        </figure>
        <div class="proposal-list">
			<?php if ( $proposals->have_posts() ) : while ( $proposals->have_posts() ) : $proposals->the_post(); ?>
                <hr class="separator"/>
                <div class="mt-2">
                    <?php $title = (get_locale() == 'de' ? rwmb_get_value( 'german_title' ) : rwmb_get_value( 'english_title' )) ?>
                    <p class="has-text-weight-bold"><?= $title ?></p>
                    <?php if ($title != rwmb_the_value('original_title', echo: false)): ?>
                    <?php if ($post->post_type == 'movie'): ?>
                        <p>OT:&ensp;<?= rwmb_the_value('original_title', echo: false) ?></p>
                    <?php else: ?>
                        <p>Event</p>
                    <?php endif; ?>
                    <?php endif; ?>
                </div>
			<?php endwhile; ?>
                <hr class="separator"/>
			<?php else: ?>
                <p class="is-align-self-center"><?= esc_html__( 'No proposals found. Check back later…', 'gegenlicht' ) ?></p>
			<?php endif; ?>
        </div>
    </div>
</article>
