<?php

namespace inc\customizer;

use WP_Customize_Color_Control;
use WP_Customize_Manager;

require_once "base.php";

class DarkModeBrandingCustomizer extends CPTheme_CustomizerBase {

	const SECTION = "dark_mode";

	function __construct( WP_Customize_Manager $manager, int $priority = 20, array $additionalArgs = [] ) {
		parent::__construct( $manager, __( 'Dark Mode', 'cinema-press-theme' ), __( 'Configure the light mode colors in your branding', 'gegenlicht' ), priority: $priority, section: self::SECTION, additionalArgs: $additionalArgs );
		$this->register_theme_mods();
		$this->register_controls();
	}

	private function register_theme_mods(): void {
		$this->add_theme_mod( "primary_color_dark", "#2e2e2e" );
		$this->add_theme_mod( "text_color_dark", "#ff00ff" );
	}

	private function register_controls(): void {
		$this->manager->add_control( new WP_Customize_Color_Control( $this->manager, "primary_color_dark", array(
			'section'     => self::SECTION,
			'label'       => __( 'Primary Color', 'gegenlicht' ),
			'description' => __( 'Select a color for primary color.', 'gegenlicht' ),
		) ) );
		$this->manager->add_control( new WP_Customize_Color_Control( $this->manager, "text_color_dark", array(
			'section'     => self::SECTION,
			'label'       => __( 'Text Color', 'gegenlicht' ),
			'description' => __( 'Select a color for text color.', 'gegenlicht' ),
		) ) );
	}
}