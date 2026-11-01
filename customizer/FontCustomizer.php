<?php

namespace inc\customizer;

use WP_Customize_Color_Control;
use WP_Customize_Manager;
use WP_Query;

require_once "base.php";

class FontCustomizer extends CPTheme_CustomizerBase {

	const SECTION = "fonts";

	function __construct( WP_Customize_Manager $manager, int $priority = 20, array $additionalArgs = [] ) {
		parent::__construct( $manager, __( 'Fonts', 'cinema-press-theme' ), __( 'Configure the fonts used on your website. To change the available Fonts, open the font library in the admin dashboard', 'cinema-press-theme' ), priority: $priority, section: self::SECTION, additionalArgs: $additionalArgs );
		$this->register_theme_mods();
		$this->register_controls();
	}

	private function register_theme_mods(): void {
		$this->add_theme_mod( "heading_font_family", 'Readex Pro' );
		$this->add_theme_mod( "text_font_family", "InterVariable" );
	}

	private function register_controls(): void {
		$available_fonts = new WP_Query(array(
			'post_type' => 'wp_font_family',
			'posts_per_page' => -1,
		));

		$installed_fonts = [];

		foreach ( $available_fonts->posts as $font ) {
			$slug = explode(",", json_decode($font->post_content, true)['fontFamily'])[0];
			$installed_fonts[ $slug ] = $font->post_title;
		}


		$this->add_control( 'heading_font_family', [
			"label" => __( 'Headings', 'cinema-press-theme' ),
			"type" => "select",
			"choices" => array_merge(["Readex Pro" => "Readex Pro", "InterVariable" => "Inter Variable"], $installed_fonts),
		] );
		$this->add_control( 'text_font_family', [
			"label" => __( 'Text', 'cinema-press-theme' ),
			"type" => "select",
			"choices" => array_merge(["InterVariable" => "Inter Variable", "Readex Pro" => "Readex Pro"], $installed_fonts)
		] );
	}
}