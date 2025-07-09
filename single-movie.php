<?php

/**
 * Template Name: Movie Detail Page
 */

defined( 'ABSPATH' ) || exit;


get_header();
do_action( 'wp_body_open' );


$showDetails = ( rwmb_meta( 'license_type' ) == 'full' || is_user_logged_in() );

$title = get_locale() == 'de' ? rwmb_meta( 'german_title' ) : rwmb_meta( 'english_title' );

$anonymousImage = get_theme_mod( 'anonymous_image' );

$isSpecialProgram   = rwmb_meta( 'program_type' ) == 'special_program';
if ( $isSpecialProgram ):
	$specialProgramID = rwmb_meta( 'special_program' );
	$specialProgram = get_term( $specialProgramID, 'special-program' );

	$anonymousImage      = get_term_meta( $specialProgram->term_id, 'anonymous_image', true );
	$backgroundColor     = get_term_meta( $specialProgram->term_id, 'background_color', true );
	$textColor           = get_term_meta( $specialProgram->term_id, 'text_color', true );
	$backgroundColorDark = get_term_meta( $specialProgram->term_id, 'dark_background_color', true );
	$textColorDark       = get_term_meta( $specialProgram->term_id, 'dark_text_color', true );
	?>
    <style>
        :root {
            --bulma-body-background-color: <?= $backgroundColor ?> !important;
            --bulma-body-color: <?= $textColor ?> !important;
        }

        @media (prefers-color-scheme: dark) {
            :root {
                --bulma-body-background-color: <?= $backgroundColorDark ?> !important;
                --bulma-body-color: <?= $textColorDark ?> !important;
            }
        }
    </style>
