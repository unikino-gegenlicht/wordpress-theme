<?php

namespace inc\customizer;

use WP_Customize_Manager;
use WP_Query;

class DetailPageCustomizer extends GGLCustomizerBase {
	private const SECTION = "detail_page";

	function __construct( WP_Customize_Manager $manager, int $priority = 20, array $additionalArgs = [] ) {
		parent::__construct( $manager, __( "Detail Pages", "gegenlicht" ), __( "Configure behaviour on the detail pages of movies and events", "gegenlicht" ), $priority, self::SECTION, additionalArgs: $additionalArgs );

		$this->register_theme_mods();
		$this->register_controls();
	}

	private function register_theme_mods() {
		$this->add_theme_mod( "main_screening_location", null );
	}

	private function register_controls() {
		$this->add_control( "main_screening_location", [
			"label"       => __( "Main Screening Location", "gegenlicht" ),
			"description" => __( "If a different screening location is set in a post, a warning block will be displayed for users", "gegenlicht" ),
			"type"        => "select",
			"choices"     => $this->get_screening_location_options()
		] );
	}

	private function get_screening_location_options(): array {
		$query = new WP_Query( [
			"post_type"      => "screening-location",
			"posts_per_page" => - 1,
			"order"          => "ASC",
			"orderby"        => "title",
		] );

		$options = [];
		while ( $query->have_posts() ) {
			$query->the_post();
			$options[ $query->post->ID ] = $query->post->post_title;
		}

		return $options;
	}

}