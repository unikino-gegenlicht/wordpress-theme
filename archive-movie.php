<?php
defined( 'ABSPATH' ) || exit;
get_header(args: ["title" => __("Archive", "gegenlicht")]);
define( "DONOTCACHEPAGE", true );

$semesters = get_terms( array(
	'taxonomy'   => 'semester',
	'hide_empty' => false,
) );

$semesterScreenings = [];
foreach ( $semesters as $semester ) {
    $semesterStart = (string) get_term_meta( $semester->term_id, 'semester_start', true );
    $parsed_semester_start = date_parse_from_format("d.m.Y", $semesterStart);
    $timestamp = mktime(0, 0, 0, $parsed_semester_start["month"], $parsed_semester_start["day"], $parsed_semester_start["year"] );
    $semesterScreenings[$timestamp] = $semester;
}

krsort( $semesterScreenings );
?>
<main class="page-content mt-4">
    <article class="content">
        <header>
            <h1><?= get_theme_mod( 'archive_header' )[ get_locale() ] ?? "" ?></h1>
        </header>
	    <?= apply_filters( "the_content", get_theme_mod( 'archive_text' )[ get_locale() ] ?? "" ) ?>
    </article>
    <hr class="separator"/>
    <?php
    foreach( $semesterScreenings as $timestamp => $semester ):
	    $data = new WP_Query( array(
		    'post_type'      => [ 'movie', 'event' ],
		    'posts_per_page' => - 1,
		    'meta_query'     => [
			    [
				    'key'     => 'screening_date',
				    'value'   => time(),
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

        $screenings = [];
        while ( $data->have_posts() ) : $data->the_post();
            $screeningDate = (int) rwmb_get_value("screening_date");
            $title = ggl_get_title();

            $screenings[$screeningDate] = $title;
        endwhile;

	    $archive_data = get_term_meta( $semester->term_id, 'semester_shown_movies', true );
        if (!$archive_data) {
            $archive_data = [];
        }
	    $merge_archive_data = (bool) get_term_meta( $semester->term_id, 'semester_add_archival_data', true );
        if (!$merge_archive_data && $archive_data != null) {
            $screenings = [];
        }
	    foreach ($archive_data as $entry) {
		    $date                  = date_parse_from_format( "d.m.Y", $entry[0] );
		    $timestamp             = mktime( $date['hour'] ?: '0', null, null, $date['month'], $date['day'], $date['year'] );
            $offset = 0;
            while (($screenings[$timestamp + $offset] ?? null) !== null) {
                $offset++;
            }
		    $screenings[$timestamp] = $entry[1];
	    }

        krsort( $screenings );
        if (empty($screenings)) {
            continue;
        }
        ?>
        <article>
            <div class="movie-list mb-6">
                <div class="movie-list-title">
			        <?= $semester->name ?>
                </div>
                <div class="movie-list-entries">
			        <?php foreach ( $screenings as $screeningDate => $title ) : ?>
                        <div class="entry">
                            <div>
                                <p><?= date("d.m.Y", $screeningDate) ?></p>
                                <p class="font-ggl is-size-5 is-uppercase"><?= $title ?></p>
                            </div>
                        </div>
			        <?php endforeach; ?>
                </div>
            </div>
        </article>
    <?php
    endforeach;
    ?>
</main>
<?php get_footer();