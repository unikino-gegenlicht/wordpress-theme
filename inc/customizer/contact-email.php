<?php
require_once "base.php";

class EmailOptionsCustomizer extends GGLCustomizerBase {
	private const SECTION = "email";

	function __construct( WP_Customize_Manager $manager, int $priority = 20, array $additionalArgs = [] ) {
		parent::__construct( $manager, __( 'E-Mail Addresses', 'gegenlicht' ), __( 'Configure the available email options listed throughout the website', 'gegenlicht' ), $priority, self::SECTION, additionalArgs: $additionalArgs );

		$this->register_theme_mods();
		$this->register_controls();
	}

	private function register_theme_mods() {
		$this->add_theme_mod( "email_address[general]", "info@gegenlicht.net" );
		$this->add_theme_mod( "email_address[join]", "mitmachen@gegenlicht.net" );
		$this->add_theme_mod( "email_address[website]", "website@gegenlicht.net" );
	}

	private function register_controls(): void {
		$this->add_control( "email_address[general]", array(
			"label" => __( 'General', 'gegenlicht' ),
		) );
		$this->add_control( "email_address[join]", array(
			"label" => __( 'Join', 'gegenlicht' ),
		) );
		$this->add_control( "email_address[website]", array(
			"label" => __( 'Technical Contact', 'gegenlicht' ),
		) );
	}

}