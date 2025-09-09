<?php
defined("ABSPATH") || exit();
global $wp;
require_once "header.php"
?>
<main class="page-content content mt-4">
	<h1 class="has-text-centered"><?= __("Gone", "gegenlicht")?></h1>
	<?php
	get_template_part( 'partials/responsive-image', args: [
		'image_url'        => wp_get_attachment_image_url( get_theme_mod( 'semester_break_image' ), 'desktop' ),
		'mobile_image_url' => wp_get_attachment_image_url( get_theme_mod( 'semester_break_image' ), 'mobile' ),
		'fetch-priority'   => "high",
		"loading"          => "eager"
	] );
	?>
	<p class="mt-4"><?= esc_html__("The page you are trying to open is not available anymore", "gegenlicht") ?></p>
    <p><?php get_template_part( 'partials/button', args: [
                'href'    => "javascript:history.back()",
                'content' => __( 'One Step Back', 'gegenlicht' ),
                "external" => false,
        ] ) ?></p>
    <p><?php get_template_part( 'partials/button', args: [
                'href'    => get_home_url(scheme: "https"),
                'content' => __( 'Back to the Frontpage', 'gegenlicht' )
        ] ) ?></p>
</main>
<?php
get_footer();
?>