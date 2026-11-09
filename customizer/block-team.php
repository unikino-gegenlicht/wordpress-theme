<?php

namespace inc\customizer;

use WP_Customize_Color_Control;
use WP_Customize_Manager;
use WP_Customize_Media_Control;

require_once "base.php";

class TeamBlockCustomizer extends CPTheme_CustomizerBase {
	const SECTION = "block_team";

	function __construct( WP_Customize_Manager $manager, int $priority = 20, array $additionalArgs = [] ) {
		parent::__construct( $manager, __( 'Team', 'gegenlicht' ), __( 'Configure the content of the block introducing our team', 'gegenlicht' ), $priority, self::SECTION, additionalArgs: array_merge( $additionalArgs, [
			"active_callback" => function () {
				return $this->is_block_enabled();
			}
		] ) );

		$this->register_theme_mods();
		$this->register_controls();
	}

	private function is_block_enabled(): bool {
		return in_array( "team", get_theme_mod( "displayed_blocks" ) ?? [] );
	}

	private function register_theme_mods(): void {
		$this->add_theme_mod( "team_block_image" );
		$this->add_theme_mod( "team_block_title" );
		$this->add_theme_mod( "team_block_text" );
		$this->add_theme_mod( "team_block_background" );
		$this->add_theme_mod( "team_block_text_color" );
		$this->add_theme_mod( "team_block_background_dark" );
		$this->add_theme_mod( "team_block_text_color_dark" );
	}

	private function register_controls(): void {
		$this->manager->add_control( new WP_Customize_Media_Control( $this->manager, "team_block_image", array(
			"section"     => self::SECTION,
			"label"       => __( 'Block Header Image', 'gegenlicht' ),
			"description" => __( 'Upload an image for the block header which will be displayed above the block title', 'gegenlicht' ),
			"panel"       => $this->panel,

		) ) );

		$this->add_control( "team_block_title", array(
			"section" => self::SECTION,
			"label"   => __( 'Block Title', 'cinema-press-theme' ),
			"type"    => "text",
			"panel"   => $this->panel,

		) );


		$this->add_control( "team_block_text", array(
			"section" => self::SECTION,
			"label"   => __( 'Block Text', 'cinema-press-theme' ),
			"type"    => "textarea",
			"panel"   => $this->panel,

		) );

		$this->manager->add_control( new WP_Customize_Color_Control( $this->manager, "team_block_background", array(
			'section'     => self::SECTION,
			'label'       => __( 'Background Color (Light Mode)', 'cinema-press-theme' ),
			'description' => __( 'Select a color for primary color. Selecting no color will inherit the default theme colors', 'cinema-press-theme' ),
		) ) );
		$this->manager->add_control( new WP_Customize_Color_Control( $this->manager, "team_block_text_color", array(
			'section'     => self::SECTION,
			'label'       => __( 'Font Color (Light Mode)', 'cinema-press-theme' ),
			'description' => __( 'Select a color for primary color. Selecting no color will inherit the default theme colors', 'cinema-press-theme' ),
		) ) );
		$this->manager->add_control( new WP_Customize_Color_Control( $this->manager, "team_block_background_dark", array(
			'section'     => self::SECTION,
			'label'       => __( 'Background Color (Dark Mode)', 'cinema-press-theme' ),
			'description' => __( 'Select a color for primary color. Selecting no color will inherit the default theme colors', 'cinema-press-theme' ),
		) ) );
		$this->manager->add_control( new WP_Customize_Color_Control( $this->manager, "team_block_text_color_dark", array(
			'section'     => self::SECTION,
			'label'       => __( 'Font Color (Dark Mode)', 'cinema-press-theme' ),
			'description' => __( 'Select a color for primary color. Selecting no color will inherit the default theme colors', 'cinema-press-theme' ),
		) ) );
	}

}