<?php
/**
 * Template Name: Broadband
 *
 * A brand-led landing page for the broadband/mobile switching service
 * (brand placeholder: "More" — TEMPLATE-CONVERSION-GUIDE.md's ??
 * fallback rule applies the same way here as everywhere else: swap the
 * real brand name/logo in via this page's own Page Content JSON once
 * decided, no code change needed).
 *
 * Section order: a centered brand-intro (logo image + short blurb, new
 * `.brand-intro` component — see site-theme.css), a 3-step "Compare /
 * Switch / Save" process as a one-row `.step-cards` grid of `.card`s
 * (new component, added 2026-09-01 in place of the originally-used
 * `.rows`/`.row-item` stacked-list component, per explicit user
 * request to show the 3 steps as cards in one row), two `.sec-head`-
 * only copy sections (about the brand, then why
 * to switch with it — the same heading+lede pattern template-home.php's
 * Services/Results/Brands sections already use), a Contact Form 7
 * enquiry form reusing the exact `.form-split` card pattern
 * template-contact.php established (GUIDELINES.md §10), and an FAQ
 * section using the parent's existing CSS-only `.accordion` component
 * (site-theme.css lines ~735-782) — not used by any other template in
 * this theme yet, but already shipped and ready to reuse per rule 5.
 *
 * Per rule 10, calls get_header()/get_footer() rather than rendering
 * its own header/footer.
 *
 * Text content: per rule 8/`GUIDELINES.md` §8, every piece of
 * editor-facing copy reads from hex_get_page_content() with a `??`
 * fallback equal to this template's own default copy below, so a new
 * Page assigned this template renders sensibly before any JSON is
 * saved. Keys: `brand` (logo_url/logo_alt/blurb), `steps`
 * (eyebrow/heading/items[num,title,desc]), `about`
 * (eyebrow/heading/lede), `usage` (eyebrow/heading/lede), `contact`
 * (eyebrow/heading/text/cf7_form_id), `site` (phone/email/address —
 * same independent read as template-contact.php's own Contact section,
 * not the Site Content header/footer store), `faq`
 * (heading/items[question,answer]).
 *
 * Step 9 (backfilling a page's default JSON into the
 * `hex_page_content` table): as of 2026-09-01 this happens
 * automatically the moment a Page is assigned this template (see
 * hexnity_wp_child_maybe_backfill_page_content() in functions.php) —
 * no manual step needed, and no longer a one-time thing tied to
 * Page ID 46 specifically; any new Page assigned this template gets
 * its own independent JSON the same way.
 *
 * @package HexnityWPChild
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

$content = function_exists( 'hex_get_page_content' ) ? hex_get_page_content() : array();

$steps = $content['steps']['items'] ?? array(
	array(
		'num'   => '01',
		'title' => 'Compare',
		'desc'  => 'Enter your address! Tell us a bit about your needs. Our process is simple with NO complicated questions.',
	),
	array(
		'num'   => '02',
		'title' => 'Switch',
		'desc'  => 'We\'ll tell you about the plans available in your area and recommend one which is most suited to your needs.',
	),
	array(
		'num'   => '03',
		'title' => 'Save',
		'desc'  => 'Confirm your details and get connected. The process is easy and there will be no interruption at all.',
	),
);

/*
 * Hand-drawn, hardcoded per position (not part of the steps.items
 * JSON) — same pattern as the fixed phone/email/address icons used
 * on the contact info rows elsewhere in this theme: the icon is tied
 * to the step's fixed real-world meaning (Compare/Switch/Save), not
 * editable per-page copy, so it stays out of hex_get_page_content().
 * Plain inline SVG, stroke="currentColor", matching every other icon
 * in this theme — no icon font/library.
 */
$step_icons = array(
	'<svg class="icon icon-lg" viewBox="0 0 24 24" fill="none" stroke="currentColor"><circle cx="10.5" cy="10.5" r="6.5" stroke-width="1.6"/><path d="M20 20l-5-5" stroke-width="1.6" stroke-linecap="round"/></svg>',
	'<svg class="icon icon-lg" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="M4 8h13M17 8l-3.5-3.5M17 8l-3.5 3.5" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/><path d="M20 16H7M7 16l3.5-3.5M7 16l3.5 3.5" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/></svg>',
	'<svg class="icon icon-lg" viewBox="0 0 24 24" fill="none" stroke="currentColor"><circle cx="12" cy="12" r="9" stroke-width="1.6"/><path d="M8 12.5l2.5 2.5L16 9.5" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/></svg>',
);

$faq_items = $content['faq']['items'] ?? array(
	array(
		'question' => 'How long does switching take?',
		'answer'   => 'Most switches are confirmed in one call. Your new connection is usually active within a few business days, depending on your address and current provider.',
	),
	array(
		'question' => 'Will there be any interruption to my service?',
		'answer'   => 'No — we time the switch so your existing connection stays live until the new one is ready, with no gap in service.',
	),
	array(
		'question' => 'Is there a cost to use this service?',
		'answer'   => 'No, comparing and switching through us is free. We\'re paid by the provider you choose, not by you.',
	),
	array(
		'question' => 'What information do I need to provide?',
		'answer'   => 'Just your address and a few details about how you use your connection — no account numbers or paperwork needed to get a recommendation.',
	),
	array(
		'question' => 'Can I switch if I\'m still in a contract?',
		'answer'   => 'Often, yes. Tell us your current provider and we\'ll let you know what your options are, including any exit fees to weigh up.',
	),
);
?>

