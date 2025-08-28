<?php
defined( 'ABSPATH' ) || exit;

get_header();
?>
<main class="page-content mt-4">
    <article class="content my-6">
        <header>
            <figure class="image supporter-logo">
                <img height="150" src="<?= get_the_post_thumbnail_url( size: 'full' ) ?>" decoding="async" loading="lazy"/>
            </figure>
            <h2 class="mt-2"><?php the_title() ?></h2>
        </header>
		<?php the_content();


		get_template_part( 'partials/button', args: [
			'href'     => rwmb_the_value( 'supporter_website', echo: false ),
			'content'  => __( 'To the Website', 'gegenlicht' ),
			'external' => true
		] );
		?>
    </article>
</main>
<?php
get_footer();
?>
