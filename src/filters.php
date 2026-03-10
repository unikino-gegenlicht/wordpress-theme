<?php
add_action( "after_setup_theme", function () {
    add_filter( "ggl__show_full_details", "ggl_theme__default_show_full_details", accepted_args: 2 );
} );
function ggl_theme__default_show_full_details( bool $display_details, WP_Post $post ): bool {
    return ( rwmb_get_value( "license_type", post_id: $post->ID ) == "full" || is_user_logged_in() );
}

add_filter( 'protected_title_format', 'ggl_theme__remove_protected_protected_from_titles' );
function ggl_theme__remove_protected_protected_from_titles( $format ) {
    return '%s';
}

add_filter('private_title_format', 'ggl_theme__remove_protected_protected_from_titles' );

add_filter( "the_password_form", "ggl_theme__default_password_form", accepted_args: 3 );
function ggl_theme__default_password_form( $output, $post, $invalid_pw ): string {
    global $post;
    $label    = "pw-box-" . $post->ID ?: rand();
    $pw_wrong = false;

    if ( ! empty( $post->ID ) && wp_get_raw_referer() === get_permalink( $post->ID ) && isset( $_COOKIE[ 'wp-postpass_' . COOKIEHASH ] ) ) {
        $pw_wrong = true;

    }

    ob_start();
    ?>
    <div class="post-password-form">
        <p><?= esc_html__( "The content of this page is protected by a password. If you received a password for displaying this page please enter it below to unlock the contents on this page", "gegenlicht" ) ?></p>
        <form action="<?= esc_url( site_url( 'wp-login.php?action=postpass', 'login_post' ) ) ?>" method="post">
            <?php if ( ! empty( $post->ID ) ): ?>
                <input type="hidden" name="redirect_to" value="<?= esc_attr( get_permalink( $post->ID ) ) ?>">
            <?php endif; ?>
            <div class="field">
                <label class="label"><?= esc_html__("Password", "gegenlicht") ?></label>
                <div class="control">
                    <input class="input <?= $pw_wrong ? 'is-danger' : '' ?>" name="post_password" type="password"
                           placeholder="<?= esc_html__( "Password", "gegenlicht" ) ?>">
                </div>
                <?php if ( $pw_wrong ) : ?>
                    <p class="help is-danger"><?= esc_html__("The password you entered is not correct. Please try again", "gegenlicht") ?></p>
                <?php endif; ?>
            </div>
            <div class="field">
                <div class="control">
                    <input type="submit" name="Submit" class="input button"
                           value="<?= esc_attr__( 'Submit', 'text-domain' ) ?>"/>
                </div>
            </div>
        </form>
    </div>

    <?php
    $output = ob_get_clean();

    return $output;

}
