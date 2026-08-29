<?php
/**
 * Child theme bootstrap for Hexnity WP Child.
 *
 * The design-system Tailwind build (assets/css/tailwind.css, compiled
 * from assets/css/src/site-theme.css) lives and loads from the parent
 * theme only (hex-wp-theme-template) — its own inc/enqueue.php always
 * enqueues 'hex-tailwind' via get_template_directory_uri(), which
 * resolves to the parent theme regardless of which child theme is
 * active. This child theme briefly had its own duplicate Tailwind
 * build (own package.json, own compiled tailwind.css, own enqueue) —
 * reversed per explicit user correction: "it should be in main theme
 * and should load via main theme." This child theme keeps its own
 * copy of assets/css/src/site-theme.css purely as an editable
 * reference/override source for a child-theme developer — it is not
 * built or enqueued from here. This child theme's own runtime
 * token-value file (theme-options.css) is unaffected: it's generated
 * and enqueued by the parent theme's inc/style-settings.php
 * regardless of which theme folder it physically sits in.
 *
 * @package HexnityWPChild
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'HEXNITY_WP_CHILD_VERSION', '1.2.1' );

/**
 * Re-enqueue this child theme's style.css to load after the parent's
 * Tailwind build, so a one-off override rule placed directly in
 * style.css can actually win the cascade against a compiled Tailwind
 * class of equal specificity.
 *
 * The parent's own inc/enqueue.php separately enqueues 'hex-style' via
 * get_stylesheet_uri() (resolves to THIS theme's style.css when this
 * child theme is active) as the WP-theme-review-required "theme's own
 * stylesheet" handle — that copy loads before 'hex-tailwind', too
 * early for an override to take effect, so this queues a second,
 * later copy under its own handle. This does mean style.css is
 * fetched via two <link> tags (harmless — same URL, browser-cached
 * after the first). Deregistering 'hex-style' instead was tried and
 * reverted: 'hex-animate'/'hex-tailwind' both declare it as a
 * dependency, and removing it from the registry broke that whole
 * dependency chain (all three stylesheets silently stopped printing)
 * — confirmed live. Do not deregister 'hex-style' here.
 *
 * @return void
 */
function hexnity_wp_child_enqueue_assets() {
	wp_enqueue_style(
		'hexnity-wp-child-style',
		get_stylesheet_uri(),
		array( 'hex-tailwind' ),
		HEXNITY_WP_CHILD_VERSION
	);
}
add_action( 'wp_enqueue_scripts', 'hexnity_wp_child_enqueue_assets', 20 );
