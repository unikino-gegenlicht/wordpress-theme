<?php
/*
 * Copyright (c) 2026. Unikino GEGENLICHT. Licensed under the EUPL v1.2 or later
 */

?>
<hr class="mt-6 mb-0">
<footer class="footer has-background-primary">
    <div class="content content-container">
        <p class="is-size-2 mb-0 font-readex"><?php bloginfo( 'name' ); ?></p>
        <p class="font-readex"><?php bloginfo( 'description' ) ?></p>
        <hr>
        <div class="is-flex">
            <?php
            $navigation_items = wp_get_nav_menu_items( wp_get_nav_menu_name( 'footer' ) ) ?: [];
            for ( $i = 0; $i < count( $navigation_items ); $i ++ ) {
                $item         = $navigation_items[ $i ];
                $isActivePage = $item->url === ( home_url( $wp->request ) . '/' ) || $item->url === ( home_url( $wp->request ) );
                ?>
                <a class="pr-1" href="<?= $item->url ?>"><span class="icon-text"><span
                                class="font-readex"><?= $item->title ?></span><span class="icon"><i
                                    class="fa-utility fa-semibold fa-arrow-up-right-from-square"></i></span></span></a>
                <?php
                if ( $i < count( $navigation_items ) - 1 ) {
                    ?>
                        <p class="pr-1 m-0">|</p>
                    <?php
                }
            }
            ?>
        </div>
        <hr>
        <?php dynamic_sidebar('footer') ?>
    </div>
</footer>
<?php do_action('wp_footer') ?>
</body>
</html>
