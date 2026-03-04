<?php
add_action("after_setup_theme", function () {
	add_filter("ggl__show_full_details", "ggl_theme__default_show_full_details", accepted_args: 2);
});
function ggl_theme__default_show_full_details(bool $display_details, WP_Post $post) : bool {
	$display_details = (rwmb_get_value( "license_type", post_id: $post->ID ) == "full" || is_user_logged_in());
	return $display_details;
}
