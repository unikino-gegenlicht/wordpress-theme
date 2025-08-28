<?php
require_once "base.php";
class AnonymizationCustomizer extends GGLCustomizerBase {

	/**
	 * The identifier of the section created by the customizer
	 */
	private const SECTION = 'anonymization';


	function __construct( WP_Customize_Manager $manager, int $priority = 20 ) {
		parent::__construct(
			$manager,
			__( 'Anonymization', 'gegenlicht' ),
			__('Configure the anonymization functions of the theme', 'gegenlicht' ),
			$priority,
			self::SECTION
		);

		$this->register_theme_mods();
		$this->register_controls();
	}

	private function register_theme_mods(): void {
		$this->add_theme_mod( "anonymous_image");
		$this->add_theme_mod( "anonymous_team_image");

		$this->add_theme_mod("anonymized_movie_explainer[de]");
		$this->add_theme_mod("anonymized_movie_explainer[en]");

		$this->add_theme_mod("special_program_anonymous_explainer[de]");
		$this->add_theme_mod("special_program_anonymous_explainer[en]");
	}

	private function register_controls(): void {
		$this->manager->add_control(new WP_Customize_Media_Control($this->manager, "anonymous_image", array(
			'section' => self::SECTION,
			'label' => __( 'Anonymous Event/Movie Image', 'gegenlicht' ),
			'description' => __("This image is displayed if a movie or event doesn't have a advertisement license set in the backend. Additionally, the image might be displayed as fallback image in case a event or movie doesn't have one set", "gegenlicht")
		)));

		$this->manager->add_control("anonymized_movie_explainer[de]", array(
			'section' => self::SECTION,
			'label' => __( 'Anonymous Movie Explanation (German)', 'gegenlicht' ),
			'type'    => 'textarea',
			'description' => __("This text is displayed below the anonymized movie image explaining why some data is missing on the page.")
		));

		$this->manager->add_control("anonymized_movie_explainer[en]", array(
			'section' => self::SECTION,
			'label' => __( 'Anonymous Movie Explanation (English)', 'gegenlicht' ),
			'type'    => 'textarea',
			'description' => __("This text is displayed below the anonymized movie image explaining why some data is missing on the page.")
		));

		$this->manager->add_control("special_program_anonymous_explainer[de]", array(
			'section' => self::SECTION,
			'label' => __( 'Hidden Movie List Explanation (German)', 'gegenlicht' ),
			'type'    => 'textarea',
			'description' => __("This text is displayed on the display pages of the special programs if a user is not logged in.")
		));

		$this->manager->add_control("special_program_anonymous_explainer[en]", array(
			'section' => self::SECTION,
			'label' => __( 'Hidden Movie List Explanation (English)', 'gegenlicht' ),
			'type'    => 'textarea',
			'description' => __("This text is displayed on the display pages of the special programs if a user is not logged in.")
		));

		$this->manager->add_control(new WP_Customize_Media_Control($this->manager, "anonymous_team_image", array(
			'section' => self::SECTION,
			'label' => __( 'Anonymous Team Member Image', 'gegenlicht' ),
			'description' => __("This image is displayed if a team member doesn't have a picture set for them", "gegenlicht")
		)));
	}
}
