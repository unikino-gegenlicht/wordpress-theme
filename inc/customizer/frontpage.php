<?php
namespace GGL\customizer;

require_once 'functions.php';
require_once 'custom_controls.php';
/**
 * This file contains all theme options and settings regarding the front page
 */

const section = 'static_front_page';

function customize_frontpage_section(\WP_Customize_Manager $wp_customize): void {

	$wp_customize->get_section('static_front_page')->description = "";
	$wp_customize->get_section('static_front_page')->priority = 21;
	$wp_customize->remove_control('show_on_front');


	add_semester_selection($wp_customize);
	add_special_program_selection($wp_customize);
	add_show_blocks($wp_customize);
}

/**
 * Adds the selector for the displayed semester
 *
 * @param \WP_Customize_Manager $wp_customize
 *
 * @return void
 */
function add_semester_selection(\WP_Customize_Manager $wp_customize): void {
	$wp_customize->add_setting('displayed_semester', array(
		'type' => 'theme_mod',
		'capability' => 'edit_theme_options',
	));

	$wp_customize->add_control('displayed_semester', array(
		'label' => __('Displayed Semester', 'ggl-customizer'),
		'description' => __('Choose the semester you want to display on the front page', 'ggl-customizer'),
		'section' => section,
		'type' => 'select',
		'choices' => get_semesters(),
	));
}

/**
 * This function adds a multiselect
 *
 * @param \WP_Customize_Manager $wp_customize
 *
 * @return void
 */
function add_special_program_selection(\WP_Customize_Manager $wp_customize): void {
	$wp_customize->add_setting('displayed_special_programs', array(
		'type' => 'theme_mod',
		'default' => [],
		'capability' => 'edit_theme_options',
	));

	$choices = get_special_programs();

	$control = new WP_CheckboxList_Customize_Control($wp_customize, 'displayed_special_programs', array(
		'label' => __('Displayed Special Programs', 'gegenlicht'),
		'section' => section,
		'choices' => $choices,
		'type' => 'multi-select',
		'description' => __('Choose the special programs you want to display on the front page. (Use <kbd>Ctrl</kbd> to select multiple entries)', 'gegenlicht'),
	));

	$wp_customize->add_control($control);
}

function add_show_blocks(\WP_Customize_Manager $wp_customize): void {
	$wp_customize->add_setting('displayed_blocks', array(
		'type' => 'theme_mod',
		'default' => [],
		'capability' => 'edit_theme_options',
	));

	$control = new WP_CheckboxList_Customize_Control($wp_customize, 'displayed_blocks', array(
		'label' => __('Displayed Special Programs', 'gegenlicht'),
		'section' => section,
		'choices' => [
			'team' => __('Team Block', 'gegenlicht'),
			'location' => __('Location Block', 'gegenlicht'),
			'cooperations' =>  __('Cooperations Block', 'gegenlicht'),
		],
		'type' => 'multi-select',
		'description' => __('Choose additional blocks shown the front page. (Use <kbd>Ctrl</kbd> to select multiple entries)', 'gegenlicht'),
	));

	$wp_customize->add_control($control);
}


