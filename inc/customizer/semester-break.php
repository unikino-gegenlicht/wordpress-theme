<?php


class SemesterBreakCustomizer extends GGLCustomizerBase {
	private const SECTION = 'semester_break';

	function __construct( WP_Customize_Manager $manager, int $priority = 20, array $additionalArgs = [] ) {
		parent::__construct( $manager, __( 'Semester Intermission', 'gegenlicht' ), __( 'Configure the content of the page showing our cooperation partners and how to to a cooperation with us', 'gegenlicht' ), $priority, self::SECTION, additionalArgs: $additionalArgs );

		$this->add_settings();
		$this->add_controls();
	}

	private function add_settings(): void {
		$this->add_theme_mod( "manual_semester_break" );
		$this->add_theme_mod( "semester_break_image" );
		$this->add_theme_mod( "semester_break_tagline[de]" );
		$this->add_theme_mod( "semester_break_tagline[en]" );
		$this->add_theme_mod( "semester_break_premonition_text[de]" );
		$this->add_theme_mod( "semester_break_premonition_text[en]" );
		$this->add_theme_mod( "semester_break_text[de]" );
		$this->add_theme_mod( "semester_break_text[en]" );
		$this->add_theme_mod( "semester_break_pre_events_text[de]" );
		$this->add_theme_mod( "semester_break_pre_events_text[en]" );
		$this->add_theme_mod( "semester_break_post_events_text[de]" );
		$this->add_theme_mod( "semester_break_post_events_text[en]" );
		$this->add_theme_mod( "semester_break_archive_text[de]" );
		$this->add_theme_mod( "semester_break_archive_text[en]" );
	}

	private function add_controls(): void {

		/**
		 * Add the control for `manual_semester_break`
		 */
		$this->add_control( "manual_semester_break", array(
			"section"     => self::SECTION,
			"label"       => esc_html__( "Enable Semester Intermission", "gegenlicht" ),
			"description" => esc_html__( "Override the detection algorithm and manually enable a semester break", "gegenlicht" ),
			"type"        => "checkbox",
		) );

		/**
		 * Add the control for selecting the image displayed during the semester break
		 */
		$this->manager->add_control( new WP_Customize_Media_Control( $this->manager, 'semester_break_image', array(
			'label'       => __( 'Intermission Image', 'gegenlicht' ),
			'description' => __( 'Upload an image which will be displayed during the program intermission between two semesters', 'gegenlicht' ),
			'section'     => self::SECTION,
		) ) );

		$this->add_control( "semester_break_tagline[de]", array(
			"section"     => self::SECTION,
			"label"       => esc_html__( "Tagline (German)", "gegenlicht" ),
			"description" => esc_html__( "Set the tagline displayed below the intermission image if no events during the intermission are taking place", "gegenlicht" ),
			"type"        => "text",
		) );

		$this->add_control( "semester_break_tagline[en]", array(
			"section"     => self::SECTION,
			"label"       => esc_html__( "Tagline (English)", "gegenlicht" ),
			"description" => esc_html__( "Set the tagline displayed below the intermission image if no events during the intermission are taking place", "gegenlicht" ),
			"type"        => "text",
		) );

		$this->add_control( "semester_break_premonition_text[de]", array(
			"section"     => self::SECTION,
			"label"       => esc_html__( "Intermission Premonition Text (German)", "gegenlicht" ),
			"description" => esc_html__( "Set the text that will be displayed if the program is about to finish and doesn't have any entries left to display in the monthly lists", "gegenlicht" ),
			"type"        => "textarea"
		) );
		$this->add_control( "semester_break_premonition_text[en]", array(
			"section"     => self::SECTION,
			"label"       => esc_html__( "Intermission Premonition Text (English)", "gegenlicht" ),
			"description" => esc_html__( "Set the text that will be displayed if the program is about to finish and doesn't have any entries left to display in the monthly lists", "gegenlicht" ),
			"type"        => "textarea"
		) );

		$this->add_control( "semester_break_text[de]", array(
			"section"     => self::SECTION,
			"label"       => esc_html__( "Intermission Text (German)", "gegenlicht" ),
			"description" => esc_html__( "Set the content of the intermission page", "gegenlicht" ),
			"type"        => "textarea"
		) );

		$this->add_control( "semester_break_text[en]", array(
			"section"     => self::SECTION,
			"label"       => esc_html__( "Intermission Text (English)", "gegenlicht" ),
			"description" => esc_html__( "Set the content of the intermission page", "gegenlicht" ),
			"type"        => "textarea"
		) );

		$this->add_control( "semester_break_pre_events_text[de]", array(
			"section"     => self::SECTION,
			"label"       => esc_html__( "Pre-Event List Text (German)", "gegenlicht" ),
			"description" => esc_html__( "Set the content of the intermission page. This text will be displayed if events are happening during the intermission", "gegenlicht" ),
			"type"        => "textarea"
		) );

		$this->add_control( "semester_break_pre_events_text[en]", array(
			"section"     => self::SECTION,
			"label"       => esc_html__( "Pre-Event List Text (English)", "gegenlicht" ),
			"description" => esc_html__( "Set the content of the intermission page. This text will be displayed if events are happening during the intermission", "gegenlicht" ),
			"type"        => "textarea"
		) );

		$this->add_control( "semester_break_post_events_text[de]", array(
			"section"     => self::SECTION,
			"label"       => esc_html__( "Post-Event List Text (German)", "gegenlicht" ),
			"description" => esc_html__( "Set the content of the intermission page. This text will be displayed if events are happening during the intermission", "gegenlicht" ),
			"type"        => "textarea"
		) );

		$this->add_control( "semester_break_post_events_text[en]", array(
			"section"     => self::SECTION,
			"label"       => esc_html__( "Post-Event List Text (English)", "gegenlicht" ),
			"description" => esc_html__( "Set the content of the intermission page. This text will be displayed if events are happening during the intermission", "gegenlicht" ),
			"type"        => "textarea"
		) );

		$this->add_control( "semester_break_archive_text[de]", array(
			"section"     => self::SECTION,
			"label"       => esc_html__( "Archive Reference Text (German)", "gegenlicht" ),
			"description" => esc_html__( "Set the content of the text referring to the archive", "gegenlicht" ),
			"type"        => "textarea"
		) );

		$this->add_control( "semester_break_archive_text[en]", array(
			"section"     => self::SECTION,
			"label"       => esc_html__( "Archive Reference Text (English)", "gegenlicht" ),
			"description" => esc_html__( "Set the content of the text referring to the archive", "gegenlicht" ),
			"type"        => "textarea"
		) );

	}
}