<?php endif; ?>
<main>
    <header class="page-content">
        <hr class="separator"/>
        <div class="screening-information">
            <p><?= esc_html__( 'Screening', 'gegenlicht' ) ?></p>
            <p>
                <time
                        datetime="<?= date( 'Y-m-d H:i', rwmb_meta( 'screening_date' ) ) ?>">
					<?= date( 'd.m.Y | H:i', rwmb_meta( 'screening_date' ) ) ?>
                </time>
            </p>
        </div>
        <hr class="separator"/>
        <div class="screening-information is-justify-content-right">
			<?php
			$admission_type = rwmb_meta( 'admission_type' );

			switch ( $admission_type ) {
				case 'free':
					echo "<p>" . esc_html__( 'Free Admission', 'gegenlicht' ) . "</p>";
					break;
				case 'donation':
					echo "<p>" . esc_html__( 'Donations welcome', 'gegenlicht' ) . "</p>";
				case 'paid':
					$admissionFee = (float) rwmb_meta( 'admission_fee' );
					echo "<p>" . esc_html__( 'Admission', 'gegenlicht' ) . " " . number_format( $admissionFee, 2, get_locale() == 'en' ? '.' : "," ) . "&euro;</p>";

			}
			?>
        </div>
        <hr class="separator"/>
        <h1 class="font-ggl is-size-1 is-uppercase ">
	        <?= $showDetails ? $title : (rwmb_meta('program_type') == 'special_program' ? trim(get_term(rwmb_meta('special_program'))->name) : esc_html__( 'An unnamed movie', 'gegenlicht' )) ?>
        </h1>
		<?php if ( $showDetails && $title != rwmb_meta( 'original_title' ) ): ?>
            <p class="font-ggl is-size-4"><?= rwmb_meta( 'original_title' ) ?></p>
		<?php endif; ?>
        <hr class="separator"/>
        <p>
			<?= join( '/', rwmb_meta( 'country' ) ) ?> <?= date( 'Y', strtotime( rwmb_meta( 'release_date' ) ) ) ?>
            |
			<?= rwmb_meta( 'running_time' ) ?> <?= esc_html__( 'Minutes', 'gegenlicht' ) ?>
        </p>
        <p>
			<?= esc_html__( 'by' ) ?> <?= $showDetails ? rwmb_meta( 'director' )->name : trim(preg_replace( '/\w/', '█', rwmb_meta( 'director' )->name )) ?>
        </p>
        <p>
			<?php
			$actors     = rwmb_meta( 'actors' );
			$actorNames = array();
			foreach ( $actors as $actor ) {
				if ( $showDetails ) {
					$actorNames[] = $actor->name;
				} else {
					$actorNames[] = preg_replace( '/\w/', '█', $actor->name );
				}
			}
			?>
			<?= esc_html__( 'with' ) ?> <?= join( separator: ' ' . esc_html__( 'and' ) . ' ', array: $actorNames ) ?>
        </p>
        <hr class="separator"/>
        <div class="tags are-medium">
			<?php
			$ageRating = rwmb_meta( 'age_rating' );
			switch ( $ageRating ) {
				case - 2:
				case - 1:
					echo '<span class="tag is-rounded is-primary" style="border: var(--bulma-body-color) solid 1px;">' . esc_html__( 'Not Rated', 'gegenlicht' ) . '</span>';
					break;
				default:
					echo '<span class="tag is-rounded is-primary" style="border: var(--bulma-body-color) solid 1px;">' . esc_html__( 'FSK', 'gegenlicht' ) . ' ' . $ageRating . '</span>';

			}
			?>

			<?php
			$audioType        = rwmb_meta( 'audio_type' );
			$audioLanguage    = rwmb_meta( 'audio_language' );
			$subtitleLanguage = rwmb_meta( 'subtitle_language' );

			if ( $audioType == 'original' ):
				if ( $subtitleLanguage == 'eng' ):
					echo '<span class="tag is-rounded is-primary has-tooltip-arrow has-tooltip-right" style="border: var(--bulma-body-color) solid 1px;" data-tooltip="' . $audioLanguage . '. ' . esc_html__( 'Original with', 'gegenlicht' ) . ' ' . $subtitleLanguage . '. ' . esc_html__( 'Subtitles', 'gegenlicht' ) . '">' . esc_html__( 'OmeU' ) . '</span>';
				endif;
				if ( $subtitleLanguage == 'deu' ):
					echo '<span class="tag is-rounded is-primary has-tooltip-arrow has-tooltip-right" style="border: var(--bulma-body-color) solid 1px;" data-tooltip="' . $audioLanguage . '. ' . esc_html__( 'Original with', 'gegenlicht' ) . ' ' . $subtitleLanguage . '. ' . esc_html__( 'Subtitles', 'gegenlicht' ) . '">' . esc_html__( 'OmdU' ) . '</span>';
				endif;
				if ( $subtitleLanguage == 'zxx' ):
					echo '<span class="tag is-rounded is-primary has-tooltip-arrow has-tooltip-right" style="border: var(--bulma-body-color) solid 1px;" data-tooltip="' . $audioLanguage . '. ' . esc_html__( 'Original without Subtitles', 'gegenlicht' ) . '">' . esc_html__( 'OV', 'gegenlicht' ) . '</span>';
				endif;
			endif;

			if ( $audioType == 'synchronization' ):
				echo '<span class="tag is-rounded is-primary has-tooltip-arrow has-tooltip-right" style="border: var(--bulma-body-color) solid 1px;" data-tooltip="' . $audioLanguage . '. ' . $subtitleLanguage == 'zxx' ? esc_html__( 'Audio without Subtitles', 'gegenlicht' ) : esc_html__( 'Audio with', 'gegenlicht' ) . ' ' . $subtitleLanguage . '. ' . esc_html__( 'Subtitles', 'gegenlicht' ) . '">' . esc_html__( 'Dub' ) . '</span>';
			endif;


			?>
        </div>
        <figure class="image is-hidden-tablet is-4by5 movie-image">
            <img alt="<?= $showDetails ? get_post_meta(get_post_thumbnail_id(), '_wp_attachment_image_alt', true) : ''?>" src="<?= $showDetails ? get_the_post_thumbnail_url( size: 'full' ) : wp_get_attachment_image_url($anonymousImage, size: 'full') ?>"/>
        </figure>
        <figure class="image is-hidden-mobile is-16by9 movie-image">
            <img alt="<?= $showDetails ? get_post_meta(get_post_thumbnail_id(), '_wp_attachment_image_alt', true) : ''?>" src="<?= $showDetails ? get_the_post_thumbnail_url( size: 'full' ) : wp_get_attachment_image_url($anonymousImage, size: 'full') ?>"/>
        </figure>
        <?php if (!$showDetails && !$isSpecialProgram): ?>
            <div class="boxed-text mt-3">
                <?php
                $introTextRaw = get_theme_mod( 'anonymized_movie_explainer_' . get_locale() );
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
        <?php endif; ?>
        <?php if ($isSpecialProgram): ?>
            <div class="boxed-text mt-3">
		        <?php
		        $paragraphs   = preg_split( "/\R\R/", $specialProgram->description );
		        foreach ( $paragraphs as $paragraph ) :
			        ?>
                    <p>
				        <?= $paragraph ?>
                    </p>
		        <?php
		        endforeach;
		        ?>
            </div>
        <?php endif; ?>
    </header>
    <div class="has-background-white py-4 px-2 my-5 reservation-button">
        <a class="button is-fullwidth is-uppercase is-size-5 has-background-white has-text-black" href="">
            <span class="has-text-weight-bold"><?= esc_html__( 'Reserve Now', 'gegenlicht' ) ?></span>
            <span class="material-symbols ml-1">open_in_new</span>
        </a>
    </div>
    <article class="page-content px-2 mt-4">
        <?php if (rwmb_meta('show_content_notice')): ?>
        <div class="content-notice mb-6 p-2">
            <h2 class="is-size-4"><?= esc_html__("Content Notice", 'gegenlicht') ?></h2>
            <hr class="separator m-1 is-background-color"/>
            <p>
                <?= rwmb_meta('content_notice') ?>
            </p>
        </div>
        <?php endif; ?>
        <h2 class="font-ggl is-size-3 is-uppercase">
			<?= esc_html__( 'What the movie is about' ) ?>
        </h2>
        <hr class="separator"/>
        <p>
			<?= $showDetails ? rwmb_meta( 'summary' ) : rwmb_meta('anon_summary') ?>
        </p>
        <h2 class="font-ggl is-size-3 is-uppercase mt-6">
			<?= esc_html__( "Why it's worth watching" ) ?>
        </h2>
        <hr class="separator"/>
        <p>
			<?= $showDetails ? rwmb_meta( 'worth_to_see' ) : rwmb_meta('anon_worth_to_see') ?>
        </p>
		<?php if ( rwmb_meta( 'short_movie_screened' ) == 'yes' && is_user_logged_in() ): ?>
            <hr class="separator mt-6"/>
            <h3 class="font-ggl is-size-5 is-uppercase">
				<?= esc_html__( 'Short Movie' ) ?>
            </h3>
            <p><?= rwmb_meta( 'short_movie_title' ) ?></p>
            <div class="is-flex is-align-items-center short-details">
                <p><?= esc_html__( 'by' ) ?> <?= rwmb_meta( 'short_movie_directed_by' ) ?></p>
                |
                <p><?= join( '/', rwmb_meta( 'short_movie_country' ) ) ?> <?= rwmb_meta( 'short_movie_release_year' ) ?></p>
                |
                <p><?= rwmb_meta( 'short_movie_running_time' ) ?> <?= esc_html__( 'Minutes' ) ?></p>
            </div>
            <hr class="separator"/>
		<?php endif; ?>
    </article>
    <div class="has-background-white py-4 px-2 mt-5 reservation-button">
        <a class="button is-fullwidth is-uppercase is-size-5 has-background-white has-text-black" href="">
            <span class="has-text-weight-bold"><?= esc_html__( 'Reserve Now', 'gegenlicht' ) ?></span>
            <span class="material-symbols ml-1">open_in_new</span>
        </a>
    </div>
