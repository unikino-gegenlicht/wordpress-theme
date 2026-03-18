<?php

defined( 'ABSPATH' ) || exit;
define( "DONOTCACHEPAGE", true );
get_header();
do_action( 'wp_body_open' );
?>
    <article class="page-content content mt-4">
        <h1 class="is-size-2 no-separator"><?= get_theme_mod( "team_page_title" )[ substr( get_user_locale(), 0, 2 ) ] ?? "" ?></h1>
        <?php
        $raw = get_theme_mod( 'team_page_text' )[ substr( get_user_locale(), 0, 2 ) ] ?? "";
        echo apply_filters( "the_content", $raw );
        ?>
    </article>
<?php get_template_part( "src/partials/contact-block", args: [ "emailAddress" => get_theme_mod( "email_address" )["join"]  ?? ""] ) ?>
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

                foreach ( $members['active'] as $member ) :
                    ?>
                    <div class="cell pb-3">
                        <?php ggl_the_teamie_image( $member ); ?>
                        <hr class="separator mb-1"/>
                        <a href="<?= get_permalink( $member ) ?>"><h5
                                    class="is-size-5 mb-1"><?php ggl_the_teamie_name( $member ); ?></h5>
                        </a>
                        <p class="is-italic"> <?php ggl_teamie_joined_in( $member ); ?> </p>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <?php if ( isset( $members['former'] ) && count( $members['former'] ) > 0 )  : ?>
            <h2 class="is-size-3"><?= esc_html__( 'Former Members', 'gegenlicht' ) ?></h2>
            <div class="content">
                <?php
                $raw = get_theme_mod( 'team_former_members_text' )[ substr( get_user_locale(), 0, 2 ) ] ?? "";
                echo apply_filters( "the_content", $raw );
                ?>
            </div>
            <div class="fixed-grid has-2-cols-mobile has-4-cols-tablet">
                <div class="grid">
                    <?php
                    foreach ( $members['former'] as $member ) :
                    ?>
                    <div class="cell pb-3">
                        <?php ggl_the_teamie_image( $member ); ?>
                        <hr class="separator mb-1"/>
                        <a href="<?= get_permalink( $member ) ?>"><h5
                                    class="is-size-5 mb-1"><?php ggl_the_teamie_name( $member ); ?></h5>
                        </a>
                        <p class="is-italic"> <?php ggl_the_teamie_membership_duration( $member ); ?> </p>
                    </div>
                    <?php endforeach ?>
                </div>
            </div>
        <?php endif ?>
    </main>


<?php get_footer(); ?>