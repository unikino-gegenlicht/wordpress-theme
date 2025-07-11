<?php
?>
<figure class="image is-hidden-tablet is-4by5 movie-image mt-4">
    <img fetchpriority="<?= $args['fetch-priority'] ?? 'normal' ?>'"
         src="<?= $args['image_url'] ?: wp_get_attachment_image_url( get_theme_mod( 'anonymous_image' ), 'large' ) ?>"/>
</figure>
<figure class="image is-hidden-mobile is-16by9 movie-image mt-4">
    <img fetchpriority="<?= $args['fetch-priority'] ?? 'normal' ?>'"
         src="<?= $args['image_url'] ?? wp_get_attachment_image_url( get_theme_mod( 'anonymous_image' ), 'large' ) ?>"/>
</figure>
