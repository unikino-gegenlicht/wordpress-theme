<?php

readonly class Displayable {
	public string $title;
	public DateTime $screeningDate;
	public string $mainGenre;
	public int $duration;
	public string $location;
	public string $imageUrl;

	function __construct( WP_Post $post ) {
		switch ( $post->post_type ) {
			case "movie":
				$this->fromMovie( $post );
				break;
			case "event":
				$this->fromEvent( $post );
				break;
			default:
				wp_die( 'unable to create displayable object from post' );
		}
		$this->imageUrl = get_the_post_thumbnail_url( $post->ID, 'full' );
	}

	private function fromMovie( WP_Post $movie ): void {
		$licenseType = get_post_meta( $movie->ID, "movie_license_type", true );
		switch ( $licenseType ) {
			case 'full':
				$this->title = $movie->post_title;
				break;
			case 'pool':
			case 'none':
				$this->title = __( 'Unnamed Movie' ); //todo: replace with theme mod
				break;
		}

		$this->screeningDate = DateTime::createFromFormat( 'U', get_post_meta( $movie->ID, "movie_screening_date", true ) );
		$this->location      = get_post_meta( $movie->ID, "movie_screening_location", true );
		$this->mainGenre     = "UPDATEFIX";
		$this->duration      = (int) get_post_meta( $movie->ID, "movie_running_time", true );
	}

	private function fromEvent( WP_Post $event ): void {
		$licenseType = get_post_meta( $event->ID, "event_license_type", true );
		switch ( $licenseType ) {
			case 'full':
				$this->title = $event->post_title;
				break;
			case 'n/a':
			case 'none':
			case 'pool':
				$this->title = "FUCK OFF"; //todo: replace with theme mod
				break;
			default:
				wp_die($licenseType, 'UNKNOWN LICENSE TYPE');
		}

		$this->screeningDate = DateTime::createFromFormat( 'U', get_post_meta( $event->ID, "event_screening_date", true ) );
		$this->location      = get_post_meta( $event->ID, "event_screening_location", true );
		$this->mainGenre     = "UPDATEFIX";
		$this->duration      = (int) get_post_meta( $event->ID, "event_duration", true );

	}
}