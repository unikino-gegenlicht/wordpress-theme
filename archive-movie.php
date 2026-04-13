<?php
defined( 'ABSPATH' ) || exit;
get_header( args: [ "title" => __( "Archive", "gegenlicht" ) ] );
define( "DONOTCACHEPAGE", true );

$semesters = get_terms( array(
        'taxonomy'   => 'semester',
        'hide_empty' => false,
) );

$semesterScreenings = [];
foreach ( $semesters as $semester ) {
    $semesterStart                        = (int) get_term_meta( $semester->term_id, 'semester_start', true );
    $semesterScreenings[ $semesterStart ] = $semester;
}

krsort( $semesterScreenings );
?>
    <main class="page-content mt-4">
        <article class="content">
            <header>
                <h1><?= get_theme_mod( 'archive_header' )[ substr(get_user_locale(), 0, 2)  ] ?? "" ?></h1>
            </header>
            <?= apply_filters( "the_content", get_theme_mod( 'archive_text' )[ substr(get_user_locale(), 0, 2)  ] ?? "" ) ?>
        </article>
        <hr class="separator"/>
        <?php
        foreach ( $semesterScreenings as $timestamp => $semester ):
            $screenings = [];
            $archived_screenings = ggl_get_semester_archived_screenings( $semester );
            foreach ($archived_screenings as $screening_date => $titles) {
                foreach ($titles as $title) {
                    $screenings[$screening_date][] = $title[0];
                }
            }
            try {
                $now = new DateTime( timezone: new DateTimeZone( wp_timezone_string() ) );
            } catch ( DateInvalidTimeZoneException|DateMalformedStringException $e ) {
                $now = new DateTime();
            }
            $data = new WP_Query( array(
                    'post_type'      => [ 'movie', 'event' ],
                    'posts_per_page' => - 1,
                    'meta_query'     => [
                            [
                                    'key'     => 'screening_date',
                                    'value'   => $now->getTimestamp() + $now->getOffset(),
                                    'compare' => '<=',
                            ]
                    ],
                    'tax_query'      => [
                            [
                                    'taxonomy' => 'semester',
                                    'field'    => 'term_id',
                                    'terms'    => $semester->term_id,
                            ]
                    ],
                    'meta_key'       => 'screening_date',
                    'orderby'        => 'meta_value_num',
                    'order'          => 'DESC',
            ) );

            while ( $data->have_posts() ) : $data->the_post();
                $screeningDate = (int) rwmb_get_value( "screening_date" );
                $title         = ggl_get_localized_title();

                $screenings[ $screeningDate ][] = $title;
            endwhile;

            ksort( $screenings );
            if ( empty( $screenings ) ) {
                continue;
            }
            ?>
            <article id="<?= $semester->slug ?>">
                <div class="movie-list mb-6">
                    <div class="movie-list-title">
                        <?= $semester->name ?>
                    </div>
                    <div class="movie-list-entries">
                        <?php foreach ( $screenings as $screeningDate => $movies ): ?>
                            <?php foreach ( $movies as $movie ) : ?>
                                <div class="entry">
                                    <div>
                                        <?php if ( $screeningDate !== 0 ): ?>
                                            <p><?= date( "d.m.Y", $screeningDate ) ?></p>
                                        <?php endif; ?>
                                        <p class="font-ggl is-size-5 is-uppercase"><?= $movie ?></p>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php endforeach; ?>
                    </div>
                </div>
            </article>
        <?php
        endforeach;
        ?>
    </main>
<?php get_footer();