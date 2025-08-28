<?php

defined( 'ABSPATH' ) || exit;

$taxonomy = get_queried_object();


get_header( args: [ "title" => $taxonomy->name ] );
do_action( 'wp_body_open' );

?>
<main class="page-content">
    <article>
        <header class="content">
            <h1 class="is-size-2"><?= $taxonomy->name ?></h1>
        </header>
		<?= apply_filters( "the_content", $taxonomy->description ) ?>
    </article>

	<?php

	$query = new WP_Query( array(
		'post_type'      => [ 'movie', "event" ],
		'posts_per_page' => - 1,
		'tax_query'      => array(
			array(
				'taxonomy' => 'semester',
				'terms'    => $taxonomy->term_id,
			)
		),
		"meta_key"       => "screening_date",
		"orderby"        => "meta_value_num",
		"order"          => "ASC"
	) );

	while ( $query->have_posts() ): $query->the_post();
		$screeningMonth                     = date( 'F', (int) rwmb_get_value( 'screening_date' ) );
		$monthlyMovies[ $screeningMonth ][] = $post;
	endwhile;

	foreach ( $monthlyMovies as $month => $posts ) :
		get_template_part( "partials/movie-list", args: [
			"title" => esc_html__( $month, 'gegenlicht' ),
			"posts" => $posts
		] );
    endforeach; ?>


</main>