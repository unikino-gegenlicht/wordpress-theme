<?php
/*
 * Copyright (c) 2026. Unikino GEGENLICHT. Licensed under the EUPL v1.2 or later
 */

defined( 'ABSPATH' ) || exit;
get_header();
?>
    <main class="content-container content mt-4">
        <header>
            <?php the_post_thumbnail() ?>
            <hr>
            <h1 class="mt-0"><?php the_title() ?></h1>
        </header>
        <?= apply_filters('the_content', get_the_content() ) ?>
    </main>
<?php get_footer(); ?>