<?php
/**
 * Location Button
 */
$screening_location_id = (int) ( $args['screening_location_id'] ?? - 1 );

if ( $screening_location_id !== - 1 ) {
    $place_name      = get_post( $screening_location_id )->post_title;
    $apple_place_id  = rwmb_get_value( "apple_place_id", post_id: $screening_location_id ) ?? null;
    $google_place_id = rwmb_get_value( "google_place_id", post_id: $screening_location_id ) ?? null;
    $lat             = rwmb_get_value( "lat", post_id: $screening_location_id ) ?? null;
    $long            = rwmb_get_value( "long", post_id: $screening_location_id ) ?? null;
    $street          = rwmb_get_value( "street", post_id: $screening_location_id ) ?? "";
    $postal_code     = rwmb_get_value( "postal_code", post_id: $screening_location_id ) ?? "";
    $city            = rwmb_get_value( "city", post_id: $screening_location_id ) ?? "";
} else {
    $place_name      = (string) ( $args['place_name'] ?? "" );
    $lat             = (float) ( $args['latitude'] ?? $args['lat'] ?? "0" );
    $long            = (float) ( $args['longitude'] ?? $args['long'] ?? $args["lng"] ?? "0" );
    $apple_place_id  = ( $args['apple_place_id'] ?? null );
    $google_place_id = ( $args['google_place_id'] ?? null );
    $street          = (string) ( $args['street'] ?? "" );
    $postal_code     = (string) ( $args['postal_code'] ?? "" );
    $city            = (string) ( $args['city'] ?? "" );
}

$platform = get_browser()->platform;
if ( $platform === 'iOS' ):
    $query_params = [
            "coordinate" => $apple_place_id != null ? join(",", [ $lat, $long ]) : null,
            "center"     => $apple_place_id == null ? join(",", [ $lat, $long ]) : null,
            "place-id"   => $apple_place_id,
            "query"      => $apple_place_id == null ? ( mb_trim( "$street, $postal_code $city" ) != "" ? mb_trim( "$street, $postal_code $city" ) : null ) : null,
    ];

    $target_url = ( $apple_place_id !== null ? "https://maps.apple.com/place?" : "https://maps.apple.com/search?" ) . http_build_query( $query_params );
else:
    $query_params = [
            "api"            => 1,
            "query"          => $place_name . "," . (( $lat != null && $long != null ) ? "$lat, $long" : ""),
            "query_place_id" => $google_place_id,
    ];

    $target_url = "https://www.google.com/maps/search/?" . http_build_query( $query_params );
endif;
?>
<a href="<?= esc_attr( $target_url ) ?>"
   role="link"
   class="button is-outlined is-fullwidth mt-2 py-3 is-uppercase is-size-5 has-text-weight-bold"
   target="_blank">
	<span class="icon-text is-align-items-center is-justify-content-space-around">
		<span><?= $platform === "iOS" ? esc_html__( "Show on Apple Maps", "gegenlicht" ) : esc_html__( "Show on Google Maps", "gegenlicht" ) ?></span>
		<span class="icon">
			<span class="material-symbols is-size-4">explore</span>
		</span>
	</span>
</a>
