<?php

require_once "base.php";

class SingleTeamCustomizer extends GGLCustomizerBase {
	const SECTION = 'single_team';

	function __construct( WP_Customize_Manager $manager, int $priority = 20, array $additionalArgs = [] ) {
		parent::__construct( $manager, __( 'Member (Single Page)', 'gegenlicht' ), __( 'Configure the content of the page showing our current team and our former members', 'gegenlicht' ), $priority, self::SECTION, additionalArgs: $additionalArgs );

		$this->add_settings();
		$this->add_controls();
	}

	private function add_settings(): void {
		$this->add_theme_mod( "active_text[de]" );
		$this->add_theme_mod( "active_text[en]" );

		$this->add_theme_mod( "former_text[de]" );
		$this->add_theme_mod( "former_text[en]" );
	}

	private function add_controls(): void {
		$this->add_control( "active_text[de]", array(
			"section" => self::SECTION,
			"label"   => __( 'Active Member Text (German)', 'gegenlicht' ),
			"description" => __("This text is displayed below each active team member on their individual page. The following variables are available for use: <br/> <code>%%name%%</code> – Name of the Member<br/><code>%%joinedIn%%</code> – Year in which the member joined<br/>", 'gegenlicht' ),
			"type" => "textarea",
		) );

		$this->add_control( "active_text[en]", array(
			"section" => self::SECTION,
			"label"   => __( 'Active Member Text (English)', 'gegenlicht' ),
			"description" => __("This text is displayed below each active team member on their individual page. The following variables are available for use: <br/> <code>%%name%%</code> – Name of the Member<br/><code>%%joinedIn%%</code> – Year in which the member joined<br/>", 'gegenlicht' ),
			"type" => "textarea",
		) );

		$this->add_control( "former_text[de]", array(
			"section" => self::SECTION,
			"label"   => __( 'Former Member Text (German)', 'gegenlicht' ),
			"description" => __("This text is displayed below each former team member on their individual page. The following variables are available for use: <br/> <code>%%name%%</code> – Name of the Member<br/><code>%%joinedIn%%</code> – Year in which the member joined<br/><code>%%leftIn%%</code> – Year in which the member left<br/>", 'gegenlicht' ),
			"type" => "textarea",
		) );

		$this->add_control( "former_text[en]", array(
			"section" => self::SECTION,
			"label"   => __( 'Former Member Text (English)', 'gegenlicht' ),
			"description" => __("This text is displayed below each former team member on their individual page. The following variables are available for use: <br/> <code>%%name%%</code> – Name of the Member<br/><code>%%joinedIn%%</code> – Year in which the member joined<br/><code>%%leftIn%%</code> – Year in which the member left<br/>", 'gegenlicht' ),
			"type" => "textarea",
		) );



	}
}