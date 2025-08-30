<?php
require_once "base.php";

class LocationBlockCustomizer extends GGLCustomizerBase {
	const SECTION = "block_location";

	function __construct( WP_Customize_Manager $manager, int $priority = 20, array $additionalArgs = [] ) {
		parent::__construct( $manager, __( 'Location', 'gegenlicht' ), __( 'Configure the content of the block showcasing our location', 'gegenlicht' ), $priority, self::SECTION, additionalArgs: array_merge( $additionalArgs, [
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

		$this->add_theme_mod( "location_block_title[de]" );
		$this->add_theme_mod( "location_block_title[en]" );

		$this->add_theme_mod( "location_block_text[de]" );
		$this->add_theme_mod( "location_block_text[en]" );

		$this->add_theme_mod( "location_block_text_color[light]" );
		$this->add_theme_mod( "location_block_text_color[dark]" );

		$this->add_theme_mod( "location_block_bg_color[light]" );
		$this->add_theme_mod( "location_block_bg_color[dark]" );

		$this->add_theme_mod('location_detail_page');
	}

	private function register_controls(): void {
		$this->manager->add_control( new WP_Customize_Media_Control( $this->manager, "location_block_image", array(
			"section" => self::SECTION,
			"panel" => $this->panel,
			"label"   => __( 'Block Header Image', 'gegenlicht' ),
			"description" => __( 'Upload an image for the block header which will be displayed above the block title', 'gegenlicht' ),
		)));

		$this->add_control( "location_block_title[de]", array(
			"section" => self::SECTION,
			"label"   => __( 'Title (German)', 'gegenlicht' ),
			"type"    => "text",

		) );

		$this->add_control( "location_block_title[en]", array(
			"section" => self::SECTION,
			"label"   => __( 'Title (English)', 'gegenlicht' ),
			"type"    => "text",

		) );

		$this->add_control( "location_block_text[de]", array(
			"section" => self::SECTION,
			"label"   => __( 'Text (German)', 'gegenlicht' ),
			"type"    => "textarea",
		) );

		$this->add_control( "location_block_text[en]", array(
			"section" => self::SECTION,
			"label"   => __( 'Text (English)', 'gegenlicht' ),
			"type"    => "textarea",
		) );

		$this->manager->add_control(new WP_Customize_Color_Control($this->manager, "location_block_text_color[light]", array(
			"section"     => self::SECTION,
			"label"       => __( 'Text Color (Light Mode)', 'gegenlicht' ),
			"description" => __( 'The color of the text and partner logos (if possible)', 'gegenlicht' ),
			"type"        => "color",
			"panel" => $this->panel,
		)));

		$this->manager->add_control(new WP_Customize_Color_Control($this->manager, "location_block_bg_color[light]", array(
			"section"     => self::SECTION,
			"label"       => __( 'Background Color (Light Mode)', 'gegenlicht' ),
			"description" => __( 'The background color of the whole block', 'gegenlicht' ),
			"type"        => "color",
			"panel" => $this->panel,
		)));

		$this->manager->add_control(new WP_Customize_Color_Control($this->manager, "location_block_text_color[dark]", array(
			"section"     => self::SECTION,
			"label"       => __( 'Text Color (Dark Mode)', 'gegenlicht' ),
			"description" => __( 'The color of the text and partner logos (if possible) during dark mode', 'gegenlicht' ),
			"type"        => "color",
			"panel" => $this->panel,
		)));

		$this->manager->add_control(new WP_Customize_Color_Control($this->manager, "location_block_bg_color[dark]", array(
			"section"     => self::SECTION,
			"label"       => __( 'Background Color (Dark Mode)', 'gegenlicht' ),
			"description" => __( 'The background color of the whole block during dark mode', 'gegenlicht' ),
			"type"        => "color",
			"panel" => $this->panel,
		)));

		$this->add_control('location_detail_page', array(
			'type' => 'dropdown-pages',
			'section' => self::SECTION,
			'label' => __( 'Detail Page', 'gegenlicht' ),
			'description' => __('This page contains detailed information about the location', "gegenlicht"),
		));
	}

}