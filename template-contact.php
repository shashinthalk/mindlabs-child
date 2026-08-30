<?php
/**
 * Template Name: Contact
 *
 * Gives the Home page's "Contact" section (`#contact`, the primary
 * nav's "Contact" link and the global header/footer's own CTA button —
 * GUIDELINES.md §9) its own dedicated page/URL, same pattern as
 * template-services.php: a `.page-hero`/`.breadcrumb` title band, then
 * the section's own content reusing template-home.php's exact
 * `.form-split` markup and copy. Unlike the other 3 new templates, this
 * hero has no CTA button of its own — the form is right below it, a
 * second "click here to scroll to the form" button would be redundant.
 *
 * Per rule 10, calls get_header()/get_footer() rather than rendering
 * its own header/footer.
 *
 * Text content: per rule 8/`GUIDELINES.md` §8, every piece of
 * editor-facing copy reads from hex_get_page_content() with a `??`
 * fallback equal to template-home.php's original "Contact" section copy
 * exactly (`site.phone`/`site.email`/`site.address` included, read
 * independently the same way template-home.php's own Contact section
 * does — not from the Site Content header/footer framework, which is a
 * separate store per TEMPLATE-CONVERSION-GUIDE.md step 10). This page
 * has its own separate JSON row, keyed by its own page ID.
 *
 * The form itself: as of 2026-08-30 this is a real Contact Form 7 form
 * (`[contact-form-7]`, plugin's own default form, ID 41 — rebuilt with
 * a custom form-tag template) rather than the static, non-functional
 * `<form>` markup template-home.php still has. Every field carries the
 * theme's own `class:form-control`/`class:hex-small` (and the submit
 * button `class:btn`/`class:btn-primary`/`class:btn-lg`/`class:btn-submit`)
 * directly on the form-tag, per rule 5 (reuse real classes — never a
 * recreated lookalike), so it renders visually identical to the static
 * version it replaces; no new CSS was needed. `wpautop()` — which CF7
 * runs on its form template by default and which would otherwise
 * inject unrequested `<p>`/`<br>` tags into this exact markup shape —
 * is disabled sitewide via the `wpcf7_autop_or_not` filter in
 * `functions.php`. The CF7 form ID is read from this page's own
 * `hex_get_page_content()` (`contact.cf7_form_id`) with a `?? 41`
 * fallback, so an editor could point this page at a different CF7 form
 * without a code change. The dropdown's own options ("I'm enquiring
 * as...") now live in CF7's own form editor (wp-admin → Contact →
 * Contact Forms), not this page's Page Content JSON — `contact.
 * form_options` is no longer read here.
 *
 * @package HexnityWPChild
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

$content = function_exists( 'hex_get_page_content' ) ? hex_get_page_content() : array();
?>

<div class="page-hero">
	<div class="hex-container">
		<span class="breadcrumb hex-small"><a href="<?php echo esc_url( home_url( '/' ) ); ?>">Home</a> / <?php echo esc_html( $content['hero']['breadcrumb_label'] ?? 'Contact' ); ?></span>
		<h1 class="hex-h1"><?php echo wp_kses_post( $content['hero']['heading'] ?? 'Let\'s talk about your <span class="accent-text">next move</span>.' ); ?></h1>
		<p class="lede hex-lead"><?php echo esc_html( $content['hero']['lede'] ?? "Tell us what you're trying to grow. We'll come back within one business day with a view on whether we're the right partner — and what it would take." ); ?></p>
	</div>
</div>

<main id="primary">
	<section>
		<div class="hex-container">
			<div class="form-split card">
				<div class="form-split-side hex-small">
					<div>
						<span class="eyebrow hex-h5 font-mono"><?php echo esc_html( $content['contact']['eyebrow'] ?? 'Get in touch' ); ?></span>
						<h2 class="hex-h2"><?php echo esc_html( $content['contact']['heading'] ?? 'Book a consultation' ); ?></h2>
						<p><?php echo esc_html( $content['contact']['text'] ?? "Tell us what you're trying to grow. We'll come back within one business day with a view on whether we're the right partner — and what it would take." ); ?></p>
					</div>
					<div class="contact-list">
						<div><svg class="icon icon-sm" viewBox="0 0 20 20" fill="none" stroke="currentColor"><path d="M3 5c0 8 4 12 12 12l2-3-4-2-2 2c-2-1-4-3-5-5l2-2-2-4L3 5z" stroke-linejoin="round"/></svg><?php echo esc_html( $content['site']['phone'] ?? '1300 110 829' ); ?></div>
						<div><svg class="icon icon-sm" viewBox="0 0 20 20" fill="none" stroke="currentColor"><rect x="2.5" y="4.5" width="15" height="11" rx="2"/><path d="M3 6l7 5 7-5" stroke-linecap="round"/></svg><?php echo esc_html( $content['site']['email'] ?? 'info@mindlabz.com.au' ); ?></div>
						<div><svg class="icon icon-sm" viewBox="0 0 20 20" fill="none" stroke="currentColor"><path d="M10 18s6-5.2 6-9.5A6 6 0 004 8.5C4 12.8 10 18 10 18z" stroke-linejoin="round"/><circle cx="10" cy="8.5" r="2"/></svg><?php echo esc_html( $content['site']['address'] ?? '10.1, 3 Bowen Crescent, Melbourne VIC 3004' ); ?></div>
					</div>
				</div>
				<?php if ( class_exists( 'WPCF7_ContactForm' ) ) : ?>
				<?php
				$cf7_form_id = $content['contact']['cf7_form_id'] ?? 41;
				echo do_shortcode( '[contact-form-7 id="' . absint( $cf7_form_id ) . '" title="Contact form"]' );
				?>
				<?php else : ?>
				<p class="hex-small">Contact Form 7 isn't active — email us directly at <a href="<?php echo esc_url( 'mailto:' . ( $content['site']['email'] ?? 'info@mindlabz.com.au' ) ); ?>"><?php echo esc_html( $content['site']['email'] ?? 'info@mindlabz.com.au' ); ?></a>.</p>
				<?php endif; ?>
			</div>
		</div>
	</section>
</main>

<?php get_footer(); ?>
