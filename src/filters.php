<?php
add_filter("ggl__show_full_details", "ggl_theme__default_show_full_details", priority: 1, accepted_args: 2);
function ggl_theme__default_show_full_details(bool $anonymize, WP_Post $post) : bool {
	return rwmb_get_value( "license_type", post_id: $post->ID ) == "full" || is_user_logged_in();
}