</main>
<section id="proposed-by">
	<?php
	$selectedBy     = '';
	$selectedByType = rwmb_meta( 'selected_by' );
	$post_id        = '';
	$skipSelectedBy = false;
	switch ( $selectedByType ) {
		case 'member':
			$post_id    = rwmb_meta( 'team_member_id' );
			$member     = get_post( $post_id );
			$selectedBy = $member->post_title;
			break;
		case 'cooperation':
			$post_id    = rwmb_meta( 'cooperation_partner_id' );
			$partner    = get_post( $post_id );
			$selectedBy = $partner->post_title;
			break;
		default:
			$skipSelectedBy = true;
			break;
	}

	if ( ! $skipSelectedBy ):
		?>
        <div class="page-content">

            <p class="font-ggl is-size-3 is-uppercase">
				<?= esc_html__( 'Selected by' ) ?><br class="is-hidden-tablet"/>
				<?= $selectedBy ?>
            </p>
            <hr class="separator is-black"/>
            <div class="is-flex is-align-items-top is-flex-wrap-wrap">
                <figure class="image is-3by4 <?= $selectedByType == "member" ? 'member-picture' : '' ?>">
                    <img src="<?= get_the_post_thumbnail_url( post: $post_id, size: 'full' ) ?: wp_get_attachment_image_url( get_theme_mod( 'missing_team_image_replacement' ) ) ?>"/>
                </figure>
                <div style="width: 1rem; height: 1rem"></div>
                <div class="proposal-list">
					<?php
					$query = new WP_Query( array(
						'post_type'      => [ 'movie', 'event' ],
						'posts_per_page' => 6,
                        'post__not_in' => [ $post->ID ],
						'meta_query'     => array(
							'relation' => 'AND',
							array(
								array(
									'key'   => 'program_type',
									'value' => 'main',
								),
								array(
									'key'     => 'license_type',
									'value'   => [
										'full',
										is_user_logged_in() ? 'pool' : null,
										is_user_logged_in() ? 'none' : null,
									],
									'compare' => 'IN',
								),
								array(
									'relation' => 'OR',
									array(
										'key'   => 'team_member_id',
										'value' => $post_id,
									),
									array(
										'key'   => 'cooperation_partner_id',
										'value' => $post_id,
									)
								)
							)

						)
					) );
					if ( $query->have_posts() ):
						while ( $query->have_posts() ) : $query->the_post();
							?>
                            <hr class="separator is-black"/>
                            <p class="mt-2 has-text-weight-bold font-ggl is-uppercase is-size-5"><?= $post->post_title ?></p>
						<?php endwhile;
						echo '<hr class="separator is-black"/>';
						wp_reset_postdata();
					else:
						?>
                        <p class="is-align-self-center"><?= esc_html__( 'No proposals found. Check back later…' ) ?></p>
					<?php
					endif; ?>
                </div>
            </div>
        </div>
	<?php endif; ?>
</section>
<?php get_footer(); ?>



