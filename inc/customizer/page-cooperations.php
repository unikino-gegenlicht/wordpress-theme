<?php

require_once "base.php";

class CooperationPageCustomizer extends GGLCustomizerBase {
	const SECTION = 'page_cooperations';

	function __construct( WP_Customize_Manager $manager, int $priority = 20, array $additionalArgs = [] ) {
		parent::__construct( $manager, __( 'Cooperation Page', 'gegenlicht' ), __( 'Configure the content of the page showing our cooperation partners and how to to a cooperation with us', 'gegenlicht' ), $priority, self::SECTION, additionalArgs: $additionalArgs );

		$this->add_settings();
		$this->add_controls();
	}

	private function add_settings(): void {
		$this->add_theme_mod( "cooperation_page_title[de]" );
		$this->add_theme_mod( "cooperation_page_title[en]" );

		$this->add_theme_mod( "cooperation_page_text[de]" );
		$this->add_theme_mod( "cooperation_page_text[en]" );

		$this->manager->add_setting( "cooperation_rules[de]", array(
			'type'      => 'theme_mod',
			'default'   => '',
			'transport' => 'postMessage',
		) );
		$this->manager->add_setting( "cooperation_rules[en]", array(
			'type'      => 'theme_mod',
			'default'   => '',
			'transport' => 'postMessage',
		) );
		$this->add_theme_mod( "cooperation_rules_closer[de]" );
		$this->add_theme_mod( "cooperation_rules_closer[en]" );

		$this->add_theme_mod( "cooperation_partner_text[de]" );
		$this->add_theme_mod( "cooperation_partner_text[en]" );

		$this->add_theme_mod( "cooperation_form_button_text[de]" );
		$this->add_theme_mod( "cooperation_form_button_text[en]" );
		$this->add_theme_mod( "cooperation_form_url" );
	}

	private function add_controls(): void {
		$this->add_control( "cooperation_page_title[de]", array(
			"section" => self::SECTION,
			"label"   => __( 'Heading (German)', 'gegenlicht' ),
		) );

		$this->add_control( "cooperation_page_title[en]", array(
			"section" => self::SECTION,
			"label"   => __( 'Heading (English)', 'gegenlicht' ),
		) );

		$this->add_control( "cooperation_page_text[de]", array(
			"section" => self::SECTION,
			"label"   => __( 'Introduction (German)', 'gegenlicht' ),
			"type"    => "textarea"
		) );

		$this->add_control( "cooperation_page_text[en]", array(
			"section" => self::SECTION,
			"label"   => __( 'Introduction (English)', 'gegenlicht' ),
			"type"    => "textarea"
		) );

		$this->manager->add_control( new MultiInputControl( $this->manager, "cooperation_rules[de]", array(
			"section" => self::SECTION,
			"panel" => $this->panel,
			"label" => __( 'Cooperation Rules (German)', 'gegenlicht' ),
			"description" => __("The rules are displayed in the same order as here", "gegenlicht" ),
			"settings" => "cooperation_rules[de]",
		) ) );

		$this->manager->add_control( new MultiInputControl( $this->manager, "cooperation_rules[en]", array(
			"section" => self::SECTION,
			"panel" => $this->panel,
			"label" => __( 'Cooperation Rules (English)', 'gegenlicht' ),
			"description" => __("The rules are displayed in the same order as here", "gegenlicht" ),
			"settings" => "cooperation_rules[en]",
		) ) );

		$this->add_control( "cooperation_partner_text[de]", array(
			"section" => self::SECTION,
			"label"   => __( 'Partner List Text (German)', 'gegenlicht' ),
			"description" => __('This text is displayed just above the entries showcasing our cooperation partners', 'gegenlicht' ),
			"type"    => "textarea"
		) );

		$this->add_control( "cooperation_partner_text[en]", array(
			"section" => self::SECTION,
			"label"   => __( 'Partner List Text (English)', 'gegenlicht' ),
			"description" => __('This text is displayed just above the entries showcasing our cooperation partners', 'gegenlicht' ),
			"type"    => "textarea"
		) );

		$this->add_control( "cooperation_form_button_text[de]", array(
			"section" => self::SECTION,
			"label"   => __( 'Button Label (German)', 'gegenlicht' ),
			"type"    => "text"
		) );

		$this->add_control( "cooperation_form_button_text[en]", array(
			"section" => self::SECTION,
			"label"   => __( 'Button Label (English)', 'gegenlicht' ),
			"type"    => "text"
		) );

		$this->add_control( "cooperation_form_url", array(
			"section" => self::SECTION,
			"label"   => __( 'URL to Cooperation Request Form', 'gegenlicht' ),
			"type"    => "url"
		) );

	}
}