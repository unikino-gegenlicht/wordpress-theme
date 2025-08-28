<?php

/**
 * Button Partial
 *
 * The button partial renders a `<a>` element annotated to be a button with the
 * desired content in it.
 * The following arguments are required to make this partial work:
 *   - `href` (string):
 *
 *      The target of the button
 *
 *      Applied Filters: `esc_attr`
 *
 *   - `label` (string):
 *
 *       The text of the button
 *
 *       Applied Filters: `esc_html`
 *
 * Additionally, you can specify values for the following arguments to change
 * the default behaviour:
 *   - `aria-role` (`string`):
 *
 *      Change the accessibility role for the element.
 *
 *      Defaults to: `link`
 *
 *      Applied Filters: `esc_attr`
 *
 *
 *   - `additional-classes` (`array`):
 *
 *      Add additional CSS classes to the outermost `<a>` element.
 *
 *      Defaults to: `[]`
 *
 *      Applied Filters: `esc_attr`
 *
 *
 *   - `external` (`bool`):
 *
 *       Indicate that the buttons target is external.
 *
 *       Defaults to: `[]`
 *
 *       Applied Filters: `esc_attr`
 */

$href              = (string) ( $args['href'] ?? "" );
$content           = (string) ( $args['content'] ?? "" );
$role              = (string) ( $args['aria-role'] ?? "link" );
$additionalClasses = (array) ( $args['additional-classes'] ?? [] );
$external          =  ( $args['external'] ?? null );
$icon              = (string)  ( $args['icon'] ?? null );

$websiteHost = parse_url(get_home_url())['host'] ?? "";
$targetHost = parse_url($href)['host'] ?? "";

if ($external === null && $websiteHost !== $targetHost) {
    $external = true;
}
?>

<a href="<?= $href ?>"
   role="<?= $role ?>"
   class="button is-outlined is-fullwidth mt-2 is-size-5 has-text-weight-bold <?= join( ' ', $additionalClasses ) ?>)"
   style="padding: 0.75rem 0 !important;"
   rel="<?= $external ? '_blank' : '' ?>">
	<?php if ( $external ) : ?>
        <span class="icon-text is-align-items-center">
            <span class="is-uppercase"><?= esc_html( $content ) ?></span>
            <span class="icon">
                <span class="material-symbols">open_in_new</span>
            </span>
        </span>
    <?php elseif ( $icon ) : ?>; ?>
    <span class="icon-text is-align-items-center">
            <span class="is-uppercase"><?= esc_html( $content ) ?></span>
            <span class="icon">
                <span class="material-symbols"><?= $icon ?></span>
            </span>
        </span>
	<?php else: ?>
    <span class="is-uppercase"><?= esc_html( $content ) ?></span>
	<?php endif ?>
</a>
