<?php

defined( 'ABSPATH' ) || exit;


get_header('home');
do_action('wp_body_open');

?>
<div id="page">
    <section id="semesterprogram">
        <div class="columns m-0">
            <div class="column is-1 is-hidden-mobile">
                <h2 class="text-rotated is-smallcaps font-ggl has-text-black is-size-1"><?= __('Unser Semesterprogramm') ?></h2>
            </div>
            <div class="column is-1 is-hidden-tablet">
                <h2 class="font-ggl is-smallcaps has-text-black is-size-3"><?= __('Unser Semesterprogramm') ?></h2>
            </div>
            <div class="column">
                <div class="fixed-grid has-1-cols-mobile has-2-cols-tablet has-3-cols-desktop has-3-cols-widescreen has-4-cols-fullhd">
                    <div class="grid">
                        <?php for ($i = 1; $i <= 10; $i++): 
                            get_template_part('partials/tile', $i % 3 == 0 ? 'event': 'movie', array('counter' => $i));
                        endfor; ?>
                    </div>
                </div>


            </div>
        </div>
    </section>

    <?php //todo: check displayed special programs from theme_mod and display in <section> ?>
    <section id="special-program-x" class="full-width-section"
        style="background-image: url('<?= get_stylesheet_directory_uri() ?>/assets/img/special-program.svg'), linear-gradient(to bottom,  #356aca,  #356aca); background-size: cover; background-position: center;">
        <div class="columns">
            <div class="column is-1 is-hidden-mobile">
                <h2 class="text-rotated is-smallcaps font-ggl has-text-white is-size-1"> <?= __('Special Program X') ?></h2>
            </div>
            <div class="column is-1 is-hidden-tablet">
                <h2 class="is-smallcaps font-ggl has-text-white is-size-3"> <?= __('Special Program X') ?></h2>
            </div>
            <div class="column">
                <p class="has-text-white has-text-weight-semibold is-size-4 pb-4">
                    <?= __('Dies ist die Beschreibung des Sonderprogramms') ?>
                </p>
                <div class="fixed-grid has-1-cols-mobile has-2-cols-tablet has-3-cols-desktop has-3-cols-widescreen has-4-cols-fullhd">
                    <div class="grid">
                        <?php for ($i = 1; $i <= 10; $i++): 
                            get_template_part('partials/tile', 'movie', array('counter' => $i));
                        endfor; ?>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="who-we-are" class="full-width-section" style="background-color: white;">
        <div class="columns p-0"
            style="width: var(--page-width); margin: auto; background-color: var(--bulma-primary);">
            <div class="column is-8">
                <h2 class="font-ggl is-size-3 has-text-black is-uppercase">
                    <?= __('Who is the GEGENLICHT') //todo: replace with theme_mod ?>
                </h2>
            </div>
        </div>
    </section>


</div>
<?php //get_footer(); ?>