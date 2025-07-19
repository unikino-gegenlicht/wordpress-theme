<?php
namespace GGL\customizer;

/**
 * Check if an attachment is an SVG image
 *
 * This function may be used for validating that a selected attachment is an SVG image.
 * It uses the {@link wp_check_filetype()} function to check on the extension and mime type of the selected attachment
 *
 * @param $attachmentID int
 *
 * @return bool|\WP_Error Returns true if the image is an SVG image, else a WP_Error is returned
 */
function is_attachment_svg_image(int $attachmentID): bool | \WP_Error {
	$localFilePath = get_attached_file($attachmentID, unfiltered: true);
	if ($localFilePath === false) {
		return new \WP_Error('file_nonexistent', __('The selected attachment does not exist on disk', 'gegenlicht'));
	}

	$fileInfo = wp_check_filetype($localFilePath, ["image/svg+xml", "image/svg"]);
	$fileExtension = $fileInfo['ext'] ?? null;
	$fileMimeType = $fileInfo['mime'] ?? null;

	if ($fileExtension === null && $fileMimeType === null) {
		return new \WP_Error('file_not_svg', __('The selected attachment is not a SVG image', 'gegenlicht'));
	}

	return true;
}

/**
 * This function returns all taxonomy terms of the taxonomy `semester`
 *
 * @return array{int, string}
 */
function get_semesters(): array {
	$terms = get_terms(array(
		'taxonomy' => 'semester',
		'hide_empty' => false,
	));

	$output = array();

	foreach ( $terms as $term ) {
		$output[$term->term_id] = $term->name;
	}

	return $output;
}

/**
 * Get all special programs
 *
 * @return array
 */
function get_special_programs(): array {
	$terms = get_terms(array(
		'taxonomy' => 'special-program',
		'hide_empty' => false,
	));

	$output = array();

	foreach ( $terms as $term ) {
		$output[$term->term_id] = $term->name;
	}

	return $output;
}