<?php
$fullwidth       = (bool) ( $args['fullwidth'] ?? false );
$showPhoneNumber = (bool) ( $args['showPhoneNumber'] ?? false );
$showEmail       = (bool) ( $args['showEmail'] ?? true );
$showInstagram   = (bool) ( $args['showInstagram'] ?? true );
$showMastodon    = (bool) ( $args['showMastodon'] ?? true );
$showLetterboxd  = (bool) ( $args['showLetterboxd'] ?? false );

$phoneNumber        = (string) ( $args['phoneNumber'] ?? get_theme_mod( 'contact_info' )["phoneNumber"] ?? "" );
$emailAddress       = (string) ( $args['emailAddress'] ?? get_theme_mod( 'contact_info' )["emailAddress"] ?? "" );
$instagramUsername  = (string) ( $args['instagramUsername'] ?? get_theme_mod( 'social_medias' )["instagram"] ?? "" );
$mastodonUrl        = (string) ( $args['mastodonUrl'] ?? get_theme_mod( 'social_medias' )["mastodonURL"] ?? "" );
$letterboxdUsername = (string) ( $args['letterboxdUsername'] ?? get_theme_mod( 'social_medias' )["letterboxd"] ?? "" );

$mastodonUsername = "";
if (!empty($mastodonUrl)) {
    $parts = explode( "/", $mastodonUrl );
    $mastodonUsername = $parts[3] . '@' . $parts[2];
}

$backgroundColorDark = (string) ( $args['colors']['background']['dark'] ?? get_theme_mod( 'contact_partial' )['background']['dark'] ?? 'black' );
$backgroundColor     = (string) ( $args['colors']['background']['light'] ?? get_theme_mod( 'contact_partial' )['background']['light'] ?? 'black' );

$textColorDark = (string) ( $args['colors']['text']['dark'] ?? get_theme_mod( 'contact_partial' )['text']['dark'] ?? '#ffdd00' );
$textColor     = (string) ( $args['colors']['text']['light'] ?? get_theme_mod( 'contact_partial' )['text']['light'] ?? '#ffdd00' );
?>

<style>
    .contact-block {
        --bulma-body-background-color: <?= $backgroundColor ?>;
        --bulma-body-color: <?= $textColor ?>;

        --bulma-content-table-cell-padding: 0.25rem 0;
        --bulma-content-table-cell-border: none;

        .table {
            --bulma-table-background-color: var(--bulma-body-background-color);
            --bulma-table-color: var(--bulma-body-color);
            border-collapse: separate;
            border-spacing: 0.5rem 0;
        }

        background-color: var(--bulma-body-background-color);
        color: var(--bulma-body-color);

        padding: 0.5rem 0;
        margin: 1rem 0;

        .info {
            max-width: var(--max-content-width) !important;
            margin: 0 auto !important;
            padding: 0 var(--content-padding) !important;
        }
    }
</style>
<article class="contact-block content">
    <div class="info">
        <h2 class="is-size-3 is-uppercase pt-4">
			<?= esc_html__( "Contact Us", "gegenlicht" ) ?>
        </h2>
        <table class="table is-fullwidth">
			<?php if ( $showPhoneNumber ) : ?>
                <tr>
                    <td><?= esc_html__( "Telephone", "gegenlicht" ) ?></td>
                    <td><?= $phoneNumber ?></td>
                </tr>
			<?php endif; ?>
			<?php if ( $showEmail && ! empty( $emailAddress ) ) : ?>
                <tr>
                    <td><?= esc_html__( "e-mail", "gegenlicht" ) ?></td>
                    <td><?= preg_replace( "/@/", " (at) ", $emailAddress ) ?></td>
                </tr>
			<?php endif; ?>
			<?php if ( $showInstagram && ! empty( $instagramUsername ) ) : ?>
                <tr>
                    <td><?= esc_html__( "Instagram", "gegenlicht" ) ?></td>
                    <td><?= $instagramUsername ?></td>
                </tr>
			<?php endif; ?>
			<?php if ( $showMastodon && ! empty( $mastodonUsername ) ) : ?>
                <tr>
                    <td><?= esc_html__( "Mastodon", "gegenlicht" ) ?></td>
                    <td><?= $mastodonUsername ?></td>
                </tr>
			<?php endif; ?>
			<?php if ( $showLetterboxd && ! empty( $letterboxdUsername ) ) : ?>
                <tr>
                    <td><?= esc_html__( "Letterboxd", "gegenlicht" ) ?></td>
                    <td><?= $letterboxdUsername ?></td>
                </tr>
			<?php endif; ?>
        </table>
        <p class="is-size-5 is-uppercase font-ggl pb-4">
			<?= esc_html__( "Or just chat us up at one of our screenings", "gegenlicht" ) ?>
        </p>
    </div>
</article>