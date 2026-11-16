<?php
/*
 * Copyright (c) 2026. Unikino GEGENLICHT. Licensed under the EUPL v1.2 or later
 */
defined( 'ABSPATH' ) || exit;

use CinemaPress\post_types\Movie;
use CinemaPress\post_types\Screening;


get_header();
$movie = get_post( rwmb_meta( 'movie' ) );
?>
<main class="content-container mt-3">
    <header class="is-flex is-justify-content-space-between ">
        <p><?= esc_html__( 'Screening starts', 'cinema-press-theme' ) ?></p>
        <p><?php rwmb_the_value( 'screening_start', [ 'format' => CINEMA_PRESS__DATETIME_FORMAT ] ) ?></p>
    </header>
    <hr>
    <div class="content">
        <div>
            <h1 class="mb-1 font-heading is-uppercase"><?= Movie::title( $movie ) ?></h1>
            <p class="font-heading is-uppercase is-size-5">
                <abbr title="<?= esc_attr__( 'Original Title', 'cinema-press-theme' ) ?>">OT</abbr>&colon;&ensp;<?= Movie::original_title( $movie ) ?>
            </p>
        </div>
        <hr>
        <p class="mb-0">
            <?= join( "/", iterator_to_array( Movie::countries( $movie ) ) ) ?>
            <?= Movie::release_year( $movie ) ?>
        </p>
        <p class="mb-0">
            <?php
            $directors = iterator_to_array( Movie::directors( $movie ) );
            if ( count( $directors ) === 1 ) {
                echo sprintf( __(/* translators: the name of the actor in %s */ "by %s", 'cinema-press-theme' ), $directors[0] );
            } else {
                $last_director = array_pop( $directors );
                $str1          = join( "/", $directors );
                echo sprintf( __( /* translators: %1$s is a comma separated list, %2$s is the last listed director */ 'by %1$s and %2$s', 'cinema-press-theme' ), $str1, $last_director );
            }
            ?>
        </p>
        <p class="mb-0">
            <?php
            $actors = iterator_to_array( Movie::actors( $movie ) );
            if ( count( $actors ) === 1 ) {
                echo sprintf( __(/* translators: the name of the actor in %s */ "with %s", 'cinema-press-theme' ), $actors[0] );
            } else {
                $last_actor = array_pop( $actors );
                $str1       = join( "/", $actors );
                echo sprintf( __( /* translators: %1$s is a comma separated list, %2$s is the last listed actor */ 'with %1$s and %2$s', 'cinema-press-theme' ), $str1, $last_actor );
            }

            ?>
        </p>
        <hr>
        <div class="tags are-medium is-flex-grow-1 mb-3">
            <span class="tag is-rounded is-primary"><?= esc_html__( 'Age Rating', 'cinema-press-theme' ) ?>&colon;&nbsp;<?= Movie::age_rating( $movie ) ?></span>
            <?php if ( empty( Screening::audio_language( $post ) ) || Screening::audio_language( $post ) === null ): ?>
                <span class="tag is-rounded is-primary"><?= esc_html__( 'Silent Movie', 'cinema-press-theme' ) ?></span>
            <?php else: ?>
                <span class="tag is-rounded is-primary"><?= esc_html__( 'Audio in', 'cinema-press-theme' ) ?>&nbsp;<?= Screening::audio_language( $post, as_code: false ) ?></span>
            <?php endif; ?>

            <?php if ( empty( Screening::subtitle_language( $post ) ) || Screening::subtitle_language( $post ) === null ): ?>
                <?php if ( empty( Screening::audio_language( $post ) ) || Screening::audio_language( $post ) === null ): ?>
                    <span class="tag is-rounded is-danger"><?= esc_html__( 'Without Intertitles', 'cinema-press-theme' ) ?></span>
                <?php else: ?>
                    <span class="tag is-rounded is-danger"><?= esc_html__( 'No Subtitles', 'cinema-press-theme' ) ?></span>
                <?php endif; ?>
            <?php else: ?>
                <span class="tag is-rounded is-primary"><?= esc_html__( 'Subtitles in', 'cinema-press-theme' ) ?>&nbsp;<?php rwmb_the_value( 'subtitle_language' ) ?></span>
            <?php endif; ?>

        </div>
        <?php the_post_thumbnail() ?>
        <hr>
        <?php do_action( 'cinema_press_theme_render_reservation_button' ) ?>
        <h2 class="mt-0 is-uppercase"> <?= esc_html__( 'What is the movie about?', 'cinema-press-theme' ) ?></h2>
        <?php Screening::summary( $post, display: true ) ?>
        <?php if ( Screening::worth_to_see( $post ) ): ?>
            <h2 class="mt-0 is-uppercase"> <?= esc_html__( 'Why is the movie worth seeing?', 'cinema-press-theme' ) ?></h2>
            <?php Screening::summary( $post, display: true ) ?>
        <?php endif; ?>
        <?php do_action( 'cinema_press_theme_render_reservation_button' ) ?>
    </div>
