<?php
require_once "base.php";

class YouthProtectionCustomizer extends GGLCustomizerBase {
	private const SECTION = "youth-protection";

	function __construct( WP_Customize_Manager $manager, int $priority = 20, array $additionalArgs = [] ) {
		parent::__construct( $manager, __( 'Youth Protection', 'gegenlicht' ), __( 'Configure the details regarding the youth protection mechanisms used', 'gegenlicht' ), $priority, self::SECTION, additionalArgs: $additionalArgs );

		$this->add_settings();
		$this->add_controls();
	}

	private function add_settings() {
		$this->add_theme_mod("youth_protection_officer[name]");
		$this->add_theme_mod("youth_protection_officer[email]");
	}

	private function add_controls() {
		$this->add_control("youth_protection_officer[name]", [
			"label"       => __( "Name of Youth Protection Officer", "gegenlicht" ),
		]);
		$this->add_control("youth_protection_officer[email]", [
			"label"       => __( "E-Mail Address of Youth Protection Officer", "gegenlicht" ),
		]);
	}
}