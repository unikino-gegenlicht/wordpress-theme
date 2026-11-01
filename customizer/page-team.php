<?php

namespace inc\customizer;

use WP_Customize_Manager;

require_once "base.php";

class TeamPageCustomizer extends CPTheme_CustomizerBase {
	const SECTION = 'page_team';

	function __construct( WP_Customize_Manager $manager, int $priority = 20, array $additionalArgs = [] ) {
		parent::__construct( $manager, __( 'Team Page', 'gegenlicht' ), __( 'Configure the content of the page showing our current team and our former members', 'gegenlicht' ), $priority, self::SECTION, additionalArgs: $additionalArgs );

		$this->add_settings();
		$this->add_controls();
	}

	private function add_settings(): void {
		$this->add_theme_mod( "team_page_title", __( 'The Team', 'gegenlicht' ) );

		$this->add_theme_mod( "team_page_text" );

		$this->add_theme_mod( "team_page_active_members_heading", __( 'Active Members', 'gegenlicht' ) );

		$this->add_theme_mod( "team_page_former_members_heading", __( 'Former Members', 'gegenlicht' ) );
		$this->add_theme_mod( "team_page_former_members_text" );

		$this->add_theme_mod( "team_page_honorary_members_heading", __( 'Honorary Members', 'gegenlicht' ) );
		$this->add_theme_mod( "team_page_honorary_members_text" );

	}

	private function add_controls(): void {
		$this->add_control( "team_page_title", array(
			"section" => self::SECTION,
			"label"   => __( 'Heading', 'gegenlicht' ),
		) );

		$this->add_control( "team_page_text", array(
			"section" => self::SECTION,
			"label"   => __( 'Opener Text for the members page', 'gegenlicht' ),
			"type"    => "textarea"
		) );

		$this->add_control( "team_page_active_members_heading", array(
			"section" => self::SECTION,
			"label"   => __( 'Heading for active member section', 'gegenlicht' ),
		) );

		$this->add_control( "team_page_former_members_heading", array(
			"section" => self::SECTION,
			"label"   => __( 'Heading for former member section', 'gegenlicht' ),
		) );

		$this->add_control( "team_page_former_members_text", array(
			"section" => self::SECTION,
			"label"   => __( 'Text about former members', 'gegenlicht' ),
			"type"    => "textarea"
		) );

		$this->add_control( "team_page_honorary_members_heading", array(
			"section" => self::SECTION,
			"label"   => __( 'Heading for honorary member section', 'gegenlicht' ),
		) );

		$this->add_control( "team_page_honorary_members_text", array(
			"section" => self::SECTION,
			"label"   => __( 'Text about former members', 'gegenlicht' ),
			"type"    => "textarea"
		) );
	}
}