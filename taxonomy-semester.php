<?php

defined( 'ABSPATH' ) || exit;

$taxonomy = get_queried_object();
$data     = new WP_Query( array(
        'post_type'      => [ 'movie', 'event' ],
        'posts_per_page' => - 1,
        'tax_query'      => [
                [
                        'taxonomy' => 'semester',
                        'field'    => 'term_id',
                        'terms'    => $taxonomy->term_id,
                ]
        ],
        'meta_key'       => 'screening_date',
        'orderby'        => 'meta_value_num',
        'order'          => 'DESC',
) );

if ( function_exists( "ggl_cpt__serialize_icals" ) && function_exists( "ggl_cpt__generate_single_ical" ) ):
    $events = [];
    while ( $data->have_posts() ) : $data->the_post();
        $events[] = ggl_cpt__generate_single_ical( $data->post );
    endwhile;
    $data->rewind_posts();

    $outputIcs = get_query_var( "ics", false );
    if ( $outputIcs ):
        header( "Content-type: text/calendar; charset=utf-8" );
        header( 'Content-Disposition: attachment; filename="' . $taxonomy->name . '".ics' );

        echo ggl_cpt__serialize_icals( $events, false );

        return;
    endif;

    $serializedData = ggl_cpt__serialize_icals( $events );
endif;


get_header( args: [ "title" => $taxonomy->name ] );
do_action( 'wp_body_open' );

?>
    <main class="page-content mt-4">
        <article class="content">
            <header>
                <h1 class="is-size-2"><?= $taxonomy->name ?></h1>
            </header>
            <?= apply_filters( "the_content", $taxonomy->description ) ?>
        </article>

        <?php


        $screenings = [];
        while ( $data->have_posts() ) : $data->the_post();
            $screeningDate = (int) rwmb_get_value( "screening_date" );
            $title         = ggl_get_title();

            $screenings[ $screeningDate ][] = [ $title, get_permalink() ];
        endwhile;

        $archive_data = get_term_meta( $taxonomy->term_id, 'semester_shown_movies', true );
        if ( ! $archive_data ) {
            $archive_data = [];
        }
        $merge_archive_data = (bool) get_term_meta( $taxonomy->term_id, 'semester_add_archival_data', true );
        if ( ! $merge_archive_data && $archive_data != null ) {
            $screenings = [];
        }
        foreach ( $archive_data as $entry ) {
            $date                       = date_parse_from_format( "d.m.Y", $entry[0] );
            $timestamp                  = mktime( 20, 0, null, $date['month'], $date['day'], $date['year'] );
            $screenings[ $timestamp ][] = [ $entry[1], "" ];
        }

        ksort( $screenings );
        ?>
        <article>
            <div class="movie-list mb-6">
                <div class="movie-list-title is-flex is-align-items-center">
                    <?= esc_html__( "The Program", "gegenlicht" ) ?>
                    <span class="tag is-rounded is-medium is-primary ml-auto">
                    <a href="<?= $serializedData ?>"
                       download="<?= $taxonomy->name ?>.ics"
                       style="color: var(--bulma-body-color)">
                            <span class="icon is-medium"><span class="material-symbols"
                                                               style="font-size: 24px">calendar_add_on</span></span>
                    </a>
                </span>
                </div>
                <div class="movie-list-entries">
                    <?php foreach ( $screenings as $screeningDate => $titles ) : ?>
                        <?php foreach ( $titles as $data ) : ?>
                            <?php if ( $screeningDate > time() ) : ?>
                                <a role="link"
                                   aria-label="<?= $data[0] ?>. <?= esc_html__( 'Screening starts: ', 'gegenlicht' ) ?> <?= date( 'r', $screeningDate ) ?>"
                                   href="<?= $data[1] ?>"
                                   class="entry">
                                    <div>
                                        <p>
                                            <time datetime="<?= date( 'Y-m-d H:i:s', $screeningDate ) ?>">
                                                <?= date( GGL_LIST_DATETIME, $screeningDate ) ?>
                                            </time>
                                        </p>
                                        <h2 class="is-size-5 no-separator is-uppercase">
                                            <?= $data[0] ?>
                                        </h2>
                                    </div>
                                    <span class="icon">
                                    <span class="material-symbols">arrow_forward_ios</span>
                                 </span>
                                </a>
                            <?php else: ?>
                                <div class="entry">
                                    <div>
                                        <p><?= date( "d.m.Y", $screeningDate ) ?></p>
                                        <p class="font-ggl is-size-5 is-uppercase"><?= $data[0] ?></p>
                                    </div>
                                </div>
                            <?php endif; ?>
                        <?php endforeach ?>
                    <?php endforeach; ?>
                </div>
            </div>
        </article>


    </main>
<?php get_footer() ?>