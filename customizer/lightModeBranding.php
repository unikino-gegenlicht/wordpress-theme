<?php

namespace inc\customizer;

use WP_Customize_Color_Control;
use WP_Customize_Manager;
use WP_Customize_Media_Control;

require_once "base.php";

class LightModeBrandingCustomizer extends CPTheme_CustomizerBase {

	const SECTION = "light_mode";

	function __construct( WP_Customize_Manager $manager, int $priority = 20, array $additionalArgs = [] ) {
		parent::__construct( $manager, __( 'Light Mode', 'cinema-press-theme' ), __( 'Configure the light mode colors in your branding', 'gegenlicht' ), priority: $priority, section: self::SECTION, additionalArgs: $additionalArgs );
		$this->register_theme_mods();
		$this->register_controls();
	}

	private function register_theme_mods(): void {
		$this->add_theme_mod( "primary_color_light", "#ff00ff" );
		$this->add_theme_mod( "text_color_light", "#2e2e2e" );
	}

	private function register_controls(): void {
		$this->manager->add_control( new WP_Customize_Color_Control( $this->manager, "primary_color_light", array(
			'section'     => self::SECTION,
			'label'       => __( 'Primary Color', 'gegenlicht' ),
			'description' => __( 'Select a color for primary color.', 'gegenlicht' ),
		) ) );
		$this->manager->add_control( new WP_Customize_Color_Control( $this->manager, "text_color_light", array(
			'section'     => self::SECTION,
			'label'       => __( 'Text Color', 'gegenlicht' ),
			'description' => __( 'Select a color for text color.', 'gegenlicht' ),
		) ) );
	}
}