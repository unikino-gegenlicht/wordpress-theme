<?php
require_once "base.php";

class SocialMediasOptionsCustomizer extends GGLCustomizerBase {
	private const SECTION = "social-medias";

	function __construct( WP_Customize_Manager $manager, int $priority = 20, array $additionalArgs = [] ) {
		parent::__construct(
			$manager,
			__( 'Social Media', 'gegenlicht' ),
			__('Configure the available social media options listed throughout the website', 'gegenlicht' ),
			$priority,
			self::SECTION,
			additionalArgs: $additionalArgs
		);

		$this->register_theme_mods();
		$this->register_controls();
	}

	private function register_theme_mods() {
		$this->add_theme_mod("social_medias[instagram]");
		$this->add_theme_mod("social_medias[mastodon]");
		$this->add_theme_mod("social_medias[facebook]");
		$this->add_theme_mod("social_medias[discord]");
		$this->add_theme_mod("social_medias[letterboxd]");
	}

	private function register_controls(): void {
		$this->add_control("social_medias[instagram]", array(
			"label" => __( 'Instagram URL', 'gegenlicht' ),
		));
		$this->add_control("social_medias[mastodon]", array(
			"label" => __( 'Mastodon URL', 'gegenlicht' ),
		));
		$this->add_control("social_medias[facebook]", array(
			"label" => __( 'Facebook URL', 'gegenlicht' ),
		));
		$this->add_control("social_medias[discord]", array(
			"label" => __( 'Discord Server Invitation URL', 'gegenlicht' ),
			"description" => __("Please ensure that the invite url does not expire")
		));
		$this->add_control("social_medias[letterboxd]", array(
			"label" => __( 'Letterbox URL', 'gegenlicht' ),
		));
	}

}