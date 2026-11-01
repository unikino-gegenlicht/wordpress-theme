<?php


namespace inc\customizer;

use WP_Customize_Manager;
use WP_Customize_Media_Control;

class SemesterBreakCustomizer extends CPTheme_CustomizerBase {
	private const SECTION = 'semester_break';

	function __construct( WP_Customize_Manager $manager, int $priority = 20, array $additionalArgs = [] ) {
		parent::__construct( $manager, __( 'Semester Intermission', 'cinema-press-theme' ), __( 'Configure the content of the page showing our cooperation partners and how to to a cooperation with us', 'cinema-press-theme' ), $priority, self::SECTION, additionalArgs: $additionalArgs );

		$this->add_settings();
		$this->add_controls();
	}

	private function add_settings(): void {
		$this->add_theme_mod( "manual_semester_break" );
		$this->add_theme_mod( "semester_break_image" );
		$this->add_theme_mod( "semester_break_tagline" );
		$this->add_theme_mod( "semester_break_premonition_text" );
		$this->add_theme_mod( "semester_break_text" );
		$this->add_theme_mod( "semester_break_pre_events_text" );
		$this->add_theme_mod( "semester_break_post_events_text" );
		$this->add_theme_mod( "semester_break_archive_text" );
	}

	private function add_controls(): void {

		/**
		 * Add the control for `manual_semester_break`
		 */
		$this->add_control( "manual_semester_break", array(
			"section"     => self::SECTION,
			"label"       => esc_html__( "Enable Semester Intermission", "cinema-press-theme" ),
			"description" => esc_html__( "Override the detection algorithm and manually enable a semester break", "cinema-press-theme" ),
			"type"        => "checkbox",
		) );

		/**
		 * Add the control for selecting the image displayed during the semester break
		 */
		$this->manager->add_control( new WP_Customize_Media_Control( $this->manager, 'semester_break_image', array(
			'label'       => __( 'Intermission Image', 'cinema-press-theme' ),
			'description' => __( 'Upload an image which will be displayed during the program intermission between two semesters', 'cinema-press-theme' ),
			'section'     => self::SECTION,
		) ) );

		$this->add_control( "semester_break_tagline", array(
			"section"     => self::SECTION,
			"label"       => esc_html__( "Tagline", "cinema-press-theme" ),
			"description" => esc_html__( "Set the tagline displayed below the intermission image if no events during the intermission are taking place", "cinema-press-theme" ),
			"type"        => "text",
		) );


		$this->add_control( "semester_break_premonition_text", array(
			"section"     => self::SECTION,
			"label"       => esc_html__( "Intermission Premonition Text", "cinema-press-theme" ),
			"description" => esc_html__( "Set the text that will be displayed if the program is about to finish and doesn't have any entries left to display in the monthly lists", "cinema-press-theme" ),
			"type"        => "textarea"
		) );


		$this->add_control( "semester_break_text", array(
			"section"     => self::SECTION,
			"label"       => esc_html__( "Intermission Text", "cinema-press-theme" ),
			"description" => esc_html__( "Set the content of the intermission page", "cinema-press-theme" ),
			"type"        => "textarea"
		) );


		$this->add_control( "semester_break_pre_events_text", array(
			"section"     => self::SECTION,
			"label"       => esc_html__( "Pre-Event List Text", "cinema-press-theme" ),
			"description" => esc_html__( "Set the content of the intermission page. This text will be displayed if events are happening during the intermission", "cinema-press-theme" ),
			"type"        => "textarea"
		) );


		$this->add_control( "semester_break_post_events_text", array(
			"section"     => self::SECTION,
			"label"       => esc_html__( "Post-Event List Text", "cinema-press-theme" ),
			"description" => esc_html__( "Set the content of the intermission page. This text will be displayed if events are happening during the intermission", "cinema-press-theme" ),
			"type"        => "textarea"
		) );


		$this->add_control( "semester_break_archive_text", array(
			"section"     => self::SECTION,
			"label"       => esc_html__( "Archive Reference Text", "cinema-press-theme" ),
			"description" => esc_html__( "Set the content of the text referring to the archive", "cinema-press-theme" ),
			"type"        => "textarea"
		) );


	}
}
