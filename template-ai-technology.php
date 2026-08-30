<?php
/**
 * Template Name: AI & Technology
 *
 * Converted from template.html (theme root) — a draft template dropped
 * in with generic/mockup class names (.page-hero/.wrap/.band/.feat/.n/
 * .comp/.badges/.btn-accent/.serif-it) that don't exist in this child
 * theme's real design system. Per TEMPLATE-CONVERSION-GUIDE.md, every
 * one of those was mapped onto real classes instead of recreated:
 * .wrap -> .hex-container, .band -> .dark-band, .feat -> .feature-list
 * (same shape as template-home.php's "platform" section — 26px/1fr
 * grid children with a .feature-list-index), .comp -> .compliance-box
 * + .card + .badge-row (same shape as template-home.php's "compliance"
 * section), .badges span.badge -> .badge.badge-mono, .btn-accent ->
 * .btn-primary (this theme has no "accent" button variant — the
 * concept's only other CTA using this same label/copy, on the Home
 * page's dark band, already uses .btn-primary, so this stays
 * consistent rather than inventing a new variant), .serif-it -> the
 * existing .accent-text utility (italic + accent color — same
 * treatment already used for the Home hero's accent words). No new
 * .page-hero/.breadcrumb classes existed yet either; both were
 * authored in site-theme.css's shared @layer components block with
 * generic, reusable names per the guide's step 6 (not
 * page-prefixed), since a plain title/breadcrumb band is a pattern
 * any future non-landing-page template can reuse.
 *
 * Not standalone (unlike template-home.php) — this is an ordinary
 * content page, so it uses the parent theme's own get_header()/
 * get_footer() rather than rendering its own nav/footer markup.
 *
 * Text content: per GUIDELINES.md §8 / TEMPLATE-CONVERSION-GUIDE.md
 * step 8, every piece of editor-facing copy reads from
 * hex_get_page_content() with a `??` fallback equal to template.html's
 * exact original copy. This page's "features" and "compliance" fields
 * happen to carry the same copy as the Home page's "platform"/
 * "compliance" fields (this page reads as a dedicated detail page for
 * that same section) — they are still stored as this page's own,
 * separate JSON object (hex_get_page_content() is per-page), not
 * shared with the Home page's row.
 *
 * @package HexnityWPChild
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

$content = function_exists( 'hex_get_page_content' ) ? hex_get_page_content() : array();

$features = $content['features'] ?? array(
	array( 'index' => 'A', 'title' => 'Intent scoring', 'desc' => 'Models rank every enquiry on likelihood to convert before an agent picks up the phone.' ),
	array( 'index' => 'B', 'title' => 'Smart routing', 'desc' => 'Leads land with the team, script and offer most likely to close them — automatically.' ),
	array( 'index' => 'C', 'title' => 'Conversation automation', 'desc' => 'AI handles qualification, callbacks and follow-up so people spend their time on live intent.' ),
	array( 'index' => 'D', 'title' => 'Live partner dashboards', 'desc' => 'Volume, conversion, cost-per-sale and QA outcomes, shared with partners in real time.' ),
	array( 'index' => 'E', 'title' => 'Compliance by default', 'desc' => 'Consent capture, call recording and audit trails aligned to ISO/IEC 27001:2022.' ),
);

$compliance_badges = $content['compliance']['badges'] ?? array(
	'ISO/IEC 27001:2022', 'IAS / IAF accredited', 'Consent-based data only',
);
?>

<div class="page-hero">
	<div class="hex-container">
		<span class="breadcrumb hex-small"><a href="<?php echo esc_url( home_url( '/' ) ); ?>">Home</a> / <?php echo esc_html( $content['hero']['breadcrumb_label'] ?? 'AI & Technology' ); ?></span>
		<h1 class="hex-h1"><?php echo wp_kses_post( $content['hero']['heading'] ?? 'A sales floor that runs on <span class="accent-text">evidence</span>, not instinct.' ); ?></h1>
		<p class="lede hex-lead"><?php echo esc_html( $content['hero']['lede'] ?? 'Every enquiry is scored, routed, worked and recorded. Partners see the same numbers we do, in the same hour — not in a monthly spreadsheet.' ); ?></p>
		<div class="hero-cta">
			<a class="btn btn-primary btn-lg" href="<?php echo esc_url( $content['hero']['cta_url'] ?? home_url( '/contact/' ) ); ?>"><?php echo esc_html( $content['hero']['cta_label'] ?? 'Request a walkthrough' ); ?></a>
		</div>
	</div>
</div>

<div class="dark-band">
	<section>
		<div class="hex-container">
			<div class="feature-list hex-small">
				<?php foreach ( $features as $feature ) : ?>
				<div><span class="feature-list-index hex-h5 font-mono"><?php echo esc_html( $feature['index'] ?? '' ); ?></span><div><h4 class="hex-h4"><?php echo esc_html( $feature['title'] ?? '' ); ?></h4><p><?php echo esc_html( $feature['desc'] ?? '' ); ?></p></div></div>
				<?php endforeach; ?>
			</div>
		</div>
	</section>
</div>

<section>
	<div class="hex-container">
		<div class="compliance-box card hex-small">
			<div>
				<h4 class="hex-h4"><?php echo esc_html( $content['compliance']['heading'] ?? 'ISO/IEC 27001:2022 certified since 2022' ); ?></h4>
				<p><?php echo esc_html( $content['compliance']['text'] ?? 'Certified information security management, independently accredited. Every consumer interaction is consented, recorded and auditable — the standard energy and telco retailers are contractually required to hold their partners to.' ); ?></p>
			</div>
			<div class="badge-row">
				<?php foreach ( $compliance_badges as $badge ) : ?>
				<span class="badge badge-mono hex-h5 font-mono"><?php echo esc_html( $badge ); ?></span>
				<?php endforeach; ?>
			</div>
		</div>
	</div>
</section>

<?php get_footer(); ?>
