<?php
/*
 * Copyright (c) 2026. Unikino GEGENLICHT. Licensed under the EUPL v1.2 or later
 */
defined( 'ABSPATH' ) || exit;
define( "DONOTCACHEPAGE", true );

$semester_id = get_theme_mod( 'displayed_semester' );

$query = new WP_Query( array(
        'post_type'      => 'screening',
        'posts_per_page' => - 1,
        'meta_query'     => array(
                'key'     => 'screening_start',
                'value'   => date( 'Y-m-d H:i:s' ),
                'compare' => '>=',
        ),
        'tax_query'      => array(
                array(
                        'taxonomy' => 'semester',
                        'terms'    => $semester_id,
                )
        ),
        'meta_key'       => 'screening_start',
        'orderby'        => 'meta_value',
        'order'          => 'ASC',
) );
$post  = array_shift( $query->posts );
if ( $post !== null ):
    get_header();
    setup_postdata( $post );
    ?>
    <main class="mb-6 content-container">
        <article id="next-screening" class="pt-0 mt-3 screening-info">
            <header>
                <p><?= esc_html__( 'Next screening', 'cinema-press-theme' ) ?></p>
                <p><?php rwmb_the_value( 'screening_start', [ 'format' => CINEMA_PRESS__DATETIME_FORMAT ] ) ?></p>
            </header>
            <div class="content">
                <h1 class="title"><?= rwmb_meta( 'original_title', post_id: rwmb_meta( 'movie' ) ) ?></h1>
                <?php
                if ( has_post_thumbnail() ):
                    the_post_thumbnail();
                elseif ( has_post_thumbnail( rwmb_meta( 'movie' ) ) ): $post = get_post( rwmb_meta( 'movie' ) );
                    setup_postdata( $post );
                    the_post_thumbnail();
                    wp_reset_postdata();
                endif; ?>
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
            else:
                $query->posts[] = $following_screening;
            endif;
        endif;


        wp_reset_postdata();

        $sorted_screenings = array();
        foreach ( $query->posts as $post ) :
            $screening_date                                        = date_create( rwmb_get_value( 'screening_start', [ 'format' => 'd.m.Y H:i:s' ], $post->ID ) );
            $sorted_screenings[ $screening_date->format( 'n' ) ][] = $post;
        endforeach;

        ksort( $sorted_screenings );
        ?>

        <?php if ( empty( $sorted_screenings ) ): ?>
            <div class="content mt-3">
                <h2><?= esc_html__( 'Sadly, there is no more program for this semester', 'cinema-press-theme' ) ?></h2>
                <?php echo wpautop( get_theme_mod( 'semester_break_premonition_text' ) ) ?>
            </div>
        <?php else: ?>
            <div class="content mt-6">
                <h2><?= esc_html__( 'The Semester Programme', 'cinema-press-theme' ) ?></h2>
                <?php foreach ( $sorted_screenings as $month => $screenings ): $monthObj = date_create_from_format( '!m', $month ); ?>
                    <div class="movie-list mb-5">
                        <p class="movie-list-title mb-0"><?= esc_html__( $monthObj->format( "F" ) ) ?></p>
                        <div class="movie-list-entries">
                            <?php foreach ( $screenings as $post ): setup_postdata( $post ); ?>
                                <a href="<?php the_permalink(); ?>"
                                   class="entry is-flex-direction-row is-align-items-center">
                                    <div>
                                        <p class="m-0"><?php rwmb_the_value( 'screening_start', [ 'format' => CINEMA_PRESS__DATETIME_FORMAT ], $post->ID ) ?></p>
                                        <h2 class="movie-title is-size-5 mt-0"><?php rwmb_the_value( 'german_title', post_id: rwmb_meta( 'movie' ) ) ?></h2>
                                    </div>
                                    <span class="icon">
                                        <i class="fa-light fa-chevron-right"></i>
                                    </span>
                                </a>
                            <?php endforeach;
                            wp_reset_postdata(); ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>


    </main>
<?php else: get_header( 'semester_break' ) ?>
    <main class="mb-6 content-container content">
        <h1 class="title"><?= esc_html__( 'Intermission', 'cinema-press-theme' ) ?></h1>
        <figure class="image">
            <picture style="filter: saturate(0);">
                <source class="is-16by9" media="(min-width: 576px)"
                        srcset="<?= wp_get_attachment_image_srcset( get_theme_mod( 'semester_break_image' ), size: 'desktop' ) ?: get_theme_file_uri( 'static/img/semester-break.jpg' ) ?>"/>
                <?= wp_get_attachment_image( get_theme_mod( 'semester_break_image' ), size: 'mobile', attr: [ 'class' => 'is-3by4' ] ) ?: '<img src=' . get_theme_file_uri( 'static/img/semester-break.jpg' ) . '/>' ?>
            </picture>
        </figure>
        <h2><?= esc_html( get_theme_mod( 'semester_break_tagline' ) ) ?></h2>
        <?= apply_filters( 'the_content', get_theme_mod( 'semester_break_text' ) ) ?>
    </main>
<?php endif; ?>
<?php if ( in_array( "team", get_theme_mod( 'displayed_blocks', [] ) ) ): ?>
    <style>
        #team-intro {
            --bulma-body-color: <?= get_theme_mod('team_block_text_color', 'inherit') ?>;
            --bulma-body-background-color: <?= get_theme_mod('team_block_background', 'inherit') ?>;
            --bulma-content-heading-color: var(--bulma-body-color) !important;

            background: var(--bulma-body-background-color);
            color: var(--bulma-body-color);

            .content {
                --bulma-content-heading-color: var(--bulma-body-color) !important;

            }

        }

        @media (prefers-color-scheme: dark) {
            #team-intro {
                --bulma-body-color: <?= get_theme_mod('team_block_text_color_dark', 'inherit') ?>;
                --bulma-body-background-color: <?= get_theme_mod('team_block_background_dark', 'inherit') ?>;
            }
        }
    </style>
    <article id="team-intro" class="py-3">
        <div class="content-container content">
            <h2><?= get_theme_mod( 'team_block_title' ) ?></h2>
            <?= apply_filters('the_content', get_theme_mod( 'team_block_text' ) ) ?>
            <a class="button is-outlined is-fullwidth is-uppercase font-heading has-text-weight-extrabold py-2 is-size-5" href="<?= get_post_type_archive_link('team_member') ?>">
                <?= esc_html__('To our team members', 'cinema-press-theme') ?>
            </a>
        </div>
    </article>

<?php endif; ?>
<?php get_footer(); ?>