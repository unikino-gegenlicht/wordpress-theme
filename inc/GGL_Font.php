<?php

class GGL_Font {
	public readonly string $name;
	public readonly string $public_path;
	public readonly string $hash;
	/**
	 * @type array $font_face_attrs {
	 * @type string $key
	 * @type string $value
	 * }
	 */
	public readonly array $font_face_attrs;
	public readonly array $additional_classes;
	public readonly string $type;
	private string $local_path;

	function __construct( $name, $public_path, $type, $font_face_attrs = [], $additional_classes = [] ) {
		$this->name               = $name;
		$this->public_path        = $public_path;
		$this->font_face_attrs    = $font_face_attrs;
		$this->additional_classes = $additional_classes;
		$this->type               = $type;

		$this->local_path = parse_url( $public_path, PHP_URL_PATH );
		$this->hash       = md5_file( ABSPATH . $this->local_path );
	}
}