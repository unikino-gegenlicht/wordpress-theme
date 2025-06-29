<?php

defined( 'ABSPATH' ) || exit;


get_header( 'movie' );
do_action( 'wp_body_open' );

$programType = rwmb_meta( 'movie_program_type' );
if ( $programType == "special_program" ):
	$specialProgramID = rwmb_meta( 'movie_special_program' );
	$specialProgram   = get_term( $specialProgramID, 'special-program' );

    $backgroundColor = get_term_meta( $specialProgram->term_id, 'background_color', true );
    $textColor = get_term_meta( $specialProgram->term_id, 'text_color', true );
    $backgroundColorDark = get_term_meta( $specialProgram->term_id, 'dark_background_color', true );
    $textColorDark = get_term_meta( $specialProgram->term_id, 'dark_text_color', true );
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
            <p><?= esc_html__( 'Screening on' ) ?></p>
            <p>
                <time
                        datetime="<?= date( 'Y-m-d H:i', rwmb_meta( 'screening_date' ) ) ?>">
					<?= date( 'd.m.Y | H:i', rwmb_meta( 'screening_date' ) ) ?>
                </time>
            </p>
        </div>
        <hr class="separator"/>
        <h1 class="font-ggl is-size-1 is-uppercase ">
			<?= rwmb_meta( 'german_title' ) ?>
        </h1>
        <p class="font-ggl is-size-4"><?= rwmb_meta( 'original_title' ) ?></p>
        <hr class="separator"/>
        <p>
            <!-- <?= rwmb_meta( 'movie_release_date' ) ?> -->
			<?= join( '/', rwmb_meta( 'movie_country' ) ) ?> <?= date( 'Y', strtotime( rwmb_meta( 'movie_release_date' ) ) ) ?>
            |
			<?= rwmb_meta( 'movie_running_time' ) ?> <?= esc_html__( 'Minutes' ) ?> |
			<?php
			$audioType        = rwmb_meta( 'movie_audio_type' );
			$audioLanguage    = rwmb_meta( 'movie_audio_language' );
			$subtitleLanguage = rwmb_meta( 'movie_subtitle_language' );

			switch ( $audioType ) {
				case 'original':
					echo $audioLanguage . '. ' . esc_html__( 'Original' ) . ( $subtitleLanguage != 'zxx' ? ' ' . esc_html__( 'with' ) . ' ' . $subtitleLanguage . '. ' . esc_html__( 'Subtitles' ) : '' );
					break;
				case 'synchronization':
					echo $audioLanguage . '. ' . esc_html__( 'Synchronization' ) . ( $subtitleLanguage != 'zxx' ? ' ' . esc_html__( 'with' ) . ' ' . $subtitleLanguage . '. ' . esc_html__( 'Subtitles' ) : '' );
					break;
			}
			?>
        </p>
        <p>
			<?= esc_html__( 'by' ) ?> <?= rwmb_the_value( 'movie_director', echo: false ) ?>
        </p>
        <p>
			<?php
			$actors     = rwmb_meta( 'movie_actors' );
			$actorNames = array();
			foreach ( $actors as $actor ) {
				$actorNames[] = $actor->name;
			}
			?>
			<?= esc_html__( 'with' ) ?> <?= join( separator: ' ' . esc_html__( 'and' ) . ' ', array: $actorNames ) ?>
        </p>
        <hr class="separator"/>
        <figure class="image is-hidden-tablet is-4by5 movie-image">
            <img src="<?= get_the_post_thumbnail_url( size: 'full' ) ?>"/>
        </figure>
        <figure class="image is-hidden-mobile is-16by9 movie-image">
            <img src="<?= get_the_post_thumbnail_url( size: 'full' ) ?>"/>
        </figure>
    </header>
    <div class="has-background-white py-4 px-2 my-5 reservation-button">
        <a class="button is-fullwidth is-uppercase is-size-5 has-background-white has-text-black" href="">
            <span class="has-text-weight-bold"><?= esc_html__( 'Reserve Now' ) ?></span>
            <span class="material-symbols ml-1">open_in_new</span>
        </a>
    </div>
    <article class="page-content px-2 mt-4">
        <h2 class="font-ggl is-size-3 is-uppercase">
			<?= esc_html__( 'What the movie is about' ) ?>
        </h2>
        <hr class="separator"/>
        <p>
			<?= rwmb_meta( 'movie_summary' ) ?>
        </p>
        <h2 class="font-ggl is-size-3 is-uppercase mt-6">
			<?= esc_html__( "Why it's worth watching" ) ?>
        </h2>
        <hr class="separator"/>
        <p>
			<?= rwmb_meta( 'movie_worth_to_see' ) ?>
        </p>
		<?php if ( rwmb_meta( 'movie_short_movie_screened' ) == 'yes' ): ?>
            <hr class="separator mt-6"/>
            <h3 class="font-ggl is-size-5 is-uppercase">
				<?= esc_html__( 'Short Movie' ) ?>
            </h3>
            <p><?= rwmb_meta( 'movie_short_movie_title' ) ?></p>
            <div class="is-flex is-align-items-center short-details">
                <p><?= esc_html__( 'by' ) ?> <?= rwmb_meta( 'movie_short_movie_directed_by' ) ?></p>
                |
                <p><?= join( '/', rwmb_meta( 'movie_short_movie_country' ) ) ?> <?= rwmb_meta( 'movie_short_movie_release_year' ) ?></p>
                |
                <p><?= rwmb_meta( 'movie_short_movie_running_time' ) ?> <?= esc_html__( 'Minutes' ) ?></p>
            </div>
            <hr class="separator"/>
		<?php endif; ?>
    </article>
    <div class="has-background-white py-4 px-2 mt-5 reservation-button">
        <a class="button is-fullwidth is-uppercase is-size-5 has-background-white has-text-black" href="">
            <span class="has-text-weight-bold"><?= esc_html__( 'Reserve Now' ) ?></span>
            <span class="material-symbols ml-1">open_in_new</span>
        </a>
    </div>
