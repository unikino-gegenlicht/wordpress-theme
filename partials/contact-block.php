<?php
function ggl_do_contact_shortcode( array $atts, $content = null, $tag = '' ) {

	$atts = array_change_key_case( $atts );

	$options = shortcode_atts( array(
			'fullwidth'          => false,
			'display_phone'      => false,
			'display_email'      => true,
			'display_instagram'  => true,
			'display_mastodon'   => true,
			'display_letterboxd' => false,
			'phone_number'       => get_theme_mod( 'contact_telephone_number' ),
			'email_address'      => get_theme_mod( 'contact_email_address' ),
			'mastodon_url'       => get_theme_mod( 'mastodon_url' ),
			'instagram_username' => get_theme_mod( 'instagram_username' ),
		), $atts, $tag );


	$mastodonProfileUrl = $options['mastodon_url'];

	$mastodonProfileParts = explode( "/", $mastodonProfileUrl );
	$mastodonProfileName  = $mastodonProfileParts[3] . "@" . $mastodonProfileParts[2];

	?>
    <div class="has-background-black has-text-primary py-1">
        <div class="limited-width-block">
            <h2 class="is-size-3 pl-2 is-uppercase"><?= esc_html__( 'Contact', 'gegenlicht' ) ?></h2>
            <div class="fixed-grid has-7-cols pl-2">
                <div class="grid is-row-gap-0 is-column-gap-4">
					<?php if ( $options['display_phone'] ): ?>
                        <div class="cell is-lowercase">
							<?= esc_html__( 'Telephone', 'gegenlicht' ) ?>
                        </div>
                        <div class="cell is-col-span-6">
							<?= $options['phone_number'] ?>
                        </div>
					<?php endif; ?>
					<?php if ( $options['display_email'] ): ?>
                        <div class="cell">
                            mail:
                        </div>
                        <div class="cell is-col-span-6">
							<?= $options['email_address'] ?>
                        </div>
					<?php endif; ?>
					<?php if ( $options['display_mastodon'] ): ?>

                        <div class="cell">
                            mastodon:
                        </div>
                        <div class="cell is-col-span-6">
							<?= $mastodonProfileName ?>
                        </div>
					<?php endif; ?>
					<?php if ( $options['display_instagram'] ): ?>
                        <div class="cell">
                            instagram:
                        </div>
                        <div class="cell is-col-span-6">
							<?= $options['instagram_username'] ?>
                        </div>
					<?php endif; ?>
                </div>
            </div>
            <p class="font-ggl is-uppercase pl-2 pb-2"><?= esc_html__( 'or just reach out to us at one of our screenings', 'gegenlicht' ) ?></p>
        </div>

    </div>

	<?php
}