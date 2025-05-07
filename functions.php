<?php
defined('ABSPATH') || exit;

require_once 'classes/Displayable.php';


function enable_theme_supports()
{
	add_theme_support('post-thumbnails');
	add_theme_support('title-tag');
	add_theme_support('customize-selective-refresh-widgets');
	add_theme_support('automatic-feed-links');
}

add_action('after_setup_theme', 'enable_theme_supports');


wp_dequeue_style('global-styles-inline-css');
wp_dequeue_style('wp-block-library-css');

function post_image_preload_header(Displayable $displayable): void
{
	if ($displayable->imageUrl === '') {
		return;
	}
	echo '<link rel="preload" href="' . $displayable->imageUrl . '" as="image">';
}

function crunchify_print_scripts_styles()
{

	$result = [];
	$result['scripts'] = [];
	$result['styles'] = [];

	// Print all loaded Scripts
	global $wp_scripts;
	foreach ($wp_scripts->queue as $script):
		$result['scripts'][] = $wp_scripts->registered[$script]->src . ";";
	endforeach;

	// Print all loaded Styles (CSS)
	global $wp_styles;
	foreach ($wp_styles->queue as $style):
		$result['styles'][] = $wp_styles->registered[$style]->src . ";";
	endforeach;

	return $result;
}

/**
 * returns the upcoming movie and up to two directly following movies starting in the next
 * 8 hours
 *
 * @return Displayable[]
 */
function get_upcoming(): array
{
	$args = array(
		'post_type' => 'movie',
		'post_status' => 'publish',
		'posts_per_page' => 1,
		'meta_query' => array(
			'key' => 'movie_screening_date',
			'value' => date('U', strtotime(date("Y-m-d"))),
			'compare' => '>=',
			'type'
		),
		'orderby' => 'meta_value_num',
		'meta_key' => 'movie_screening_date',
		'order' => 'ASC'
	);

	$query = new WP_Query($args);
	$next_movie = $query->have_posts() ? $query->post : null;

	$args = array(
		'post_type' => 'event',
		'post_status' => 'publish',
		'posts_per_page' => 1,
		'meta_query' => array(
			'key' => 'event_screening_date',
			'value' => (int) date('U', strtotime(date("Y-m-d"))),
			'compare' => '>=',
		),
		'orderby' => 'meta_value_num',
		'meta_key' => 'event_screening_date',
		'order' => 'ASC'
	);
	$query = new WP_Query($args);
	$next_event = $query->have_posts() ? $query->post : null;

	//wp_die(var_dump($next_movie) . PHP_EOL . var_dump($next_event));


	if ($next_event === null && $next_movie === null) {
		return array();
	}

	$skip_event = $next_event === null;
	$skip_movie = $next_movie === null;

	$event_start = $skip_event ? null : (int) get_post_meta($next_event->ID, 'event_screening_date', true);
	$movie_start = $skip_movie ? null : (int) get_post_meta($next_movie->ID, 'movie_screening_date', true);

	$upcoming = array();
	$next_start = 0;
	$following = array();

	if ($event_start !== null && $movie_start !== null) {
		if ($event_start < $movie_start) {
			$upcoming[] = $next_event;
			$next_start = $event_start;
		} else {
			$upcoming[] = $next_movie;
			$next_start = $movie_start;
		}
	} elseif ($event_start === null && $movie_start !== null) {
		$upcoming[] = $next_movie;
		$next_start = $movie_start;
	} elseif ($event_start !== null && $movie_start === null) {
		$upcoming[] = $next_event;
		$next_start = $event_start;
	}




	if (!$skip_movie) {
		$args = array(
			'post_type' => 'movie',
			'post_status' => 'publish',
			'posts_per_page' => 2,
			'meta_query' => array(
				'key' => 'movie_screening_date',
				'value' => [$next_start + 1, strtotime('+8 hours', $movie_start)],
				'compare' => 'BETWEEN',
			),
			'orderby' => 'meta_value_num',
			'meta_key' => 'movie_screening_date',
			'order' => 'ASC'
		);
		$query = new WP_Query($args);
		$next_movies = $query->have_posts() ? $query->posts : null;

		if ($next_movies !== null) {
			var_dump($next_movies);
			foreach ($next_movies as $next_movie) {
				$start_time = (int) get_post_meta($next_movie->ID, 'movie_screening_date', true);
				$following[$start_time] = $next_movie;
			}
		}
	}

	if (!$skip_event) {

		$args = array(
			'post_type' => 'event',
			'post_status' => 'publish',
			'posts_per_page' => 2,
			'meta_query' => array(
				'key' => 'event_screening_date',
				'value' => [$next_start + 1, strtotime('+8 hours', $event_start)],
				'compare' => 'BETWEEN',
			),
			'orderby' => 'meta_value_num',
			'meta_key' => 'event_screening_date',
			'order' => 'ASC'
		);
		$query = new WP_Query($args);
		$next_events = $query->have_posts() ? $query->posts : null;
		if ($next_events !== null) {
			foreach ($next_events as $next_event) {
				$start_time = (int) get_post_meta($next_event->ID, 'event_screening_date', true);
				$following[$start_time] = $next_event;
			}
		}
	}


	ksort($following);
	for ($i = 0; $i < count($following); $i++) {
		if ($i == 2) {
			break;
		}
		$upcoming[] = $following[array_keys($following)[$i]];
	}

	$displayables = array();
	foreach ($upcoming as $item) {
		if ($item === null) {
			continue;
		}
		$displayables[] = new Displayable($item);
	}

	return $displayables;

}