<main id="primary">

	<!-- BRAND INTRO -->
	<div class="brand-intro">
		<div class="hex-container">
			<img class="brand-intro-logo" src="<?php echo esc_url( $content['brand']['logo_url'] ?? get_stylesheet_directory_uri() . '/assets/images/brand-more-logo.svg' ); ?>" alt="<?php echo esc_attr( $content['brand']['logo_alt'] ?? 'More' ); ?>">
			<p class="hex-small"><?php echo esc_html( $content['brand']['blurb'] ?? 'More is our broadband and mobile comparison and switching service — one call to see what\'s available at your address and get connected, without the hassle.' ); ?></p>
		</div>
	</div>

	<!-- STEPS -->
	<section id="how-it-works">
		<div class="hex-container">
			<div class="sec-head">
				<span class="eyebrow hex-h5 font-mono"><?php echo esc_html( $content['steps']['eyebrow'] ?? 'How it works' ); ?></span>
				<h2 class="hex-h2"><?php echo esc_html( $content['steps']['heading'] ?? 'Switching And Moving Is As Simple As 1... 2... 3' ); ?></h2>
			</div>
			<div class="step-cards">
				<?php foreach ( $steps as $index => $step ) : ?>
				<div class="card step-card">
					<span class="step-card-icon"><?php echo $step_icons[ $index ] ?? ''; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- hardcoded SVG constant above, not user input. ?></span>
					<span class="step-card-num hex-h5 font-mono"><?php echo esc_html( $step['num'] ?? '' ); ?></span>
					<h3 class="hex-h3"><?php echo esc_html( $step['title'] ?? '' ); ?></h3>
					<p class="hex-small"><?php echo esc_html( $step['desc'] ?? '' ); ?></p>
				</div>
				<?php endforeach; ?>
			</div>
		</div>
	</section>

	<!-- ABOUT THE BRAND -->
	<section id="about" class="section-tight-top">
		<div class="hex-container">
			<div class="sec-head">
				<span class="eyebrow hex-h5 font-mono"><?php echo esc_html( $content['about']['eyebrow'] ?? 'About More' ); ?></span>
				<h2 class="hex-h2"><?php echo esc_html( $content['about']['heading'] ?? 'The simpler way to compare and switch broadband' ); ?></h2>
				<p class="lede hex-lead"><?php echo esc_html( $content['about']['lede'] ?? 'More compares plans across trusted broadband and mobile providers so you don\'t have to — balancing price, data and network coverage to find the plan that actually fits how you use your connection.' ); ?></p>
			</div>
		</div>
	</section>

	<!-- USING THE BRAND -->
	<section id="why-switch" class="section-tight-top">
		<div class="hex-container">
			<div class="sec-head">
				<span class="eyebrow hex-h5 font-mono"><?php echo esc_html( $content['usage']['eyebrow'] ?? 'Why switch with More' ); ?></span>
				<h2 class="hex-h2"><?php echo esc_html( $content['usage']['heading'] ?? 'One call, no lock-in confusion, no chasing paperwork' ); ?></h2>
				<p class="lede hex-lead"><?php echo esc_html( $content['usage']['lede'] ?? 'A real consultant handles the comparison, the paperwork and the handover with your new provider, so switching takes one phone call rather than an afternoon.' ); ?></p>
			</div>
		</div>
	</section>

	<!-- CONTACT FORM -->
	<section id="contact">
		<div class="hex-container">
			<div class="form-split card">
				<div class="form-split-side hex-small">
					<div>
						<span class="eyebrow hex-h5 font-mono"><?php echo esc_html( $content['contact']['eyebrow'] ?? 'Get in touch' ); ?></span>
						<h2 class="hex-h2"><?php echo esc_html( $content['contact']['heading'] ?? 'Check what\'s available at your address' ); ?></h2>
						<p><?php echo esc_html( $content['contact']['text'] ?? 'Tell us a little about your needs and we\'ll come back with the plans available in your area — no complicated questions, no obligation.' ); ?></p>
					</div>
					<div class="contact-list">
						<div><svg class="icon icon-sm" viewBox="0 0 20 20" fill="none" stroke="currentColor"><path d="M3 5c0 8 4 12 12 12l2-3-4-2-2 2c-2-1-4-3-5-5l2-2-2-4L3 5z" stroke-linejoin="round"/></svg><?php echo esc_html( $content['site']['phone'] ?? '1300 110 829' ); ?></div>
						<div><svg class="icon icon-sm" viewBox="0 0 20 20" fill="none" stroke="currentColor"><rect x="2.5" y="4.5" width="15" height="11" rx="2"/><path d="M3 6l7 5 7-5" stroke-linecap="round"/></svg><?php echo esc_html( $content['site']['email'] ?? 'info@mindlabz.com.au' ); ?></div>
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

	<!-- FAQ -->
	<section id="faq" class="section-tight-top">
		<div class="hex-container">
			<div class="sec-head">
				<h2 class="hex-h2"><?php echo esc_html( $content['faq']['heading'] ?? 'Frequently asked questions' ); ?></h2>
			</div>
			<div class="accordion">
				<?php foreach ( $faq_items as $item ) : ?>
				<details class="accordion-item">
					<summary class="hex-body"><?php echo esc_html( $item['question'] ?? '' ); ?></summary>
					<div class="accordion-content hex-small"><?php echo esc_html( $item['answer'] ?? '' ); ?></div>
				</details>
				<?php endforeach; ?>
			</div>
		</div>
	</section>

</main>

<?php get_footer(); ?>
