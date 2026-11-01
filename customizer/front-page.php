<?php

namespace inc\customizer;

use inc\customizer\controls\WP_CheckboxList_Customize_Control;
use WP_Customize_Manager;

require_once "base.php";
require_once "controls/custom_controls.php";

class FrontPageCustomizerOverrides extends CPTheme_CustomizerBase {
	const SECTION = "static_front_page";

	function __construct( WP_Customize_Manager $manager, int $priority = 21, array $additionalArgs = [ "skipAddSection" => true ] ) {
		parent::__construct( $manager, "", "", $priority, self::SECTION, additionalArgs: $additionalArgs );

		/**
		 * Remove unused controls from the customizer and update the description of the section as well as allowing
		 * less privileged users to control the section
		 */
		$this->manager->get_section( self::SECTION )->description = __( "Configure the content displayed on the front page", "cinema-press-theme" );
		$this->manager->get_section( self::SECTION )->priority    = $priority;
		$this->manager->get_section( self::SECTION )->capability  = "edit_others_posts";
		$this->manager->remove_control( 'show_on_front' );

		$this->add_theme_mods();
		$this->add_controls();
	}

	private function add_theme_mods(): void {
		$this->add_theme_mod( "displayed_semester" );
		$this->add_theme_mod( "displayed_special_programs", array() );
		$this->add_theme_mod( "displayed_blocks", array() );
		$this->add_theme_mod( "program_reveal_delay", 14 );
	}

	private function add_controls(): void {
		$this->add_control( "displayed_semester", array(
			'section'     => self::SECTION,
			'type'        => 'select',
			'label'       => __( "Displayed Semester", "cinema-press-theme" ),
			'description' => __( 'Only movies and events associated with this semester will be displayed on the front page and be accessible for non-logged in users', "cinema-press-theme" ),
			'choices'     => $this->get_semesters()
		) );

		$this->manager->add_control( new WP_CheckboxList_Customize_Control( $this->manager, "displayed_special_programs", array(
			'section'     => self::SECTION,
			'label'       => __( "Displayed Special Programs", "cinema-press-theme" ),
			'description' => __( "The selected special programs will be displayed in their respective colors below the semester program" ),
			'type'        => 'multi-select',
			'choices'     => $this->get_special_programs()
		) ) );

		$this->manager->add_control( new WP_CheckboxList_Customize_Control( $this->manager, "displayed_blocks", array(
			'section'     => self::SECTION,
			'label'       => __( "Displayed Blocks", "cinema-press-theme" ),
			'description' => __( "The selected blocks will be displayed below the special programs. Only if a block is activated here, the customizer for that block will be accessible", "cinema-press-theme" ),
			'type'        => 'multi-select',
			'choices'     => [
				'team'         => __( 'Team', 'cinema-press-theme' ),
				'location'     => __( 'Location', 'cinema-press-theme' ),
				'cooperations' => __( 'Cooperation Partners and Supporters', 'cinema-press-theme' )
			]
		) ) );

		$this->add_control( "program_reveal_delay", array(
			'section'     => self::SECTION,
			'type'        => 'number',
			'label'       => __( 'Program Reveal', "cinema-press-theme" ),
			'description' => __( 'This value controls how many days before the start of the screenings the frontpage shows the semester program', 'cinema-press-theme' ),
		) );
	}

	private function get_semesters(): array {
		$terms = get_terms( array(
			'taxonomy'   => 'semester',
			'hide_empty' => false,
			"meta_key"   => "semester_start",
			"orderby"    => "meta_value_num",
			"order"      => "DESC",
		) );

		$output = array();

		foreach ( $terms as $term ) {
			$output[ $term->term_id ] = $term->name;
		}

		return $output;
	}

	private function get_special_programs(): array {
		$terms = get_terms( array(
			'taxonomy'   => 'program_series',
			'hide_empty' => false,
		) );

		$output = array();

		foreach ( $terms as $term ) {
			$output[ $term->term_id ] = $term->name;
		}

		return $output;
	}
}