/**
 * Disable the emoji's
 */
function disable_emojis()
{
	remove_action('wp_head', 'print_emoji_detection_script', 7);
	remove_action('admin_print_scripts', 'print_emoji_detection_script');
	remove_action('wp_print_styles', 'print_emoji_styles');
	remove_action('admin_print_styles', 'print_emoji_styles');
	remove_filter('the_content_feed', 'wp_staticize_emoji');
	remove_filter('comment_text_rss', 'wp_staticize_emoji');
	remove_filter('wp_mail', 'wp_staticize_emoji_for_email');
	add_filter('tiny_mce_plugins', 'disable_emojis_tinymce');
	add_filter('wp_resource_hints', 'disable_emojis_remove_dns_prefetch', 10, 2);
}
add_action('init', 'disable_emojis');

/**
 * Filter function used to remove the tinymce emoji plugin.
 * 
 * @param array $plugins 
 * @return array Difference betwen the two arrays
 */
function disable_emojis_tinymce($plugins)
{
	if (is_array($plugins)) {
		return array_diff($plugins, array('wpemoji'));
	} else {
		return array();
	}
}

/**
 * Remove emoji CDN hostname from DNS prefetching hints.
 *
 * @param array $urls URLs to print for resource hints.
 * @param string $relation_type The relation type the URLs are printed for.
 * @return array Difference betwen the two arrays.
 */
function disable_emojis_remove_dns_prefetch($urls, $relation_type)
{
	if ('dns-prefetch' == $relation_type) {
		/** This filter is documented in wp-includes/formatting.php */
		$emoji_svg_url = apply_filters('emoji_svg_url', 'https://s.w.org/images/core/emoji/2/svg/');

		$urls = array_diff($urls, array($emoji_svg_url));
	}

	return $urls;
}

function remove_wp_block_library_css()
{
	wp_dequeue_style('wp-block-library');
	wp_dequeue_style('wp-block-library-theme');
	// WooCommerce-Block-CSS entfernen
	wp_dequeue_style('wc-blocks-style'); // Falls Sie WooCommerce-Block-CSS blocks behalten möchten, entfernen Sie diese Zeile
}

add_action('wp_enqueue_scripts', 'remove_wp_block_library_css', 100);

remove_action('wp_enqueue_scripts', 'wp_enqueue_global_styles');
remove_action('wp_footer', 'wp_enqueue_global_styles', 1);
remove_action('wp_body_open', 'wp_global_styles_render_svg_filters');
add_action('wp_enqueue_scripts', function () {
	wp_dequeue_style('wp-block-library');
	wp_dequeue_style('wp-block-library-theme');
	wp_dequeue_style('classic-theme-styles');
});