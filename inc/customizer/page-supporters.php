<?php

require_once "base.php";

class SupporterPageCustomizer extends GGLCustomizerBase {
	const SECTION = 'page_supporters';

	function __construct( WP_Customize_Manager $manager, int $priority = 20, array $additionalArgs = [] ) {
		parent::__construct( $manager, __( 'Supporter Page', 'gegenlicht' ), __( 'Configure the content of the page showing our loved supporters', 'gegenlicht' ), $priority, self::SECTION, additionalArgs: $additionalArgs );

		$this->add_settings();
		$this->add_controls();
	}

	private function add_settings(): void {
		$this->add_theme_mod( "supporter_page_title[de]" );
		$this->add_theme_mod( "supporter_page_title[en]" );

		$this->add_theme_mod( "supporter_page_text[de]" );
		$this->add_theme_mod( "supporter_page_text[en]" );

	}

	private function add_controls(): void {
		$this->add_control( "supporter_page_title[de]", array(
			"section" => self::SECTION,
			"label"   => __( 'Heading (German)', 'gegenlicht' ),
		) );

		$this->add_control( "supporter_page_title[en]", array(
			"section" => self::SECTION,
			"label"   => __( 'Heading (English)', 'gegenlicht' ),
		) );

		$this->manager->add_control( new WP_Customize_Code_Editor_Control($this->manager, "supporter_page_text[de]", array(
			"section" => self::SECTION,
			"panel" => $this->panel,
			"label"   => __( 'Text (German)', 'gegenlicht' ),
			"description" => __("This text is displayed above the supporter entries", "gegenlicht" ),
			"code_type"    => "text/html"
		) ));

		$this->manager->add_control( new WP_Customize_Code_Editor_Control($this->manager, "supporter_page_text[en]", array(
			"section" => self::SECTION,
			"panel" => $this->panel,
			"label"   => __( 'Text (English)', 'gegenlicht' ),
			"description" => __("This text is displayed above the supporter entries", "gegenlicht" ),
			"code_type"    => "text/html"
		) ));



	}
}