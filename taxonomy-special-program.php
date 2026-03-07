<?php
defined( 'ABSPATH' ) || exit;

$taxonomy = get_queried_object();
get_header( args: [ "title" => $taxonomy->name ] );
do_action( 'wp_body_open' );


$anonymousImage      = get_term_meta( $taxonomy->term_id, 'anonymous_image', true );
$backgroundColor     = get_term_meta( $taxonomy->term_id, 'background_color', true );
$textColor           = get_term_meta( $taxonomy->term_id, 'text_color', true );
$backgroundColorDark = get_term_meta( $taxonomy->term_id, 'dark_background_color', true );
$textColorDark       = get_term_meta( $taxonomy->term_id, 'dark_text_color', true );

$logo     = get_term_meta( $taxonomy->term_id, 'logo', true );
$logoDark = get_term_meta( $taxonomy->term_id, 'logo_dark', true );
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
<main class="page-content mt-4">

    <article class="content">
        <header class="has-text-centered">
            <picture>
                <source srcset="<?= wp_get_attachment_image_url( $logoDark, 'full' ); ?>"
                        media="(prefers-color-scheme: dark)">
                <source srcset="<?= wp_get_attachment_image_url( $logo, 'full' ); ?>">
                <img style="max-height: 300px" height="300" src="<?= wp_get_attachment_image_url( $logo ) ?>">
            </picture>
            <h1 class="is-size-2 has-text-left"><?= $taxonomy->name ?></h1>
        </header>
        <?php
        echo apply_filters( "the_content", $taxonomy->description );
        ?>
    </article>
    <?php
    $query = new WP_Query( array(
            'post_type'      => [ 'movie' ],
            'posts_per_page' => - 1,
            'meta_query'     => [
                    [
                            'key'     => 'screening_date',
                            'value'   => time(),
                            'compare' => '>',
                    ]
            ],
            'tax_query'      => array(
                    array(
                            'taxonomy' => 'special-program',
                            'terms'    => $taxonomy->term_id,
                    )
            ),
            'meta_key'       => 'screening_date',
            'orderby'        => 'meta_value_num',
            'order'          => 'ASC',
    ) );

    get_template_part( "src/partials/movie-list", args: [
            "title" => esc_html__( "Upcoming in this special program", 'gegenlicht' ),
            "posts" => $query->posts,
    ] )
    ?>
    <div class="movie-list mb-6">
        <div class="movie-list-title">
            <?= esc_html__( 'The program hosted', 'gegenlicht' ) ?>
        </div>
        <?php
        $query = new WP_Query( array(
                'post_type'      => [ 'movie' ],
                'posts_per_page' => - 1,
                'meta_query'     => [
                        [
                                'key'     => 'screening_date',
                                'value'   => time(),
                                'compare' => '<=',
                        ]
                ],
                'tax_query'      => array(
                        array(
                                'taxonomy' => 'special-program',
                                'terms'    => $taxonomy->term_id,
                        )
                )
        ) );
        ?>
        <div class="movie-list-entries">
            <?php while ( $query->have_posts() ) : $query->the_post(); ?>
            <div class="entry">
                <div>
                    <?php if ( rwmb_get_value("screening_date") !== 0 ): ?>
                        <p><?= date( "d.m.Y", rwmb_get_value("screening_date") ) ?></p>
                    <?php endif; ?>
                    <p class="font-ggl is-size-5 is-uppercase"><?= ggl_get_title( $query->post ) ?></p>
                </div>
            </div>
            <?php endwhile; ?>
        </div>
    </div>
</main>