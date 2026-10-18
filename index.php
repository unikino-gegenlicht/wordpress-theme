<?php
/*
 * Copyright (c) 2026. Unikino GEGENLICHT. Licensed under the EUPL v1.2 or later
 */

get_header();
?>
<main>
    <?= apply_filters( 'the_content', get_the_content() ) ?>
</main>
