<?php
require_once "base.php";

class NavigationBarCustomizer extends GGLCustomizerBase {

	const SECTION = "navigation";

	function __construct( WP_Customize_Manager $manager, int $priority = 20, array $additionalArgs = [] ) {
		parent::__construct( $manager, __( 'Navigation', 'gegenlicht' ), __( 'Configure the display of the navigation header', 'gegenlicht' ), priority: $priority, section: self::SECTION, additionalArgs: $additionalArgs );

		$this->register_theme_mods();
		$this->register_controls();
	}

	private function register_theme_mods(): void {
		$this->add_theme_mod("header_logo");
		$this->add_theme_mod("small_header_logo");
	}

	private function register_controls(): void {
		$this->manager->add_control(new WP_Customize_Media_Control($this->manager, "header_logo", array(
			'section' => self::SECTION,
			'label' => __( 'Navigation Logo', 'gegenlicht' ),
			'description' => __( 'Select a SVG Image for the navigation brand. Please select a logo which has no color set to it, to allow coloring it in in dark themes and special program pages', 'gegenlicht' ),
			'mime_type' => 'image/svg+xml',
		)));
		$this->manager->add_control(new WP_Customize_Media_Control($this->manager, "small_header_logo", array(
			'section' => self::SECTION,
			'label' => __( 'Navigation Logo (Small)', 'gegenlicht' ),
			'description' => __( 'Select a SVG Image for the navigation brand. Please select a logo which has no color set to it, to allow coloring it in in dark themes and special program pages', 'gegenlicht' ),
			'mime_type' => 'image/svg+xml',
		)));
	}
}