</main>
<?php
$available_proposer_types = [
        ( get_option( 'cinema-press' )["enable_teamie_screening_assignment"] ?? 0 ) === 1 ? 'team_member' : null,
        ( get_option( 'cinema-press' )["enable_cooperation_screening_assignment"] ?? 0 ) === 1 ? 'cooperation_partner' : null
];
$available_proposer_types = array_filter( $available_proposer_types, function ( $type ) {
    return $type !== null;
} );
?>
<?php if ( ! empty( $available_proposer_types ) && ! empty( rwmb_meta( 'proposed_by' ) ) ): ?>
    <aside class="has-background-text pt-1 pb-3 mt-5">
        <div class="content mt-3 content-container">
            <?php
            $proposers = rwmb_meta( 'proposed_by' );
            if ( count( $proposers ) === 1 ) {
                $proposed_by_str = sprintf(/* translators: %s is the name of the proposer */ __( "This movie was proposed by %s", 'cinema-press-theme' ), get_the_title( $proposers[0] ) );
            } else {
                $last_proposer   = array_pop( $proposers );
                $str1            = join( ", ", array_map( function ( $proposer ) {
                    return get_the_title( $proposer );
                }, $proposers ) );
                $proposed_by_str = sprintf(/* translators: %1$s is a comma separated list, %2$s is the last listed proposer */ __( 'This movie was proposed by %1$s and %2$s', 'cinema-press-theme' ), $str1, get_the_title( $last_proposer ) );
                $proposers[]     = $last_proposer;
            }
            ?>
            <h3><?= $proposed_by_str ?></h3>
            <?php if ( count( $proposers ) === 1 ): ?>
                <div class="is-flex is-align-items-top is-flex-wrap-wrap is-justify-content-center is-gap-0.5">
                    <div class="proposing-teamie">
                        <?php echo get_the_post_thumbnail( $proposers[0] ); ?>
                    </div>
                    <?php
                    $query = new WP_Query( array(
                            'post_type'      => 'screening',
                            'posts_per_page' => 5,
                            'post__not_in'   => array( $post->ID ),
                            'meta_query'     => array(
                                    'relation' => 'AND',
                                    array(
                                            'key'     => 'screening_start',
                                            'value'   => date( 'Y-m-d H:i:s' ),
                                            'compare' => '<='
                                    ),
                                    array(
                                            'key'     => 'proposed_by',
                                            'value'   => [ $proposers[0] ],
                                            'compare' => 'IN'
                                    )
                            ),
                            'orderby'        => 'rand',
                    ) );

                    if ( $query->have_posts() ):
                        get_template_part( 'templates/screening-list', args: [
                                "title"             => esc_html__( 'Other proposals', 'cinema-press-theme' ),
                                "screenings"        => $query->posts,
                                "future_screenings" => false,
                        ] );
                    endif;
                    ?>
                </div>
            <?php else: ?>
                <div class="">
                    <div class="multiple-proposers"
                         style="overflow-x: auto; width: 100%;">

                        <?php foreach ( $proposers as $proposer ): ?>
                            <div class="proposing-teamie is-align-content-center">
                                <?php echo get_the_post_thumbnail( $proposer ); ?>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    <?php
                    $query = new WP_Query( array(
                            'post_type'      => 'screening',
                            'posts_per_page' => 10,
                            'post__not_in'   => array( $post->ID ),
                            'meta_query'     => array(
                                    'relation' => 'AND',
                                    array(
                                            'key'     => 'screening_start',
                                            'value'   => date( 'Y-m-d H:i:s' ),
                                            'compare' => '<='
                                    ),
                                    array(
                                            'key'     => 'proposed_by',
                                            'value'   => $proposers,
                                            'compare' => 'IN'
                                    )
                            ),
                            'orderby'        => 'rand',
                    ) );

                    if ( $query->have_posts() ):
                        get_template_part( 'templates/screening-list', args: [
                                "title"             => esc_html__( 'Other proposals', 'cinema-press-theme' ),
                                "screenings"        => $query->posts,
                                "future_screenings" => false,
                        ] );
                    endif;
                    ?>
                </div>
            <?php endif ?>
        </div>
    </aside>
<?php endif; ?>
<?php get_footer( args: ( ! empty( $available_proposer_types ) && ! empty( rwmb_meta( 'proposed_by' ) ) ) ? [] : [ "classes" => [ "mt-6" ] ] ); ?>
