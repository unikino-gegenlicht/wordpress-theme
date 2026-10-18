<?php
/*
 * Copyright (c) 2026. Unikino GEGENLICHT. Licensed under the EUPL v1.2 or later
 */
defined( 'ABSPATH' ) || exit;
define( "DONOTCACHEPAGE", true );

$query = new WP_Query( array(
        'post_type'      => 'screening',
        'posts_per_page' => - 1,
        'meta_query'     => array(
                'key'     => 'screening_start',
                'value'   => date( 'Y-m-d H:i:s' ),
                'compare' => '>=',
        ),
        'meta_key'       => 'screening_start',
        'orderby'        => 'meta_value',
        'order'          => 'ASC',
) );

$post = array_shift( $query->posts );
if ( $post === null ) {
    get_header( 'screening_break' );
} else {
    get_header();
    setup_postdata( $post );
}
?>
    <main class="mb-6 content-container">
        <article id="next-screening" class="pt-0 screening-info">
            <header>
                <p><?= esc_html__( 'Next screening', 'cinema-press-theme' ) ?></p>
                <p><?php rwmb_the_value( 'screening_start', [ 'format' => CINEMA_PRESS__DATETIME_FORMAT ] ) ?></p>
            </header>
            <div class="content">
                <h1 class="title"><?= rwmb_meta( 'original_title', post_id: rwmb_meta( 'movie' ) ) ?></h1>
                <?php if ( has_post_thumbnail() ): ?>
                    <figure class="image">
                        <picture>
                            <source class="is-16by9" media="(min-width: 576px)"
                                    srcset="<?php the_post_thumbnail_url( 'desktop' ) ?>"/>
                            <img src="<?php the_post_thumbnail_url( 'mobile' ) ?>"/>
                        </picture>
                    </figure>
                <?php elseif ( has_post_thumbnail( rwmb_meta( 'movie' ) ) ): $post = get_post( rwmb_meta( 'movie' ) );
                    setup_postdata( $post ); ?>
                    <figure class="image">
                        <picture>
                            <source class="is-16by9" media="(min-width: 576px)"
                                    srcset="<?php the_post_thumbnail_url( 'desktop' ) ?>"/>
                            <img src="<?php the_post_thumbnail_url( 'mobile' ) ?>"/>
                        </picture>
                    </figure>
                    <?php wp_reset_postdata(); endif; ?>
                <hr>
                <a class="button is-outlined is-uppercase font-readex is-fullwidth py-3 is-size-5"
                   href="<?= get_permalink() ?>">
                    <?= esc_html__( 'Open movie', 'cinema-press-theme' ) ?>
                </a>
            </div>
        </article>
        <?php
        $following_screening      = array_shift( $query->posts );
        if ( $following_screening !== null ) :
            $following_screening_start = new DateTime( rwmb_get_value( 'screening_start', post_id: $following_screening->ID ) );
            $next_screening_start = new DateTime( rwmb_get_value( 'screening_start' ) );

            $diff = $next_screening_start->diff( $following_screening_start );
            if ( $diff->y == 0 && $diff->m == 0 && $diff->d == 0 && $diff->h < 5 ):
                $post = $following_screening;
                setup_postdata( $post );
                ?>
                <article id="following-screening" class="mt-6 pt-0 screening-info">
                    <header>
                        <p><?= esc_html__( 'and following at', 'cinema-press-theme' ) ?></p>
                        <p><?php rwmb_the_value( 'screening_start', [ 'format' => CINEMA_PRESS__TIME_FORMAT ] ) ?></p>
                    </header>
                    <div class="content">
                        <h1 class="title"><?= rwmb_meta( 'original_title', post_id: rwmb_meta( 'movie' ) ) ?></h1>
                        <?php the_post_thumbnail(); ?>
                        <hr>
                        <a class="button is-outlined is-uppercase font-readex is-fullwidth py-3 is-size-5"
                           href="<?= get_permalink() ?>">
                            <?= esc_html__( 'Open movie', 'cinema-press-theme' ) ?>
                        </a>
                    </div>
                </article>
            <?php
            endif;
        endif;


        wp_reset_postdata();

        $sorted_screenings = array();
        foreach ( $query->posts as $post ) :
            $sorted_screenings[ rwmb_the_value( 'screening_start', [ 'format' => CINEMA_PRESS__TIME_FORMAT ], $post->ID, false ) ] = $post;
        endforeach;
        ?>

        <?php if ( empty( $sorted_screenings ) ): ?>
            <div class="content mt-3">
                <h2><?= esc_html__('Sadly, there is no more program for this semester', 'cinema-press-theme') ?></h2>
                <?php echo wpautop("") ?>
            </div>
        <?php else: ?>

        <?php endif; ?>


    </main>
<?php get_footer(); ?>