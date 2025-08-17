<?php
require_once "base.php";

class AddressOptionsCustomizer extends GGLCustomizerBase {
	private const SECTION = "addresses";

	function __construct( WP_Customize_Manager $manager, int $priority = 20, array $additionalArgs = [] ) {
		parent::__construct( $manager, __( 'Addresses', 'gegenlicht' ), __( 'Configure the addresses listed throughout the website', 'gegenlicht' ), $priority, self::SECTION, additionalArgs: $additionalArgs );

		$this->register_theme_mods();
		$this->register_controls();
	}

	private function register_theme_mods() {
		$this->add_theme_mod( "address[visitor]" );
		$this->add_theme_mod( "address[postal]");
	}

	private function register_controls(): void {
		$this->add_control( "address[visitor]", array(
			"label" => __( 'Visitor Address', 'gegenlicht' ),
			"type" => "textarea"
		) );
		$this->add_control( "address[postal]", array(
			"label" => __( 'Postal Address', 'gegenlicht' ),
			"type" => "textarea"
		) );
	}

}