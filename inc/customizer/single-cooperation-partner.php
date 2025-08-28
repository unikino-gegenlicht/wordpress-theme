<?php

require_once "base.php";

class SingleCoopCustomizer extends GGLCustomizerBase {
	const SECTION = 'single_coop';

	function __construct( WP_Customize_Manager $manager, int $priority = 20, array $additionalArgs = [] ) {
		parent::__construct( $manager, __( 'Cooperation Partner (Single Page)', 'gegenlicht' ), __( 'Configure the content of the page showing our current team and our former members', 'gegenlicht' ), $priority, self::SECTION, additionalArgs: $additionalArgs );

		$this->add_settings();
		$this->add_controls();
	}

	private function add_settings(): void {
		$this->add_theme_mod( "missing_coop_entries[de]" );
		$this->add_theme_mod( "missing_coop_entries[en]" );
	}

	private function add_controls(): void {
		$this->add_control( "missing_coop_entries[de]", array(
			"section" => self::SECTION,
			"label"   => __( 'Missing Entries Explanation (German)', 'gegenlicht' ),
			"description" => __("This text is displayed if the cooperation partner never had any cooperation screenings with us, but is created nevertheless", 'gegenlicht' ),
			"type" => "textarea",
		) );

		$this->add_control( "missing_coop_entries[en]", array(
			"section" => self::SECTION,
			"label"   => __( 'Missing Entries Explanation (English)', 'gegenlicht' ),
			"description" => __("This text is displayed if the cooperation partner never had any cooperation screenings with us, but is created nevertheless", 'gegenlicht' ),
			"type" => "textarea",
		) );


	}
}