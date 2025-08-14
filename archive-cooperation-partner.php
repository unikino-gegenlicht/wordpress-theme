<?php

defined( 'ABSPATH' ) || exit;
get_header();
do_action( 'wp_body_open' );
?>

    <article class="page-content">
        <div class="content">
            <h1 class="is-size-3 is-uppercase"><?= esc_html__( 'You want to show something with us?', 'gegenlicht' ) ?></h1>
			<?php
			$raw        = get_theme_mod( 'coop_page_text' )[ get_locale() ] ?? "Some content is missng";
			$paragraphs = preg_split( "/\R\R/", $raw, flags: PREG_SPLIT_NO_EMPTY );
			foreach ( $paragraphs as $paragraph ) :
				?>
                <p>
					<?= $paragraph ?>
                </p>
			<?php
			endforeach;
			?>
        </div>
        <article class="rules" id="rules">
			<?php
			$rulesRaw = get_theme_mod( 'coop_rules' )[ get_locale() ] ?? "";
			$rules    = preg_split( "/\R\R/", $rulesRaw, flags: PREG_SPLIT_NO_EMPTY );
			?>
            <ul>
				<?php for ( $i = 0; $i < count( $rules ); $i ++ ) : ?>
                    <li class="px-2"><?= $i + 1 ?>.&ensp;<?= esc_html( $rules[ $i ] ) ?></li>
				<?php endfor; ?>
            </ul>
        </article>
        <div class="content">
			<?php
			$raw        = get_theme_mod( 'coop_rules_closer' )[ get_locale() ] ?? "Some content is missng";
			$paragraphs = preg_split( "/\R\R/", $raw, flags: PREG_SPLIT_NO_EMPTY );
			foreach ( $paragraphs as $paragraph ) :
				?>
                <p>
					<?= $paragraph ?>
                </p>
			<?php
			endforeach;
			?>
        </div>
    </article>
    <div class="has-background-white py-4 px-2 mt-5 reservation-button">
        <a class="button is-fullwidth is-uppercase is-size-5 has-background-white has-text-black" target="_blank" href="<?= get_theme_mod('cooperation_contact_page') ?>">
            <span class="has-text-weight-bold"><?= esc_html__( 'Contact Us Now', 'gegenlicht' ) ?></span>
            <span class="material-symbols ml-1">open_in_new</span>
        </a>
    </div>
    <main class="content page-content mt-5">
        <h3 class="is-size-3"><?= esc_html__( 'Our Cooperations', 'gegenlicht' ) ?></h3>
		<?php
		$raw        = get_theme_mod( 'coop_partner_list_text' )[ get_locale() ] ?? "Some content is missng";
		$paragraphs = preg_split( "/\R\R/", $raw, flags: PREG_SPLIT_NO_EMPTY );
		foreach ( $paragraphs as $paragraph ) :
			?>
            <p>
				<?= $paragraph ?>
            </p>
		<?php endforeach; ?>

        <div class="fixed-grid has-2-cols-mobile has-4-cols-tablet">
        <div class="grid" style="grid-template-rows: auto">
        <?php while ( have_posts() ) : the_post(); ?>
            <div class="cell">
                <figure class="image is-3by4 member-picture" >
                    <img src="<?= get_the_post_thumbnail_url(size: 'full') ?>" style="object-fit: scale-down; border: var(--bulma-body-color) solid var(--border-thickness)"/>
                </figure>
                <hr class="separator"/>
                <p style="word-break: break-word" class="font-ggl is-uppercase is-size-5 no-separator"><?= the_title() ?></p>
            </div>
        <?php endwhile; ?>
        </div>
        </div>
    </main>

<?php get_footer(); ?>