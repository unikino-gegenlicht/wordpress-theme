<?php
defined( 'ABSPATH' ) || exit;


get_header();
do_action( 'wp_body_open' );
$anonymize  = rwmb_get_value( "license_type" ) != "full" && ! is_user_logged_in();
$isSpecialProgram = rwmb_get_value("program_type") === "special_program";

?>
<main>
    <header class="page-content">
        <div class="screening-information pb-0">
            <div>
                <p><?= esc_html__( 'Event starts', 'gegenlicht' ) ?></p>
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
        <h1 role="heading"><?= ggl_get_title() ?></h1>
        <div class="mt-2">
            <p>
				<?= rwmb_meta( 'duration' ) ?> <?= esc_html__( 'Minutes', 'gegenlicht' ) ?>
            </p>
        </div>
        <hr class="separator"/>
        <div class="tags are-medium">
            <span class="tag is-rounded is-primary"><?= esc_html__(rwmb_meta( 'audio_language' ), "gegenlicht") ?></span>
            <?php if (rwmb_get_value("age_restricted")): ?>
            <span class="tag is-rounded is-primary"><?=
                sprintf(
                        /* translators: %d Minimal Attendee Age*/
                        esc_html__("For Ages %d+", "gegenlicht"),
                        (int) rwmb_get_value("minimal_age")
                ) ?></span>
            <?php endif; ?>
        </div>
		<?php ggl_the_post_thumbnail() ?>
		<?php if ($isSpecialProgram): ?>
            <div class="boxed-text mt-3">
				<?= apply_filters("the_content", rwmb_get_value("special_program")->description) ?>
            </div>
		<?php endif; ?>
    </header>
    <?php if (rwmb_meta("allow_reservations")): ?>
    <div class="has-background-white py-4 px-2 my-5 reservation-button">
        <a class="button is-fullwidth is-uppercase is-size-5 has-background-white has-text-black" href="<?= rwmb_meta("reservation_url") ?>">
            <span class="has-text-weight-bold"><?= esc_html__( 'Reserve Now', 'gegenlicht' ) ?></span>
            <span class="material-symbols ml-1">open_in_new</span>
        </a>
    </div>
    <?php endif; ?>
    <article class="page-content px-2 mt-4 content">
		<?php if (rwmb_meta('show_content_notice')): ?>
            <div class="content-notice mb-6 p-2">
                <h2 class="is-size-4 border-is-background-color"><?= esc_html__("Content Notice", 'gegenlicht') ?></h2>
                <p>
	                <?= apply_filters("the_content", rwmb_get_value( 'content_notice' ) ?? "" ) ?>
                </p>
            </div>
		<?php endif; ?>
        <h2 class="font-ggl is-size-3 is-uppercase">
			<?= esc_html__( 'What the event is about', 'gegenlicht' ) ?>
        </h2>
	    <?= apply_filters("the_content",  ggl_get_summary() ) ?>
        <h2 class="font-ggl is-size-3 is-uppercase mt-6">
			<?= esc_html__( "Why it's worth attending", 'gegenlicht' ) ?>
        </h2>
	    <?= apply_filters( "the_content",ggl_get_worth_to_see() ) ?>
    </article>
	<?php if (rwmb_meta("allow_reservations")): ?>
        <div class="has-background-white py-4 px-2 my-5 reservation-button">
            <a class="button is-fullwidth is-uppercase is-size-5 has-background-white has-text-black" href="<?= rwmb_meta("reservation_url") ?>">
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
	$selectorID        = '';
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
		get_template_part("partials/team-proposals", args: ['post-id' => $post->ID, 'proposal-by' => $selectedByType, 'proposer-id' => $selectorID ])
		?>
	<?php endif; ?>
</section>
<?php get_footer(); ?>



