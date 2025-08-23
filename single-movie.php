<?php
defined( 'ABSPATH' ) || exit;
$semester          = get_the_terms( get_the_ID(), 'semester' );
$currentSemesterID = get_theme_mod( "displayed_semester" );

$showPage = false;

foreach ( $semester as $key => $term ) {
	if ( $term->term_id == $currentSemesterID ) {
		$showPage = true;
		break;
	}
}

if ( ! $showPage && ! is_user_logged_in() ):
	wp_redirect( home_url(), status: 308 );

	return;
endif;


get_header();

$showDetails = ( rwmb_meta( 'license_type' ) == 'full' || is_user_logged_in() );
$title       = get_locale() == 'de' ? rwmb_meta( 'german_title' ) : rwmb_meta( 'english_title' );

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

        .navbar {
            background-color: var(--bulma-body-background-color) !important;
        }

        a.navbar-item {
            color: var(--bulma-body-color) !important;
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
        <div class="screening-information <?= ( $showDetails && $title != rwmb_meta( 'original_title' ) ) ? '' : 'pb-0' ?>">
            <div>
                <p><?= esc_html__( 'Screening', 'gegenlicht' ) ?></p>
                <p>
                    <time
                            datetime="<?= date( 'Y-m-d H:i', rwmb_meta( 'screening_date' ) ) ?>">
						<?= date( 'd.m.Y | H:i', rwmb_meta( 'screening_date' ) ) ?>
                    </time>
                </p>
            </div>
            <div class="is-justify-content-right">
				<?php
				$admission_type = rwmb_meta( 'admission_type' );

				switch ( $admission_type ) {
					case 'free':
						echo "<p>" . esc_html__( 'Free Admission', 'gegenlicht' ) . "</p>";
						break;
					case 'donation':
						echo "<p>" . esc_html__( 'Donations welcome', 'gegenlicht' ) . "</p>";
						break;
					case 'paid':
						$admissionFee = (float) rwmb_meta( 'admission_fee' );
						echo "<p>" . esc_html__( 'Admission', 'gegenlicht' ) . " " . number_format( $admissionFee, 2, get_locale() == 'en' ? '.' : "," ) . "&euro;</p>";

				}
				?>
            </div>
        </div>
        <h1 role="heading"
            class="<?= $showDetails ? ( $title != rwmb_meta( 'original_title' ) ? 'no-separator' : '' ) : '' ?>">
			<?= $showDetails ? $title : ( rwmb_meta( 'program_type' ) == 'special_program' ? trim( get_term( rwmb_meta( 'special_program' ) )->name ) : esc_html__( 'An unnamed movie', 'gegenlicht' ) ) ?>
        </h1>
		<?php if ( $showDetails && $title != rwmb_meta( 'original_title' ) ): ?>
            <p class="font-ggl is-size-4"><?= rwmb_meta( 'original_title' ) ?></p>
            <hr class="separator"/>
		<?php endif; ?>
        <div class="mt-2">
            <p>
				<?= join( '/', rwmb_meta( 'country' ) ) ?> <?= date( 'Y', strtotime( rwmb_meta( 'release_date' ) ) ) ?>
                |
				<?= rwmb_meta( 'running_time' ) ?> <?= esc_html__( 'Minutes', 'gegenlicht' ) ?>
            </p>
            <p>
				<?= esc_html__( 'by', 'gegenlicht' ) ?> <?= $showDetails ? rwmb_meta( 'director' )->name : trim( preg_replace( '/\w/', '█', rwmb_meta( 'director' )->name ) ) ?>
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
				<?= esc_html__( 'with', 'gegenlicht' ) ?> <?= join( separator: ' ' . esc_html__( 'and', 'gegenlicht' ) . ' ', array: $actorNames ) ?>
            </p>
        </div>
        <hr class="separator"/>
        <div class="tags are-medium">
			<?php
			$ageRating   = rwmb_meta( 'age_rating' );
			$descriptors = rwmb_meta( 'descriptors' );

			$translatedDescriptors = array();

			foreach ( $descriptors as $descriptor ) {
				$translatedDescriptors[] = ggl_theme_get_translated_age_rating_descriptor( $descriptor );
			}

			switch ( $ageRating ) {
				case - 2:
				case - 1:
					echo '<span class="tag is-rounded is-primary ' . ( ! empty( $translatedDescriptors ) ? ( 'has-tooltip-arrow has-tooltip-right has-tooltip-multiline" data-tooltip="' . join( ", ", $translatedDescriptors ) . '"' ) : '' ) . '" style="border: var(--bulma-body-color) solid var(--border-thickness);">' . esc_html__( 'Not Rated', 'gegenlicht' ) . '</span>';
					break;
				default:
					echo '<span class="tag is-rounded is-primary ' . ( ! empty( $translatedDescriptors ) ? ( 'has-tooltip-arrow has-tooltip-right has-tooltip-multiline" data-tooltip="' . join( ", ", $translatedDescriptors ) . '"' ) : '' ) . '" style="border: var(--bulma-body-color) solid var(--border-thickness);">' . esc_html__( 'FSK', 'gegenlicht' ) . ' ' . $ageRating . '</span>';

			}
			?>

			<?php
			$audioType        = rwmb_meta( 'audio_type' );
			$audioLanguage    = rwmb_meta( 'audio_language' );
			$subtitleLanguage = rwmb_meta( 'subtitle_language' );

			if ( $audioType == 'original' ):
				if ( $subtitleLanguage == 'eng' ):
					echo '<span class="tag is-rounded is-primary has-tooltip-arrow has-tooltip-bottom" style="border: var(--bulma-body-color) solid var(--border-thickness);" data-tooltip="' . $audioLanguage . '. ' . esc_html__( 'Original with', 'gegenlicht' ) . ' ' . $subtitleLanguage . '. ' . esc_html__( 'Subtitles', 'gegenlicht' ) . '">' . esc_html__( 'OmeU' ) . '</span>';
				endif;
				if ( $subtitleLanguage == 'deu' ):
					echo '<span class="tag is-rounded is-primary has-tooltip-arrow has-tooltip-bottom" style="border: var(--bulma-body-color) solid var(--border-thickness);" data-tooltip="' . $audioLanguage . '. ' . esc_html__( 'Original with', 'gegenlicht' ) . ' ' . $subtitleLanguage . '. ' . esc_html__( 'Subtitles', 'gegenlicht' ) . '">' . esc_html__( 'OmdU' ) . '</span>';
				endif;
				if ( $subtitleLanguage == 'zxx' ):
					echo '<span class="tag is-rounded is-primary has-tooltip-arrow has-tooltip-bottom" style="border: var(--bulma-body-color) solid var(--border-thickness);" data-tooltip="' . $audioLanguage . '. ' . esc_html__( 'Original without Subtitles', 'gegenlicht' ) . '">' . esc_html__( 'OV', 'gegenlicht' ) . '</span>';
				endif;
			endif;

			if ( $audioType == 'synchronization' ):
				echo '<span class="tag is-rounded is-primary has-tooltip-arrow has-tooltip-right" style="border: var(--bulma-body-color) solid 1px;" data-tooltip="' . $audioLanguage . '. ' . $subtitleLanguage == 'zxx' ? esc_html__( 'Audio without Subtitles', 'gegenlicht' ) : esc_html__( 'Audio with', 'gegenlicht' ) . ' ' . $subtitleLanguage . '. ' . esc_html__( 'Subtitles', 'gegenlicht' ) . '">' . esc_html__( 'Dub' ) . '</span>';
			endif;


			?>
        </div>
		<?php get_template_part( 'partials/responsive-image', args: [
			'fetch-priority' => 'high',
			'image_url'      => $showDetails ? get_the_post_thumbnail_url( size: 'full' ) ?: wp_get_attachment_image_url( $anonymousImage, 'large' ) : wp_get_attachment_image_url( $anonymousImage, 'large' )
		] ) ?>
		<?php if ( ! $showDetails && ! $isSpecialProgram ): ?>
            <div class="boxed-text mt-3">
				<?= apply_filters( "the_content", get_theme_mod( 'anonymized_movie_explainer' )[ get_locale() ] ?? "" ) ?>
            </div>
		<?php endif; ?>
		<?php if ( $isSpecialProgram ): ?>
            <div class="boxed-text mt-3">
				<?= apply_filters( "the_content", $specialProgram->description ) ?>
            </div>
		<?php endif; ?>
    </header>
	<?php if ( rwmb_meta( "allow_reservations" ) ): ?>
        <div class="has-background-white py-4 px-2 my-5 reservation-button">
            <a class="button is-fullwidth is-uppercase is-size-5 has-background-white has-text-black"
               href="<?= rwmb_meta( "reservation_url" ) ?>">
                <span class="has-text-weight-bold"><?= esc_html__( 'Reserve Now', 'gegenlicht' ) ?></span>
                <span class="material-symbols ml-1">open_in_new</span>
            </a>
        </div>
	<?php endif; ?>
    <article class="page-content px-2 mt-4 content">
		<?php if ( rwmb_meta( 'show_content_notice' ) ): ?>
            <div class="content-notice mb-6 p-2">
                <h2 class="is-size-4 border-is-background-color"><?= esc_html__( "Content Notice", 'gegenlicht' ) ?></h2>
                <p>
					<?= ggl_cleanup_paragraphs( rwmb_get_value( 'content_notice' ) ) ?>
                </p>
            </div>
		<?php endif; ?>
        <h2 class="font-ggl is-size-3 is-uppercase">
			<?= esc_html__( 'What the movie is about', 'gegenlicht' ) ?>
        </h2>
		<?= ggl_cleanup_paragraphs( $showDetails ? rwmb_get_value( 'summary' ) : rwmb_get_value( 'anon_summary' ) ) ?>
        <h2 class="font-ggl is-size-3 is-uppercase mt-6">
			<?= esc_html__( "Why it's worth watching", 'gegenlicht' ) ?>
        </h2>
		<?= ggl_cleanup_paragraphs( $showDetails ? rwmb_get_value( 'worth_to_see' ) : rwmb_get_value( 'anon_worth_to_see' ) ) ?>
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
	<?php if ( rwmb_meta( "allow_reservations" ) ): ?>
        <div class="has-background-white py-4 px-2 my-5 reservation-button">
            <a class="button is-fullwidth is-uppercase is-size-5 has-background-white has-text-black"
               href="<?= rwmb_meta( "reservation_url" ) ?>">
                <span class="has-text-weight-bold"><?= esc_html__( 'Reserve Now', 'gegenlicht' ) ?></span>
                <span class="material-symbols ml-1">open_in_new</span>
            </a>
        </div>
	<?php endif; ?>
</main>
<section id="proposed-by">
	<?php
	$selectedBy     = '';
	$selectedByType = rwmb_meta( 'selected_by' );
	$selectorID     = '';
	$skipSelectedBy = false;
	switch ( $selectedByType ) {
		case 'member':
			$selectorID = rwmb_meta( 'team_member_id' );
			$member     = get_post( $selectorID );
			$selectedBy = $member->post_title;
			break;
		case 'cooperation':
			$selectorID = rwmb_meta( 'cooperation_partner_id' );
			$partner    = get_post( $selectorID );
			$selectedBy = $partner->post_title;
			break;
		default:
			$skipSelectedBy = true;
			break;
	}

	if ( ! $skipSelectedBy ):
		get_template_part( "partials/team-proposals", args: [
			'post-id'     => $post->ID,
			'proposal-by' => $selectedByType,
			'proposer-id' => $selectorID
		] )
		?>
	<?php endif; ?>
</section>
<?php get_footer(); ?>



