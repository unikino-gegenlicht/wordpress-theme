<?php

namespace inc\customizer;

use WP_Customize_Manager;

require_once "base.php";
require_once "controls/custom_controls.php";

class FooterCustomizer extends CPTheme_CustomizerBase {
	private const SECTION = "footer";

	function __construct( WP_Customize_Manager $manager, int $priority = 20, array $additionalArgs = [] ) {
		parent::__construct( $manager, __( 'Footer', 'gegenlicht' ), __( 'Configure the content displayed in the footer', 'gegenlicht' ), $priority, self::SECTION, additionalArgs: $additionalArgs );

		$this->register_theme_mods();
		$this->register_controls();
	}

	function register_theme_mods() {
		$this->add_theme_mod( "show_youth_protection_officer", true );
	}

	function register_controls() {

		$this->add_control( "show_youth_protection_officer", array(
			'label'       => __( 'Show Youth Protection Officer Data', 'gegenlicht' ),
			'description' => __( "It's recommended to keep this enabled to ensure compliance with German Youth Protection Laws", "gegenlicht" ),
			'type'        => 'checkbox',
		) );

	}

}
