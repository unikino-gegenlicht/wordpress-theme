<?php

require_once "base.php";

class LocationPageCustomizer extends GGLCustomizerBase {
	const SECTION = 'page_location';

	function __construct( WP_Customize_Manager $manager, int $priority = 20, array $additionalArgs = [] ) {
		parent::__construct( $manager, __( 'Location Page', 'gegenlicht' ), __( 'Configure the content of the page showcasing our location', 'gegenlicht' ), $priority, self::SECTION, additionalArgs: $additionalArgs );

		$this->add_settings();
		$this->add_controls();
	}

	private function add_settings(): void {
		$this->add_theme_mod( "location_map[light]" );
		$this->add_theme_mod( "location_map[dark]" );

		$this->add_theme_mod( "location_unikum_logo" );
		$this->add_theme_mod( "location_unikum_block_title[de]" );
		$this->add_theme_mod( "location_unikum_block_title[en]" );

		$this->add_theme_mod( "location_unikum_block_tagline[de]" );
		$this->add_theme_mod( "location_unikum_block_tagline[en]" );

		$this->add_theme_mod( "location_unikum_block_content[de]" );
		$this->add_theme_mod( "location_unikum_block_content[en]" );

		$this->add_theme_mod( "location_unikum_block_website_url" );

	}

	private function add_controls(): void {

		$this->manager->add_control( new WP_Customize_Media_Control( $this->manager, "location_map[light]", [
			"section"     => self::SECTION,
			"panel"       => $this->panel,
			"label"       => __( 'Map (Light Mode)', 'gegenlicht' ),
			"description" => __( "This map is displayed above the page contents on the location page", "gegenlicht" )
		] ) );
		$this->manager->add_control( new WP_Customize_Media_Control( $this->manager, "location_map[dark]", [
			"section"     => self::SECTION,
			"panel"       => $this->panel,
			"label"       => __( 'Map (Dark Mode)', 'gegenlicht' ),
			"description" => __( "This map is displayed above the page contents on the location page", "gegenlicht" )
		] ) );

		$this->manager->add_control( new WP_Customize_Media_Control( $this->manager, "location_unikum_logo", [
			"section"     => self::SECTION,
			"panel"       => $this->panel,
			"label"       => __( '[UNIKUM] Logo', 'gegenlicht' ),
			"description" => __( "This logo is displayed on the top of the Unikum block", "gegenlicht" )
		] ) );

		$this->add_control( "location_unikum_block_title[de]", array(
			"label"       => __( '[UNIKUM] Block Title (German)', 'gegenlicht' ),
			"description" => __( "The title of the Unikum block displayed below the page contents on the location page", "gegenlicht" )
		) );

		$this->add_control( "location_unikum_block_title[en]", array(
			"label"       => __( '[UNIKUM] Block Title (English)', 'gegenlicht' ),
			"description" => __( "The title of the Unikum block displayed below the page contents on the location page", "gegenlicht" )
		) );

		$this->add_control( "location_unikum_block_tagline[de]", array(
			"label"       => __( '[UNIKUM] Block Tagline (German)', 'gegenlicht' ),
			"description" => __( "The title of the Unikum block displayed below the page contents on the location page", "gegenlicht" )
		) );

		$this->add_control( "location_unikum_block_tagline[en]", array(
			"label"       => __( '[UNIKUM] Block Tagline (English)', 'gegenlicht' ),
			"description" => __( "The title of the Unikum block displayed below the page contents on the location page", "gegenlicht" )
		) );

		$this->add_control( "location_unikum_block_content[de]", array(
			"label"       => __( '[UNIKUM] Block Content (German)', 'gegenlicht' ),
			"description" => __( "The content of the Unikum block displayed below the page contents on the location page", "gegenlicht" ),
			"type"        => "textarea"
		) );

		$this->add_control( "location_unikum_block_content[en]", array(
			"label"       => __( '[UNIKUM] Block Content (English)', 'gegenlicht' ),
			"description" => __( "The content of the Unikum block displayed below the page contents on the location page", "gegenlicht" ),
			"type"        => "textarea"
		) );

		$this->add_control( "location_unikum_block_website_url", array(
			"label"       => __( "[UNIKUM] Website URL", "gegenlicht" ),
			"description" => __( "The url pointing to the website of the UNIKUM", "gegenlicht" ),
			"type"        => "url",
		) );
	}
}