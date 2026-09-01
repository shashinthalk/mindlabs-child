<?php
/**
 * Child theme bootstrap for Hexnity WP Child.
 *
 * The design-system Tailwind BUILD (assets/css/tailwind.css, compiled
 * from a Tailwind source file) still lives and loads from the parent
 * theme only (hex-wp-theme-template) — its own inc/enqueue.php always
 * enqueues 'hex-tailwind' via get_template_directory_uri(), which
 * resolves to the parent theme regardless of which child theme is
 * active. This child theme briefly had its own duplicate Tailwind
 * BUILD PIPELINE (own package.json, own npm run build:css, own
 * compiled output) — reversed per explicit user correction: "it
 * should be in main theme and should load via main theme." That
 * remains true: this theme has no npm, no package.json, no build
 * step of its own.
 *
 * This child theme DOES directly enqueue its own
 * assets/css/src/site-theme.css (unconditionally, on every page as of
 * 2026-08-30 — see hexnity_wp_child_enqueue_meridian_assets() below;
 * originally scoped to just the Meridian Home template, broadened once
 * header.php/footer.php made its classes global site chrome) — as of
 * 2026-08-29, that file was rewritten to be plain, hand-written,
 * browser-native CSS (no Tailwind `@theme` at-rule, no processing
 * required), per explicit user instruction: "only use site-theme.css
 * and theme options.css dont create any other css." See that file's
 * own header comment for the full architecture.
 *
 * This child theme's own runtime token-value file (theme-options.css)
 * is generated and enqueued by the parent theme's
 * inc/style-settings.php regardless of which theme folder it
 * physically sits in.
 *
 * @package HexnityWPChild
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'HEXNITY_WP_CHILD_VERSION', '1.16.3' );

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

/**
 * Google Fonts for the site's own --hex-font-heading ('Instrument
 * Serif') and --hex-font-body ('Inter') Theme Options values (set
 * 2026-08-29, mapped from the "Concept B: Meridian" design concept —
 * see theme-options.css). These are real Theme Options schema fields
 * (the admin regenerated theme-options.css around them, replacing an
 * earlier --hex-heading-font-family/--hex-body-font-family pair this
 * session had mistakenly treated as canonical) applied to every bare
 * h1-h6/body tag site-wide, so this loads unconditionally on every
 * page — otherwise every page but the Meridian Home one would
 * silently fall back to the stack's next font (Georgia / system-ui)
 * since the browser would have nothing named "Instrument
 * Serif"/"Inter" to render with. Instrument Serif's italic variant is
 * also what --hex-font-accent (same family) uses via the .font-accent
 * utility class in assets/css/src/site-theme.css.
 *
 * @return void
 */
function hexnity_wp_child_enqueue_brand_fonts() {
	wp_enqueue_style(
		'hexnity-wp-child-brand-fonts',
		'https://fonts.googleapis.com/css2?family=Instrument+Serif:ital@0;1&family=Inter:wght@400;500;600;700&display=swap',
		array(),
		null
	);
}
add_action( 'wp_enqueue_scripts', 'hexnity_wp_child_enqueue_brand_fonts', 19 );

/**
 * This child theme's own site-theme.css component classes and the
 * 'IBM Plex Mono' Google Font for the --hex-font-mono Theme Options
 * field. Originally conditional on is_page_template( array(
 * 'template-home.php', 'template-ai-technology.php' ) ) — broadened to
 * load on every page 2026-08-30, when header.php/footer.php +
 * template-parts/site-header.php/site-footer.php were added: those now
 * render the "Meridian" brand header/footer (.site-nav/.footer-grid/
 * .font-mono/etc.) as GLOBAL chrome via get_header()/get_footer(), not
 * just on the two page templates that used to be the only consumers.
 * See that file's header comment for why it's directly enqueued at all
 * (converted to plain CSS, 2026-08-29, per explicit user instruction to
 * use only site-theme.css and theme-options.css, no other CSS file).
 * Depends on 'hex-tailwind' so the --hex-* tokens this file's own
 * rules read are already registered by load time.
 *
 * @return void
 */
function hexnity_wp_child_enqueue_meridian_assets() {
	wp_enqueue_style(
		'hexnity-wp-child-meridian-mono-font',
		'https://fonts.googleapis.com/css2?family=IBM+Plex+Mono:wght@450&display=swap',
		array(),
		null
	);

	wp_enqueue_style(
		'hexnity-wp-child-site-theme',
		get_stylesheet_directory_uri() . '/assets/css/src/site-theme.css',
		array( 'hex-tailwind' ),
		HEXNITY_WP_CHILD_VERSION
	);
}
add_action( 'wp_enqueue_scripts', 'hexnity_wp_child_enqueue_meridian_assets', 21 );

/**
 * Site-wide scroll-reveal animation. Hand-written, plain JS — no
 * build step, same "no other tooling" approach as site-theme.css
 * (assets/js/site-animate.js). Targets bare structural selectors
 * that already exist in every template (section/.hero/.card/etc.),
 * not per-template markup, so it applies sitewide without any
 * template file needing an edit — see that file's own header comment
 * and the ".hex-reveal"/".hex-anim-ready" rules in site-theme.css.
 * Loaded in the footer (true) since it only needs the DOM, not
 * render-blocking placement.
 *
 * @return void
 */
function hexnity_wp_child_enqueue_animation() {
	wp_enqueue_script(
		'hexnity-wp-child-site-animate',
		get_stylesheet_directory_uri() . '/assets/js/site-animate.js',
		array(),
		HEXNITY_WP_CHILD_VERSION,
		true
	);
}
add_action( 'wp_enqueue_scripts', 'hexnity_wp_child_enqueue_animation' );

/**
 * Contact Form 7 ("Contact form", ID 41 — the plugin's own default form,
 * rebuilt 2026-08-30 to match template-contact.php's fields/copy exactly)
 * wraps its raw form-tag template through WordPress's wpautop() by
 * default, which inserts unrequested <p>/<br> tags around any field
 * whose label and form-tag sit on separate lines inside a plain <div> —
 * breaking the exact markup template-contact.php's form needs to match
 * its existing .form-label/.form-control/.form-row-split layout
 * (site-theme.css's .form-split/.form-row-split rules assume that exact
 * shape). Disabling autop sitewide is safe: this theme has only the one
 * CF7 form, and every visual gap it already relies on comes from
 * site-theme.css's own `gap`/`padding` rules, not from wpautop's
 * inserted whitespace.
 *
 * @return bool
 */
function hexnity_wp_child_cf7_disable_autop() {
	return false;
}
add_filter( 'wpcf7_autop_or_not', 'hexnity_wp_child_cf7_disable_autop' );
