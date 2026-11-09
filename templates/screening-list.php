<?php
/*
 * Copyright (c) 2026. Unikino GEGENLICHT. Licensed under the EUPL v1.2 or later
 */

/**
 * Movie and Proposal List Partial
 *
 * @type array $args {
 *     Required. Array of arguments for the movie list
 * @type string $title Required. Title shown in the header of the list
 * @type WP_Post[]|int[] $posts Required. The screenings that should be shown on the list
 * }
 */


$heading           = $args["title"] ?? "";
$screenings        = $args["screenings"] ?? [];
$future_screenings = $args["future_screenings"] ?? true;

$template_date_string = $future_screenings ? esc_html__( /* translators: %s is the date the movie was screened on */ 'Screened on %s', 'cinema-press-theme' ) : "%s";
$date_format          = $future_screenings ? CINEMA_PRESS__DATETIME_FORMAT : CINEMA_PRESS__DATE_FORMAT;
?>
<div class="movie-list is-flex-grow-2">
    <p class="movie-list-title mb-0"><?= esc_html( $heading ) ?></p>
    <div class="movie-list-entries">
        <?php foreach ( $screenings as $screening ) : $screening = get_post( $screening ); ?>
            <a class="entry is-flex-direction-row is-align-items-center"
               href="<?= get_post_permalink( $screening->ID ) ?>">
                <div>
                    <p class="m-0"><?= sprintf( $template_date_string, rwmb_the_value( 'screening_start', [ 'format' => $date_format ], $screening->ID, false ) ) ?></p>
                    <h2 class="movie-title is-size-5 mt-0 has-text-weight-semibold"><?php rwmb_the_value( 'german_title', post_id: rwmb_meta( 'movie', post_id: $screening->ID ) ) ?></h2>
                </div>
                <span class="icon is-medium">
                    <i class="fa-light fa-lg fa-chevron-right"></i>
                </span>
            </a>
        <?php endforeach; ?>
    </div>
</div>

