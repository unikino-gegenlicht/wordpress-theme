<?php
add_filter("ggl__show_full_details", "ggl_theme__default_show_full_details", priority: 1);
function ggl_theme__default_show_full_details() : bool {
	return (rwmb_get_value( "license_type" ) == "full") || is_user_logged_in();
}