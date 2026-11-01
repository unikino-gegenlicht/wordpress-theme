<?php

namespace inc\customizer;

use WP_Customize_Manager;

require_once "base.php";

class CooperationAndSupportersBlockCustomizer extends CPTheme_CustomizerBase {
	const SECTION = "block_cooperation_and_supporters";

	function __construct( WP_Customize_Manager $manager, int $priority = 20, array $additionalArgs = [] ) {
		parent::__construct( $manager, __( 'Cooperation Partners and Supporters', 'cinema-press-theme' ), __( 'Configure the content of the block showcasing our supporters and cooperation partners', 'cinema-press-theme' ), priority: $priority, section: self::SECTION, additionalArgs: array_merge( $additionalArgs, [
			"active_callback" => function () {
				return $this->is_block_enabled();
			}
		] ) );

		$this->register_theme_mods();
		$this->register_controls();
	}

	private function is_block_enabled(): bool {
		return in_array( "cooperations", get_theme_mod( "displayed_blocks" ) ?? [] );
	}

	private function register_theme_mods(): void {
		$this->add_theme_mod( "coop_block_text" );

	}

	private function register_controls(): void {
		$this->add_control( "coop_block_text", array(
			"section" => self::SECTION,
			"label"   => __( 'Block Text', 'cinema-press-theme' ),
			"type"    => "textarea",

		) );

	}

}