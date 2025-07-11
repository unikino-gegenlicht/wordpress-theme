<?php

defined( 'ABSPATH' ) || exit;
get_header();
do_action( 'wp_body_open' );
?>

    <article class="page-content">
        <h1 class="is-size-1 no-separator"><?= esc_html__( 'Join Us!', 'gegenlicht' ) ?></h1>
        <div class="content">
			<?php
			$introTextRaw = get_theme_mod( 'team_intro_text_' . get_locale() );
			$paragraphs   = preg_split( "/\R\R/", $introTextRaw );
			foreach ( $paragraphs as $paragraph ) :
				?>
                <p>
					<?= $paragraph ?>
                </p>
			<?php
			endforeach;
			?>
        </div>
    </article>
<?= do_shortcode( '[ggl-contact-block]' ); ?>
    <main class="page-content content">
        <hr class="separator">
        <p class="my-2"><?= esc_html__( 'Our Team', 'gegenlicht' ) ?></p>
        <hr class="separator">
        <h2 class="is-size-3"><?= esc_html__( 'Active Members', 'gegenlicht' ) ?></h2>
        <div class="fixed-grid has-2-cols-mobile has-4-cols-tablet">
            <div class="grid">
				<?php
				$members = array();
				while ( have_posts() ) : the_post();
					$status = rwmb_meta( 'status' );
					switch ( $status ) :
						case 'active' :
							$members['active'][] = $post;
							break;
						case 'former':
							$members['former'][] = $post;
							break;
					endswitch;
				endwhile;

				foreach ( $members['active'] as $member ) : $imageUrl = get_the_post_thumbnail_url( post: $member, size: 'full' );
					?>
                    <div class="cell">
                        <figure class="image is-3by4 member-picture">
                            <img src="<?= $imageUrl ?: wp_get_attachment_image_url( get_theme_mod( 'missing_team_image_replacement' ) ) ?>"/>
                        </figure>
                        <hr class="separator"/>
                        <h5><?= $member->post_title ?></h5>
                        <p class="is-italic"> <?= esc_html__( 'since', 'gegenlicht' ) ?> <?= rwmb_meta( 'joined_in', post_id: $member->ID ) ?> </p>
                    </div>
				<?php endforeach; ?>
            </div>
        </div>

		<?php if ( isset($members['former']) && count( $members['former'] ) > 0 )  : ?>
            <h2 class="is-size-3"><?= esc_html__( 'Former Members', 'gegenlicht' ) ?></h2>
            <div class="content">
				<?php
				$introTextRaw = get_theme_mod( 'former_members_text_' . get_locale() );
				$paragraphs   = preg_split( "/\R\R/", $introTextRaw );
				foreach ( $paragraphs as $paragraph ) :
					?>
                    <p>
						<?= $paragraph ?>
                    </p>
				<?php
				endforeach;
				?>
            </div>
            <div class="fixed-grid has-2-cols-mobile has-4-cols-tablet">
                <div class="grid">
					<?php
					foreach ( $members['former'] as $member ) : $imageUrl = get_the_post_thumbnail_url( post: $member, size: 'full' );
						?>
                        <div class="cell">
                            <figure class="image is-3by4 member-picture">
                                <img src="<?= $imageUrl ?: wp_get_attachment_image_url( get_theme_mod( 'missing_team_image_replacement' ) ) ?>"/>
                            </figure>
                            <hr class="separator"/>
                            <h5><?= $member->post_title ?></h5>
                            <p class="is-italic"> <?= rwmb_meta( 'joined_in', post_id: $member->ID ) ?>–<?= rwmb_meta( 'left_in', post_id: $member->ID ) ?> </p>
                        </div>
					<?php endforeach; ?>
                </div>
            </div>
		<?php endif ?>
    </main>


<?php get_footer(); ?>