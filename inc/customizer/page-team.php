<?php

require_once "base.php";

class TeamPageCustomizer extends GGLCustomizerBase {
	const SECTION = 'page_team';

	function __construct( WP_Customize_Manager $manager, int $priority = 20, array $additionalArgs = [] ) {
		parent::__construct( $manager, __( 'Team Page', 'gegenlicht' ), __( 'Configure the content of the page showing our current team and our former members', 'gegenlicht' ), $priority, self::SECTION, additionalArgs: $additionalArgs );

		$this->add_settings();
		$this->add_controls();
	}

	private function add_settings(): void {
		$this->add_theme_mod( "team_page_title[de]" );
		$this->add_theme_mod( "team_page_title[en]" );

		$this->add_theme_mod( "team_page_text[de]" );
		$this->add_theme_mod( "team_page_text[en]" );

		$this->add_theme_mod("team_former_members_text[de]" );
		$this->add_theme_mod("team_former_members_text[en]" );

	}

	private function add_controls(): void {
		$this->add_control( "team_page_title[de]", array(
			"section" => self::SECTION,
			"label"   => __( 'Heading (German)', 'gegenlicht' ),
		) );

		$this->add_control( "team_page_title[en]", array(
			"section" => self::SECTION,
			"label"   => __( 'Heading (English)', 'gegenlicht' ),
		) );

		$this->add_control( "team_page_text[de]",  array(
			"section" => self::SECTION,
			"label"   => __( 'Text (German)', 'gegenlicht' ),
			"type"    => "textarea"
		));

		$this->add_control( "team_page_text[en]",  array(
			"section" => self::SECTION,
			"label"   => __( 'Text (English)', 'gegenlicht' ),
			"type"    => "textarea"
		));


		$this->add_control( "team_former_members_text[de]",  array(
			"section" => self::SECTION,
			"label"   => __( 'Text about former members (German)', 'gegenlicht' ),
			"type"    => "textarea"
		));

		$this->add_control( "team_former_members_text[en]",  array(
			"section" => self::SECTION,
			"label"   => __( 'Text about former members (English)', 'gegenlicht' ),
			"type"    => "textarea"
		));


	}
}