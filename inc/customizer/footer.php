<?php
require_once "base.php";
require_once "controls/custom_controls.php";

class FooterCustomizer extends GGLCustomizerBase {
	private const SECTION = "footer";

	function __construct( WP_Customize_Manager $manager, int $priority = 20, array $additionalArgs = [] ) {
		parent::__construct( $manager, __( 'Footer', 'gegenlicht' ), __( 'Configure the content displayed in the footer', 'gegenlicht' ), $priority, self::SECTION, additionalArgs: $additionalArgs );

		$this->register_theme_mods();
		$this->register_controls();
	}

	function register_theme_mods() {
		$this->add_theme_mod( "displayed_social_medias", [] );
		$this->add_theme_mod( "displayed_addresses", [] );
		$this->add_theme_mod( "show_youth_protection_officer", true );
		$this->add_theme_mod( "impress_page" );
		$this->add_theme_mod( "contact_page" );
	}

	function register_controls() {
		$this->manager->add_control( new WP_CheckboxList_Customize_Control( $this->manager, "displayed_social_medias", array(
			'section'     => self::SECTION,
			'label'       => __( "Displayed Social Medias", "gegenlicht" ),
			'description' => __( 'The selected social media pages will be linked in the footer', 'gegenlicht' ),
			'type'        => 'multi-select',
			'choices'     => $this->get_available_social_medias()
		) ) );

		$this->manager->add_control( new WP_CheckboxList_Customize_Control( $this->manager, "displayed_addresses", array(
			'section'     => self::SECTION,
			'label'       => __( "Displayed Addresses", "gegenlicht" ),
			'description' => __( 'The selected addresses will be shown in the footer', 'gegenlicht' ),
			'type'        => 'multi-select',
			'choices'     => [
				"visitor" => __( "Visitor's Address", "gegenlicht" ),
				"postal"  => __( "Postal Address", "gegenlicht" ),
			]
		) ) );

		$this->add_control("show_youth_protection_officer", array(
			'label' => __( 'Show Youth Protection Officer Data', 'gegenlicht' ),
			'description' => __("It's recommended to keep this enabled to ensure compliance with German Youth Protection Laws", "gegenlicht" ),
			'type'  => 'checkbox',
		));

		$this->add_control("impress_page", array(
			'label' => __( 'Select Impress Page', 'gegenlicht' ),
			'description' => __("Please select the page the impress is setup on", "gegenlicht" ),
			'type'  => 'dropdown-pages',
		));

		$this->add_control("contact_page", array(
			'label' => __( 'Select Contact Page', 'gegenlicht' ),
			'description' => __("Please select the page the contact details are setup on", "gegenlicht" ),
			'type'  => 'dropdown-pages',
		));


	}

	private function get_available_social_medias(): array {
		$return       = [];
		$socialMedias = get_theme_mod( "social_medias", [] );
		foreach ( $socialMedias as $name => $_ ) {
			if ( empty( $_ ) ) {
				continue;
			}

			$return[ $name ] = ucfirst( $name );
		}

		return $return;
	}
}
