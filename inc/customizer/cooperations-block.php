<?php
require_once "base.php";

class CooperationAndSupportersFrontpageBlockCustomizer extends GGLCustomizerBase {
	const SECTION = "block_cooperation_and_supporters";

	function __construct( WP_Customize_Manager $manager, int $priority = 20, array $additionalArgs = [] ) {
		parent::__construct(
			$manager,
			__( 'Cooperation Partners and Supporters', 'gegenlicht' ),
			__('Configure the content of the block showcasing our supporters and cooperation partners', 'gegenlicht' ),
			$priority,
			self::SECTION,
			additionalArgs: $additionalArgs,
		);

		$this->register_theme_mods();
		$this->register_controls();
	}

	private function register_theme_mods(): void {
		$this->add_theme_mod("coop_block_text[de]");
		$this->add_theme_mod("coop_block_text[en]");

		$this->add_theme_mod("coop_block_text_color[light]");
		$this->add_theme_mod("coop_block_text_color[dark]");

		$this->add_theme_mod("coop_block_bg_color[light]");
		$this->add_theme_mod("coop_block_bg_color[dark]");
	}

	private function register_controls(): void {
		$this->add_control("coop_block_text[de]", array(
			"section" => self::SECTION,
			"label" => __( 'Text (German)', 'gegenlicht' ),
			"type" => "textarea",
		));

		$this->add_control("coop_block_text[en]", array(
			"section" => self::SECTION,
			"label" => __( 'Text (English)', 'gegenlicht' ),
			"type" => "textarea",
		));

		$this->add_control("coop_block_text_color[light]", array(
			"section" => self::SECTION,
			"label" => __( 'Text Color (Light Mode)', 'gegenlicht' ),
			"description" => __( 'The color of the text and partner logos (if possible)', 'gegenlicht' ),
			"type" => "color",
		));

		$this->add_control("coop_block_bg_color[light]", array(
			"section" => self::SECTION,
			"label" => __( 'Background Color (Light Mode)', 'gegenlicht' ),
			"description" => __( 'The background color of the whole block', 'gegenlicht' ),
			"type" => "color",
		));

		$this->add_control("coop_block_text_color[dark]", array(
			"section" => self::SECTION,
			"label" => __( 'Text Color (Dark Mode)', 'gegenlicht' ),
			"description" => __( 'The color of the text and partner logos (if possible)', 'gegenlicht' ),
			"type" => "color",
		));

		$this->add_control("coop_block_bg_color[dark]", array(
			"section" => self::SECTION,
			"label" => __( 'Background Color (Dark Mode)', 'gegenlicht' ),
			"description" => __( 'The background color of the whole block', 'gegenlicht' ),
			"type" => "color",
		));
	}
}