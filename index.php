<?php
/*
 * Copyright (c) 2026. Unikino GEGENLICHT. Licensed under the EUPL v1.2 or later
 */
defined( 'ABSPATH' ) || exit;

get_header();
?>
<main class="content-container content mt-3">
    <header>
        <h1><?php the_title() ?></h1>
        <?php if ( has_post_thumbnail() ): ?>
            <?php the_post_thumbnail(); ?>
        <?php endif; ?>
    </header>
    <?= apply_filters( 'the_content', get_the_content() ) ?>
</main>
<?php get_footer(); ?>
