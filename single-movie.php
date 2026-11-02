<?php
/*
 * Copyright (c) 2026. Unikino GEGENLICHT. Licensed under the EUPL v1.2 or later
 */

defined( 'ABSPATH' ) || exit;
get_header();
?>
    <main class="content content-container mt-4">
        <?php if ( has_post_thumbnail() ): ?>
            <?php the_post_thumbnail(); ?>
        <?php else: ?>
            <h1><?= str_starts_with( get_user_locale(), "de" ) ? rwmb_meta( 'german_title' ) : rwmb_meta( 'english_title' ) ?></h1>
        <?php endif; ?>
        <hr>
        <div class="is-flex is-justify-content-space-between is-flex-wrap-wrap is-row-gap-3">
            <div class="is-flex-grow-1">
                <h2><?= __( 'Directors', 'cinema-press-theme' ) ?></h2>
                <div class="tags are-medium">
                    <?php foreach ( rwmb_meta( 'directors' ) as $director ): ?>
                        <a class="tag is-rounded" href="<?= get_term_link( $director, 'director' ) ?>">
                            <?= $director->name ?>
                        </a>
                    <?php endforeach; ?>
                </div>
                <h2><?= __( 'Actors', 'cinema-press-theme' ) ?></h2>
                <div class="tags are-medium">
                    <?php foreach ( rwmb_meta( 'actors' ) as $actor ): ?>
                        <a class="tag is-rounded" href="<?= get_term_link( $actor, 'director' ) ?>">
                            <?= $actor->name ?>
                        </a>
                    <?php endforeach; ?>
                </div>
            </div>
            <div class="has-text-left-mobile has-text-right is-flex is-flex-direction-column is-gap-1">
                <h2><?= __( 'Technical Details', 'cinema-press-theme' ) ?></h2>
                <div class="mb-4">
                    <h3 class="is-size-5 mb-2"><?= esc_html__( "Released in", "cinema-press-theme" ) ?></h3>
                    <p>
                        <?= rwmb_meta( 'release_year' ) ?>
                    </p>
                </div>
                <div class="mb-4">
                    <h3 class="is-size-5 mb-2"><?= esc_html__( "Aspect Ratio", "cinema-press-theme" ) ?></h3>
                    <p>
                        <?= rwmb_meta( 'aspect_ratio' ) ?>
                    </p>
                </div>
                <div class="mb-4">
                    <h3 class="is-size-5 mb-2">
                        <?= esc_html__( "Running Time", "cinema-press-theme" ) ?>
                    </h3>
                    <p>
                        <?= sprintf( esc_html__( '%s Minutes' ), rwmb_meta( 'running_time' ) ) ?>
                    </p>
                </div>
                <div class="mb-4">
                    <h3 class="is-size-5 mb-2">
                        <?= esc_html__( "Country of Origin", "cinema-press-theme" ) ?>
                    </h3>
                    <p>
                        <?php
                        $countries = rwmb_meta( 'country_of_origin' );
                        sort( $countries );
                        foreach ( $countries as $country ):
                            echo iso_codes()->getCountries()->getByNumericCode( $country )->getLocalName() . '<br>';
                        endforeach;
                        ?>
                    </p>
                </div>
            </div>
        </div>
        <div class="buttons">
            <?php if ( ! empty( rwmb_meta( 'tmdb_id' ) ) ): ?>
                <a href="https://letterboxd.com/tmdb/<?= rwmb_meta( 'tmdb_id' ) ?>" target="_blank"
                   class="button is-outlined">
                <span class="icon is-medium">
                    <i class="fa-brands fa-letterboxd"></i>
                </span>
                    <span><?= esc_html__( 'See reviews on Letterboxd', 'cinema-press-theme' ) ?></span>
                </a>
                <a href="https://tmdb.org/movie/<?= rwmb_meta( 'tmdb_id' ) ?>" target="_blank"
                   class="button is-outlined">
                    <span><?= esc_html__( 'More details on TMDB', 'cinema-press-theme' ) ?></span>
                </a>
            <?php endif; ?>
            <?php if ( ! empty( rwmb_meta( 'imdb_id' ) ) ): ?>
                <a href="https://imdb.com/<?= rwmb_meta( 'imdb_id' ) ?>" target="_blank"
                   class="button is-outlined">
                <span class="icon is-medium">
                    <i class="fa-brands fa-imdb"></i>
                </span>
                    <span><?= esc_html__( 'More details on IMDB', 'cinema-press-theme' ) ?></span>
                </a>
            <?php endif; ?>
        </div>


    </main>

<?php get_footer(); ?>