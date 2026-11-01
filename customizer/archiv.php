<?php

namespace inc\customizer;

use WP_Customize_Manager;

require_once "base.php";

class ArchivePageCustomizer extends CPTheme_CustomizerBase {
	private const SECTION = 'archive';

	function __construct( WP_Customize_Manager $manager, int $priority = 20 ) {
		parent::__construct(
			$manager,
			__( 'Archive', 'cinema-press-theme' ),
			__( 'Configure the display of the archive page', 'cinema-press-theme' ),
			$priority,
			self::SECTION
		);

		$this->register_theme_mods();
		$this->register_controls();
	}

	private function register_theme_mods() {
		$this->add_theme_mod( "archive_text" );
	}

	private function register_controls() {
		$this->add_control( "archive_text", [
			"label" => __( "Text", "cinema-press-theme" ),
			"type"  => "textarea",
		] );
	}
}