</main>
<section id="proposed-by">
	<?php
	$selectedBy     = '';
	$selectedByType = rwmb_meta( 'movie_selected_by_type' );
	$post_id        = '';
	$skipSelectedBy = false;
	switch ( $selectedByType ) {
		case 'member':
			$post_id    = rwmb_meta( 'movie_member_id' );
			$member     = get_post( $post_id );
			$selectedBy = $member->post_title;
			break;
		case 'cooperation':
			$post_id    = rwmb_meta( 'movie_cooperation_partner_id' );
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
                    <img src="<?= get_the_post_thumbnail_url( post: $post_id, size: 'full' ) ?>"/>
                </figure>
                <div style="width: 1rem; height: 1rem"></div>
                <div class="proposal-list">
                    <!-- TODO: change to also showed in cooperation if not selected by member -->
					<?php
					$query = new WP_Query( array(
						'post_type'      => [ 'movie', 'event' ],
						'posts_per_page' => 6,
						'relation'       => 'OR',
						'meta_query'     => array(
							array(
								'key'   => 'movie_member_id',
								'value' => $post_id,
							),
						)
					) );
					echo "<!-- ";
					var_dump( $query );
					echo "--> ";
					if ( $query->have_posts() ):
						?>
                        <h4 class="has-text-weight-bold font-ggl is-uppercase"><?= esc_html__( 'My Movie Proposals' ) ?></h4>
						<?php
						while ( $query->have_posts() ) : $query->the_post();
							?>
                            <p class="mt-2"><?= $post->post_title ?></p>
						<?php endwhile;
						wp_reset_postdata(); endif; ?>
                </div>
            </div>
        </div>
	<?php endif; ?>
</section>
<?php get_footer(); ?>



