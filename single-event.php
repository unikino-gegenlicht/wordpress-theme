<?php
defined( 'ABSPATH' ) || exit;


get_header();
do_action( 'wp_body_open' );
$anonymize        = ! apply_filters( "ggl__show_full_details", false, $post );
$isSpecialProgram = rwmb_get_value( "program_type" ) === "special_program";

?>
<main>
    <header class="page-content">
        <div class="screening-information py-0">
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
        <div class="content mb-0">
            <h1 role="heading" class="mb-0 no-separator pt-2"><?= ggl_get_title() ?></h1>
        </div>
        <hr class="separator"/>
        <div class="mt-2">
            <p>
                <?= rwmb_meta( 'duration' ) ?> <?= esc_html__( 'Minutes', 'gegenlicht' ) ?>
            </p>
        </div>
        <hr class="separator"/>
        <div class="tags are-medium">
            <?php if ( rwmb_get_value( "language" ) ): ?>
                <span class="tag is-rounded is-primary"><?= esc_html__( rwmb_meta( 'language' ), "gegenlicht" ) ?></span>
            <?php endif; ?>
            <?php if ( rwmb_get_value( "age_restricted" ) ): ?>
                <span class="tag is-rounded is-primary"><?= sprintf( /* translators: %d Minimal Attendee Age*/ esc_html__( "For Ages %d+", "gegenlicht" ), (int) rwmb_get_value( "minimal_age" ) ) ?></span>
            <?php endif; ?>
            <?php
            if (
                    function_exists( 'ggl_cpt__generate_single_ical' ) &&
                    function_exists( 'ggl_cpt__serialize_icals' )
            ):
                $ical = ggl_cpt__generate_single_ical( $post );
                $serializedData = ggl_cpt__serialize_icals( [ $ical ] );
                ?>
                <span class="tag is-rounded is-primary ml-auto">
                    <a href="<?= $serializedData ?>"
                       download="<?= ggl_get_title() ?>.ics"
                       style="color: var(--bulma-body-color)">
                            <span class="icon is-medium"><span class="material-symbols"
                                                               style="font-size: 24px">calendar_add_on</span></span>
                    </a>
                </span>
            <?php endif; ?>
        </div>
        <?php ggl_the_post_thumbnail() ?>
        <?php if ( $isSpecialProgram ): ?>
            <div class="boxed-text mt-3">
                <?= apply_filters( "the_content", rwmb_get_value( "special_program" )->description ) ?>
            </div>
        <?php endif; ?>
        <?php if ( get_theme_mod("main_screening_location", null) != null && get_theme_mod( "main_screening_location" ) != rwmb_get_value( "screening_location" ) ) : $screening_location = get_post( rwmb_get_value( "screening_location" ) ); ?>
            <div class="content-notice mt-2 p-2 content">
                <h2 class="is-size-4 border-is-background-color"><?= esc_html__( "Changed Screening Location", "gegenlicht" ) ?></h2>
                <p>
                    <?= esc_html__( "This screening is not taking place at our usual screening location. Instead the screening will be hosted at:", "gegenlicht" ) ?>
                    <?= $screening_location->post_title ?>,
                    <?= rwmb_get_value( "street", post_id: $screening_location->ID ) ?>,
                    <?= rwmb_get_value( "postal_code", post_id: $screening_location->ID ) ?> <?= rwmb_get_value( "city", post_id: $screening_location->ID ) ?>
                </p>
                <?php
                $lat = rwmb_get_value( "lat", post_id: $screening_location->ID );
                $lng = rwmb_get_value( "long", post_id: $screening_location->ID );
                $name = $screening_location->post_title;
                get_template_part( 'src/partials/button', args: [
                        'href'     => "geo:$lat,$lng?q=$lat,$lng(" . rawurlencode( $name ) . ")",
                        'content'  => __( 'Show on a Map', 'gegenlicht' ),
                        'external' => true
                ] ); ?>
            </div>
        <?php endif; ?>
    </header>
    <?php if ( time() < rwmb_get_value( 'screening_date' ) && ! empty( trim( rwmb_get_value( "pretix_event_url" ) ) ) ): ?>
        <div class="reservation-button">
            <div class="page-content">
                <?php get_template_part( 'src/partials/button', args: [
                        'href'     => rwmb_get_value( "pretix_event_url" ),
                        'content'  => esc_html__( 'Reserve Now', 'gegenlicht' ),
                        'external' => true,
                        'icon'     => 'confirmation_number'
                ] ) ?></div>
        </div>
    <?php endif; ?>
    <article class="page-content px-2 mt-4 content">
        <?php if ( rwmb_meta( 'show_content_notice' ) ): ?>
            <div class="content-notice mb-6 p-2">
                <h2 class="is-size-4 border-is-background-color"><?= esc_html__( "Content Notice", 'gegenlicht' ) ?></h2>
                <p>
                    <?= apply_filters( "the_content", rwmb_get_value( 'content_notice' ) ?? "" ) ?>
                </p>
            </div>
        <?php endif; ?>
        <h2 class="font-ggl is-size-3 is-uppercase">
            <?= esc_html__( 'What the event is about', 'gegenlicht' ) ?>
        </h2>
        <?= apply_filters( "the_content", ggl_get_summary() ) ?>
        <h2 class="font-ggl is-size-3 is-uppercase mt-6">
            <?= esc_html__( "Why it's worth attending", 'gegenlicht' ) ?>
        </h2>
        <?= apply_filters( "the_content", ggl_get_worth_to_see() ) ?>
    </article>
    <?php if ( time() < rwmb_get_value( 'screening_date' ) && ! empty( trim( rwmb_get_value( "pretix_event_url" ) ) ) ): ?>
        <div class="reservation-button">
            <div class="page-content">
                <?php get_template_part( 'src/partials/button', args: [
                        'href'     => rwmb_get_value( "pretix_event_url" ),
                        'content'  => esc_html__( 'Reserve Now', 'gegenlicht' ),
                        'external' => true,
                        'icon'     => 'confirmation_number'
                ] ) ?></div>
        </div>
    <?php endif; ?>
</main>
<section id="proposed-by">
    <?php
    get_template_part( "src/partials/proposal-list" )
    ?>
</section>
<?php get_footer(); ?>



