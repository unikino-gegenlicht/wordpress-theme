<?php
require_once "base.php";

class ArchivePageCustomizer extends GGLCustomizerBase {
	private const SECTION = 'archive';

	function __construct( WP_Customize_Manager $manager, int $priority = 20 ) {
		parent::__construct(
			$manager,
			__( 'Archive', 'gegenlicht' ),
			__('Configure the display of the archive page', 'gegenlicht' ),
			$priority,
			self::SECTION
		);

		$this->register_theme_mods();
		$this->register_controls();
	}

	private function register_theme_mods() {
		$this->add_theme_mod("archive_header[de]");
		$this->add_theme_mod("archive_header[en]");

		$this->add_theme_mod("archive_text[de]");
		$this->add_theme_mod("archive_text[en]");
	}

	private function register_controls() {
		$this->add_control("archive_header[de]", [
			"label" => __("Heading (German)", "gegenlicht"),
		]);
		$this->add_control("archive_header[en]", [
			"label" => __("Heading (English)", "gegenlicht"),
		]);

		$this->add_control("archive_text[de]", [
			"label" => __("Text (German)", "gegenlicht"),
			"type" => "textarea",
		]);
		$this->add_control("archive_text[en]", [
			"label" => __("Text (English)", "gegenlicht"),
			"type" => "textarea",
		]);
	}
}