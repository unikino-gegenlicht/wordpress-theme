<?php

class GGLCustomizerBase {
	protected string $section;
	protected string $panel;
	protected string $capability;
	protected WP_Customize_Manager $manager;
	protected int $priority;

	public function __construct( WP_Customize_Manager $manager, string $title, string $description, int $priority, string $section, string $capability = "edit_others_posts", array $additionalArgs = [] ) {
		$this->manager    = $manager;
		$this->section    = $section;
		$this->priority   = $priority;
		$this->capability = $capability;
		$this->panel      = $additionalArgs["panel"] ?? "";

		$this->manager->add_section( $section, args: array_merge( [
			'title'       => $title,
			'description' => $description,
			'priority'    => $this->priority,
			'capability'  => $capability,
		], $additionalArgs ) );
	}

	/**
	 * Shortcut to add a new theme mod to the customization options.
	 *
	 * For higher customization of a setting (e.g. setting the sanitization) please use the manager directly
	 *
	 * @param string $id
	 * @param mixed $default
	 *
	 * @return void
	 */
	protected function add_theme_mod( string $id, mixed $default = "" ): void {
		$this->manager->add_setting( $id, array(
			"type"    => "theme_mod",
			"default" => $default,
		) );
	}

	protected function add_control( string $setting, array $args ): void {
		$this->manager->add_control( $setting, array_merge( $args, array( "panel" => $this->panel ) ) );
	}
}