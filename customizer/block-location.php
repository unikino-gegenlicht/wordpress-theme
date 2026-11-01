<?php

namespace inc\customizer;

use WP_Customize_Color_Control;
use WP_Customize_Manager;
use WP_Customize_Media_Control;

require_once "base.php";

class LocationBlockCustomizer extends CPTheme_CustomizerBase {
	const SECTION = "block_location";

	function __construct( WP_Customize_Manager $manager, int $priority = 20, array $additionalArgs = [] ) {
		parent::__construct( $manager, __( 'Location', 'cinema-press-theme' ), __( 'Configure the content of the block showcasing our location', 'cinema-press-theme' ), $priority, self::SECTION, additionalArgs: array_merge( $additionalArgs, [
			"active_callback" => function () {
				return $this->is_block_enabled();
			}
		] ) );

		$this->register_theme_mods();
		$this->register_controls();
	}

	private function is_block_enabled(): bool {
		return in_array( "location", get_theme_mod( "displayed_blocks" ) ?? [] );
	}

	private function register_theme_mods(): void {
		$this->add_theme_mod( "location_block_image" );
		$this->add_theme_mod( "location_block_title" );
		$this->add_theme_mod( "location_block_text" );
		$this->add_theme_mod( 'location_detail_page' );
	}

	private function register_controls(): void {
		$this->manager->add_control( new WP_Customize_Media_Control( $this->manager, "location_block_image", array(
			"section"     => self::SECTION,
			"panel"       => $this->panel,
			"label"       => __( 'Block Header Image', 'cinema-press-theme' ),
			"description" => __( 'Upload an image for the block header which will be displayed above the block title', 'cinema-press-theme' ),
		) ) );

		$this->add_control( "location_block_title", array(
			"section" => self::SECTION,
			"label"   => __( 'Block Title', 'cinema-press-theme' ),
			"type"    => "text",

		) );

		$this->add_control( "location_block_text", array(
			"section" => self::SECTION,
			"label"   => __( 'Block Text', 'cinema-press-theme' ),
			"type"    => "textarea",
		) );

		$this->add_control( 'location_detail_page', array(
			'type'        => 'dropdown-pages',
			'section'     => self::SECTION,
			'label'       => __( 'Page with Details', 'cinema-press-theme' ),
			'description' => __( 'This page contains detailed information about the location', "cinema-press-theme" ),
		) );
	}

}