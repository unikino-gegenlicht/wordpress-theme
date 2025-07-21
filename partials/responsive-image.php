<?php
$imageUrl = $args['image_url'] ?? wp_get_attachment_image_url( get_theme_mod( 'anonymous_image' ), 'large' ) ?: 'https://picsum.photos/1600/900/';
$alternativeDescription = attachment_url_to_postid( $imageUrl ) != 0 ? get_post_meta(attachment_url_to_postid( $imageUrl ), '_wp_attachment_image_alt', true ) : '';
?>
<figure class="image is-hidden-tablet is-4by5 movie-image mt-4">
    <img alt="<?= $alternativeDescription ?>" fetchpriority="<?= $args['fetch-priority'] ?? 'normal' ?>"
         src="<?= $imageUrl ?>"/>
</figure>
<figure class="image is-hidden-mobile <?= ($args['disable16by9'] ?? false) ? '' : 'is-16by9' ?> movie-image mt-4">
    <img alt="<?= $alternativeDescription ?>" fetchpriority="<?= $args['fetch-priority'] ?? 'normal' ?>"
         src="<?= $imageUrl ?>"/>
</figure>
