<?php

$postID = ($args['post-id'] ?? null);
$lazyLoad = $args['lazyload'] ?? false;
$style = ($args['style'] ?? "");

if (isset($post) && $postID != null):
    $desktopImageUrl = get_the_post_thumbnail_url( $postID, 'desktop' ) ?: wp_get_attachment_image_url( get_theme_mod( 'anonymous_image' ), 'desktop' ) ?: "";
    $mobileImageUrl = get_the_post_thumbnail_url( $postID, 'mobile' ) ?: wp_get_attachment_image_url( get_theme_mod( 'anonymous_image' ), 'mobile' ) ?: "";
	$alternativeDescription = (($imageID = attachment_url_to_postid($desktopImageUrl)) == 0) ? "" : get_post_meta($imageID, '_wp_attachment_image_alt', true);
else:
    $desktopImageUrl = $args['image_url'] ?? wp_get_attachment_image_url( get_theme_mod( 'anonymous_image' ), 'desktop' ) ?: "";
    $mobileImageUrl = $args['mobile_image_url'] ?? $args['image_url'] ?? wp_get_attachment_image_url( get_theme_mod( 'anonymous_image' ), 'mobile' ) ?: "";
    $alternativeDescription = $args['alternative_description'] ?? "";
endif;

?>
<figure class="image is-hidden-tablet is-4by5 movie-image mt-4">
    <img alt="<?= $alternativeDescription ?>" fetchpriority="<?= $args['fetch-priority'] ?? 'normal' ?>"
         src="<?= $desktopImageUrl ?>" style="<?= esc_attr($style) ?>" loading="<?= $lazyLoad ? 'lazy': 'eager' ?>"/>
</figure>
<figure class="image is-hidden-mobile <?= ($args['disable16by9'] ?? false) ? '' : 'is-16by9' ?> movie-image mt-4">
    <img alt="<?= $alternativeDescription ?>" fetchpriority="<?= $args['fetch-priority'] ?? 'normal' ?>"
         src="<?= $desktopImageUrl ?>" style="<?= esc_attr($style) ?>" loading="<?= $lazyLoad ? 'lazy': 'eager' ?>"/>
</figure>
