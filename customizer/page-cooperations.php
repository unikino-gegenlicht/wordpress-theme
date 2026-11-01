<?php

namespace inc\customizer;

use inc\customizer\controls\MultiInputControl;
use WP_Customize_Manager;

require_once "base.php";

class CooperationPageCustomizer extends CPTheme_CustomizerBase {
	const SECTION = 'page_cooperations';

	function __construct( WP_Customize_Manager $manager, int $priority = 20, array $additionalArgs = [] ) {
		parent::__construct( $manager, __( 'Cooperation Page', 'gegenlicht' ), __( 'Configure the content of the page showing our cooperation partners and how to to a cooperation with us', 'gegenlicht' ), $priority, self::SECTION, additionalArgs: $additionalArgs );

		$this->add_settings();
		$this->add_controls();
	}

	private function add_settings(): void {
		$this->add_theme_mod( "cooperation_page_title" );

		$this->add_theme_mod( "cooperation_page_text" );

		$this->manager->add_setting( "cooperation_rules", array(
			'type'      => 'theme_mod',
			'default'   => '',
			'transport' => 'postMessage',
		) );
		$this->add_theme_mod( "cooperation_rules_closer" );

		$this->add_theme_mod( "cooperation_partner_text" );

		$this->add_theme_mod( "cooperation_form_button_text" );
		$this->add_theme_mod( "cooperation_form_url" );
	}

	private function add_controls(): void {
		$this->add_control( "cooperation_page_title", array(
			"section" => self::SECTION,
			"label"   => __( 'Heading', 'gegenlicht' ),
		) );

		$this->add_control( "cooperation_page_text", array(
			"section" => self::SECTION,
			"label"   => __( 'Introduction', 'gegenlicht' ),
			"type"    => "textarea"
		) );


		$this->manager->add_control( new MultiInputControl( $this->manager, "cooperation_rules", array(
			"section"     => self::SECTION,
			"panel"       => $this->panel,
			"label"       => __( 'Cooperation Rules', 'gegenlicht' ),
			"description" => __( "The rules are displayed in the same order as here", "gegenlicht" ),
			"settings"    => "cooperation_rules",
		) ) );


		$this->add_control( "cooperation_rules_closer", array(
			"section"     => self::SECTION,
			"label"       => __( 'Rule Closer Text', 'gegenlicht' ),
			"description" => __( 'This text is displayed below the cooperation rules', 'gegenlicht' ),
			"type"        => "textarea"
		) );


		$this->add_control( "cooperation_partner_text", array(
			"section"     => self::SECTION,
			"label"       => __( 'Partner List Text', 'gegenlicht' ),
			"description" => __( 'This text is displayed just above the entries showcasing our cooperation partners', 'gegenlicht' ),
			"type"        => "textarea"
		) );

		$this->add_control( "cooperation_form_button_text", array(
			"section"     => self::SECTION,
			"label"       => __( 'Button Label', 'gegenlicht' ),
			"description" => __( "This text is displayed on the button pointing to the cooperation request form" ),
			"type"        => "text"
		) );

		$this->add_control( "cooperation_form_url", array(
			"section" => self::SECTION,
			"label"   => __( 'URL to Cooperation Request Form', 'gegenlicht' ),
			"type"    => "url"
		) );

	}
}