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

define( 'HEXNITY_WP_CHILD_VERSION', '1.20.0' );

/**
 * Registers this theme's custom-logo support so the WordPress core
 * "Site Identity" logo (Customizer → Site Identity → Logo) works out
 * of the box. This matters because this theme is deployed to more
 * than one site: the Customizer logo is a per-site theme_mod already
 * stored in every site's own database the moment an editor uploads
 * one there — unlike a value hand-fed into a Site Content JSON row on
 * one specific install, wiring to this makes the logo appear
 * correctly on any site running this theme with zero admin/JSON step.
 * See hexnity_wp_child_get_site_logo() below, which reads it.
 *
 * @return void
 */
function hexnity_wp_child_setup() {
	add_theme_support(
		'custom-logo',
		array(
			'height'      => 80,
			'width'       => 240,
			'flex-height' => true,
			'flex-width'  => true,
		)
	);
}
add_action( 'after_setup_theme', 'hexnity_wp_child_setup' );

/**
 * Resolves the logo image a header/footer template-part should
 * display, in priority order:
 *
 * 1. An explicit `logo_url` set on that section's own Site Content
 *    JSON (Hexnity WP → Header & Footer) — a deliberate per-section
 *    override an editor typed in on purpose (e.g. a different image
 *    for the dark-background footer than the light-background header).
 * 2. The site's own Customizer "Site Identity" logo (custom_logo
 *    theme_mod) — works automatically on every site using this theme,
 *    no JSON edit required.
 * 3. $fallback_url — this theme's own bundled placeholder SVG.
 *
 * @param array  $section_content Decoded Site Content JSON for the section (header or footer).
 * @param string $fallback_url    URL to use if neither of the above is set.
 * @return array{url: string, alt: string}
 */
function hexnity_wp_child_get_site_logo( $section_content, $fallback_url ) {
	if ( ! empty( $section_content['logo_url'] ) ) {
		return array(
			'url' => $section_content['logo_url'],
			'alt' => $section_content['logo_alt'] ?? '',
		);
	}

	$logo_id = get_theme_mod( 'custom_logo' );
	if ( $logo_id ) {
		$logo_url = wp_get_attachment_image_url( $logo_id, 'full' );
		if ( $logo_url ) {
			return array(
				'url' => $logo_url,
				'alt' => get_post_meta( $logo_id, '_wp_attachment_image_alt', true ),
			);
		}
	}

	return array(
		'url' => $fallback_url,
		'alt' => '',
	);
}

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

/**
 * Per-template default Page Content JSON payloads, added 2026-09-01
 * per explicit user report: assigning a Page to template-broadband.php
 * (or any other template here) did not generate any JSON for it — the
 * previous process only ever backfilled a page's JSON via a manual,
 * one-off temp script run by a session, per page, by hand (see
 * action-map.json's 2026-09-01 "More" page entries). This makes that
 * automatic and general, for every template in this theme.
 *
 * Every array below is a straight transcription of that template's own
 * hardcoded `??` fallback values (the exact same copy already rendered
 * today for a page with no saved JSON) — this does not change what any
 * page looks like; it just makes that same default copy visible and
 * editable in the "Page Content (JSON)" meta box immediately, instead
 * of only being visible by reading the template's PHP source. Keep
 * this in sync any time a template's own `??` fallback copy changes —
 * see TEMPLATE-CONVERSION-GUIDE.md rule 9.
 *
 * Verified safe to round-trip through hex_save_page_content()/
 * hex_get_page_content() first, via a throwaway draft test page
 * (created and deleted in the same temp script, never committed):
 * inline HTML with a `class` attribute (e.g.
 * `<span class="accent-text">...</span>`, used in several of these
 * templates' `wp_kses_post()`-rendered heading fields) survives the
 * save/read round trip unstripped.
 *
 * @param string $template_slug The value of get_page_template_slug().
 * @return array|null The default payload, or null if this template
 *                     isn't registered here (nothing will be saved).
 */
function hexnity_wp_child_get_template_defaults( $template_slug ) {
	switch ( $template_slug ) {

		case 'template-broadband.php':
			return array(
				'brand' => array(
					'logo_url' => get_stylesheet_directory_uri() . '/assets/images/brand-more-logo.svg',
					'logo_alt' => 'More',
					'blurb'    => 'More is our broadband and mobile comparison and switching service — one call to see what\'s available at your address and get connected, without the hassle.',
				),
				'steps' => array(
					'eyebrow' => 'How it works',
					'heading' => 'Switching And Moving Is As Simple As 1... 2... 3',
					'items'   => array(
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
					),
				),
				'about' => array(
					'eyebrow' => 'About More',
					'heading' => 'The simpler way to compare and switch broadband',
					'lede'    => 'More compares plans across trusted broadband and mobile providers so you don\'t have to — balancing price, data and network coverage to find the plan that actually fits how you use your connection.',
				),
				'usage' => array(
					'eyebrow' => 'Why switch with More',
					'heading' => 'One call, no lock-in confusion, no chasing paperwork',
					'lede'    => 'A real consultant handles the comparison, the paperwork and the handover with your new provider, so switching takes one phone call rather than an afternoon.',
				),
				'contact' => array(
					'eyebrow'     => 'Get in touch',
					'heading'     => 'Check what\'s available at your address',
					'text'        => 'Tell us a little about your needs and we\'ll come back with the plans available in your area — no complicated questions, no obligation.',
					'cf7_form_id' => 41,
				),
				'site' => array(
					'phone' => '1300 110 829',
					'email' => 'info@mindlabz.com.au',
				),
				'faq' => array(
					'heading' => 'Frequently asked questions',
					'items'   => array(
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
					),
				),
			);

		case 'template-services.php':
			return array(
				'hero' => array(
					'breadcrumb_label' => 'Services',
					'heading'          => 'Four services. One accountable <span class="accent-text">partner</span>.',
					'lede'             => 'Retailers come to us for volume they can defend in an audit. Households come to us because switching should take four minutes, not four hours.',
					'cta_label'        => 'Book a consultation',
					'cta_url'          => home_url( '/contact/' ),
				),
				'rows' => array(
					array(
						'num'   => '01',
						'title' => 'AI & IT Solutions',
						'desc'  => 'Lead scoring, conversation automation, live performance dashboards and sales workflow optimisation, built for regulated Australian retail.',
						'tags'  => array( 'Intent scoring', 'Voice & chat AI', 'Partner dashboards', 'CRM integration' ),
						'url'   => home_url( '/ai-technology/' ),
					),
					array(
						'num'   => '02',
						'title' => 'Energy Broking',
						'desc'  => 'Comparing electricity and gas plans across trusted retailers — balancing price, contract terms and sustainability for households and businesses.',
						'tags'  => array( 'Residential', 'Small business', 'Solar-aware plans' ),
						'url'   => '#',
					),
					array(
						'num'   => '03',
						'title' => 'Broadband & Mobile',
						'desc'  => 'End-to-end telecom plan comparison across pricing, data allowance, network coverage and independent quality benchmarks.',
						'tags'  => array( 'NBN', 'Mobile', 'Bundles' ),
						'url'   => '#',
					),
					array(
						'num'   => '04',
						'title' => 'Private Health Cover',
						'desc'  => 'Policy and provider comparison focused on coverage depth, waiting periods, premium value and business risk protection.',
						'tags'  => array( 'Hospital', 'Extras', 'Corporate cover' ),
						'url'   => '#',
					),
				),
			);

		case 'template-our-brands.php':
			return array(
				'hero' => array(
					'breadcrumb_label' => 'Our Brands',
					'heading'          => 'We don\'t just sell the service — <span class="accent-text">we run it.</span>',
					'lede'             => 'Our comparison brands are where the technology is proven: real households, real switches, real compliance load. That\'s what partners are actually buying.',
					'cta_label'        => 'Partner with us',
					'cta_url'          => home_url( '/contact/' ),
				),
				'cards' => array(
					array(
						'tag'        => 'Consumer · Energy & broadband',
						'title'      => 'Compare Your Bills',
						'desc'       => 'Households compare electricity, gas, broadband and mobile plans and switch over the phone with a real consultant — free to the customer, end to end.',
						'link_label' => 'Visit Compare Your Bills',
						'url'        => '#',
						'domain'     => 'compareyourbills.com.au',
						'rating'     => '★★★★★',
					),
					array(
						'tag'        => 'Consumer · Bill review',
						'title'      => 'Check Your Bill',
						'desc'       => 'A second opinion on what you\'re paying. Send through a bill and get a like-for-like breakdown against what\'s actually available in your postcode today.',
						'link_label' => 'Visit Check Your Bill',
						'url'        => '#',
						'domain'     => 'checkyourbill.com.au',
						'rating'     => '★★★★★',
					),
				),
				'quote' => array(
					'text'        => 'Compare Your Bills\' staff were very professional and helped me find the best package that met my needs — the whole transfer of electricity and gas was easy and simplified.',
					'attribution' => 'Rizwan Saeed · Compare Your Bills customer',
				),
			);

		case 'template-results.php':
			return array(
				'hero' => array(
					'breadcrumb_label' => 'Results',
					'heading'          => 'What partnership looks like in <span class="accent-text">practice</span>.',
					'lede'             => 'Every figure below is pulled from the same dashboards our partners see themselves — updated in real time, not a monthly deck.',
					'cta_label'        => 'Book a partner consultation',
					'cta_url'          => home_url( '/contact/' ),
				),
				'stats' => array(
					array(
						'figure' => '<em>+124%</em>',
						'label'  => 'Sales growth delivered for partner brands',
					),
					array(
						'figure' => '18',
						'label'  => 'Energy, telco and insurance brands supported',
					),
					array(
						'figure' => '100%',
						'label'  => 'Of sales calls recorded and QA sampled',
					),
					array(
						'figure' => '4 min',
						'label'  => 'Average time for a household to switch',
					),
				),
			);

		case 'template-contact.php':
			return array(
				'hero' => array(
					'breadcrumb_label' => 'Contact',
					'heading'          => 'Let\'s talk about your <span class="accent-text">next move</span>.',
					'lede'             => 'Tell us what you\'re trying to grow. We\'ll come back within one business day with a view on whether we\'re the right partner — and what it would take.',
				),
				'contact' => array(
					'eyebrow'     => 'Get in touch',
					'heading'     => 'Book a consultation',
					'text'        => 'Tell us what you\'re trying to grow. We\'ll come back within one business day with a view on whether we\'re the right partner — and what it would take.',
					'cf7_form_id' => 41,
				),
				'site' => array(
					'phone'   => '1300 110 829',
					'email'   => 'info@mindlabz.com.au',
					'address' => '10.1, 3 Bowen Crescent, Melbourne VIC 3004',
				),
			);

		case 'template-ai-technology.php':
			return array(
				'hero' => array(
					'breadcrumb_label' => 'AI & Technology',
					'heading'          => 'A sales floor that runs on <span class="accent-text">evidence</span>, not instinct.',
					'lede'             => 'Every enquiry is scored, routed, worked and recorded. Partners see the same numbers we do, in the same hour — not in a monthly spreadsheet.',
					'cta_label'        => 'Request a walkthrough',
					'cta_url'          => home_url( '/contact/' ),
				),
				'features' => array(
					array(
						'index' => 'A',
						'title' => 'Intent scoring',
						'desc'  => 'Models rank every enquiry on likelihood to convert before an agent picks up the phone.',
					),
					array(
						'index' => 'B',
						'title' => 'Smart routing',
						'desc'  => 'Leads land with the team, script and offer most likely to close them — automatically.',
					),
					array(
						'index' => 'C',
						'title' => 'Conversation automation',
						'desc'  => 'AI handles qualification, callbacks and follow-up so people spend their time on live intent.',
					),
					array(
						'index' => 'D',
						'title' => 'Live partner dashboards',
						'desc'  => 'Volume, conversion, cost-per-sale and QA outcomes, shared with partners in real time.',
					),
					array(
						'index' => 'E',
						'title' => 'Compliance by default',
						'desc'  => 'Consent capture, call recording and audit trails aligned to ISO/IEC 27001:2022.',
					),
				),
				'compliance' => array(
					'heading' => 'ISO/IEC 27001:2022 certified since 2022',
					'text'    => 'Certified information security management, independently accredited. Every consumer interaction is consented, recorded and auditable — the standard energy and telco retailers are contractually required to hold their partners to.',
					'badges'  => array( 'ISO/IEC 27001:2022', 'IAS / IAF accredited', 'Consent-based data only' ),
				),
			);

		case 'template-home.php':
			return array(
				'hero' => array(
					'eyebrow'              => '<b>Melbourne</b> · Energy · Telecom · Insurance · Finance',
					'heading'              => 'Growth for the brands that <span class="accent-text">power</span>, <span class="accent-text">connect</span> and <span class="accent-text">cover</span> Australia.',
					'lede'                 => 'Mindlabz combines AI-built sales technology with trained, compliant human teams — and proves it every day through our own consumer brands, Compare Your Bills and Check Your Bill.',
					'cta_primary_label'   => 'Partner with us',
					'cta_primary_url'     => '#contact',
					'cta_secondary_label' => 'Explore our services',
					'cta_secondary_url'   => '#services',
					'card' => array(
						'label'   => 'Partner sales growth',
						'figure'  => '<em>+124%</em>',
						'caption' => 'Average uplift delivered across supported retail brands.',
						'stats'   => array(
							array(
								'value' => '18',
								'label' => 'Brands supported',
							),
							array(
								'value' => '27001',
								'label' => 'ISO/IEC certified',
							),
						),
					),
				),
				'logo_strip' => array(
					'label'     => 'Trusted across energy, telecom & insurance',
					'shortcode' => '[hex_partner_logos]',
				),
				'services' => array(
					'eyebrow' => 'What we do',
					'heading' => 'Four services. One accountable partner.',
					'lede'    => 'Retailers come to us for volume they can defend in an audit. Households come to us because switching should take four minutes, not four hours.',
					'rows'    => array(
						array(
							'num'   => '01',
							'title' => 'AI & IT Solutions',
							'desc'  => 'Lead scoring, conversation automation, live performance dashboards and sales workflow optimisation, built for regulated Australian retail.',
							'tags'  => array( 'Intent scoring', 'Voice & chat AI', 'Partner dashboards', 'CRM integration' ),
							'url'   => '#platform',
						),
						array(
							'num'   => '02',
							'title' => 'Energy Broking',
							'desc'  => 'Comparing electricity and gas plans across trusted retailers — balancing price, contract terms and sustainability for households and businesses.',
							'tags'  => array( 'Residential', 'Small business', 'Solar-aware plans' ),
							'url'   => '#',
						),
						array(
							'num'   => '03',
							'title' => 'Broadband & Mobile',
							'desc'  => 'End-to-end telecom plan comparison across pricing, data allowance, network coverage and independent quality benchmarks.',
							'tags'  => array( 'NBN', 'Mobile', 'Bundles' ),
							'url'   => '#',
						),
						array(
							'num'   => '04',
							'title' => 'Private Health Cover',
							'desc'  => 'Policy and provider comparison focused on coverage depth, waiting periods, premium value and business risk protection.',
							'tags'  => array( 'Hospital', 'Extras', 'Corporate cover' ),
							'url'   => '#',
						),
					),
				),
				'platform' => array(
					'eyebrow'   => '<b>AI & Technology</b>',
					'heading'   => 'A sales floor that runs on evidence, not instinct.',
					'lede'      => 'Every enquiry is scored, routed, worked and recorded. Partners see the same numbers we do, in the same hour — not in a monthly spreadsheet.',
					'cta_label' => 'Request a walkthrough',
					'cta_url'   => '#contact',
					'features'  => array(
						array(
							'index' => 'A',
							'title' => 'Intent scoring',
							'desc'  => 'Models rank every enquiry on likelihood to convert before an agent picks up the phone.',
						),
						array(
							'index' => 'B',
							'title' => 'Smart routing',
							'desc'  => 'Leads land with the team, script and offer most likely to close them — automatically.',
						),
						array(
							'index' => 'C',
							'title' => 'Conversation automation',
							'desc'  => 'AI handles qualification, callbacks and follow-up so people spend their time on live intent.',
						),
						array(
							'index' => 'D',
							'title' => 'Live partner dashboards',
							'desc'  => 'Volume, conversion, cost-per-sale and QA outcomes, shared with partners in real time.',
						),
						array(
							'index' => 'E',
							'title' => 'Compliance by default',
							'desc'  => 'Consent capture, call recording and audit trails aligned to ISO/IEC 27001:2022.',
						),
					),
				),
				'results' => array(
					'eyebrow' => 'By the numbers',
					'heading' => 'What partnership looks like in practice.',
					'stats'   => array(
						array(
							'figure' => '<em>+124%</em>',
							'label'  => 'Sales growth delivered for partner brands',
						),
						array(
							'figure' => '18',
							'label'  => 'Energy, telco and insurance brands supported',
						),
						array(
							'figure' => '100%',
							'label'  => 'Of sales calls recorded and QA sampled',
						),
						array(
							'figure' => '4 min',
							'label'  => 'Average time for a household to switch',
						),
					),
				),
				'brands' => array(
					'eyebrow' => 'Owned consumer brands',
					'heading' => 'We don\'t just sell the service — <span class="accent-text">we run it.</span>',
					'lede'    => 'Our comparison brands are where the technology is proven: real households, real switches, real compliance load. That\'s what partners are actually buying.',
					'cards'   => array(
						array(
							'tag'        => 'Consumer · Energy & broadband',
							'title'      => 'Compare Your Bills',
							'desc'       => 'Households compare electricity, gas, broadband and mobile plans and switch over the phone with a real consultant — free to the customer, end to end.',
							'link_label' => 'Visit Compare Your Bills',
							'url'        => '#',
							'domain'     => 'compareyourbills.com.au',
						),
						array(
							'tag'        => 'Consumer · Bill review',
							'title'      => 'Check Your Bill',
							'desc'       => 'A second opinion on what you\'re paying. Send through a bill and get a like-for-like breakdown against what\'s actually available in your postcode today.',
							'link_label' => 'Visit Check Your Bill',
							'url'        => '#',
							'domain'     => 'checkyourbill.com.au',
						),
					),
				),
				'quote' => array(
					'text'        => 'Compare Your Bills\' staff were very professional and helped me find the best package that met my needs — the whole transfer of electricity and gas was easy and simplified.',
					'attribution' => 'Rizwan Saeed · Compare Your Bills customer',
				),
				'compliance' => array(
					'heading'    => 'ISO/IEC 27001:2022 certified since 2022',
					'text'       => 'Certified information security management, independently accredited. Every consumer interaction is consented, recorded and auditable — the standard energy and telco retailers are contractually required to hold their partners to.',
					'image_url'  => get_stylesheet_directory_uri() . '/assets/images/compliance-badges-placeholder.svg',
					'image_alt'  => 'ISO/IEC 27001:2022 · IAS/IAF accredited · Consent-based data only',
				),
				'paths' => array(
					array(
						'kicker'    => 'For retailers & brands',
						'heading'   => 'Grow your book, defensibly.',
						'text'      => 'You need connected customers, compliant channels and a partner who reports honestly. Let\'s talk volume, unit economics and integration.',
						'cta_label' => 'Book a partner consultation',
						'cta_url'   => '#contact',
						'cta_style' => 'primary',
					),
					array(
						'kicker'    => 'For households & business',
						'heading'   => 'Find out if you\'re overpaying.',
						'text'      => 'Power, gas, broadband, mobile or health cover — a like-for-like comparison takes about four minutes and costs you nothing.',
						'cta_label' => 'Compare my bills',
						'cta_url'   => '#',
						'cta_style' => 'ghost',
					),
				),
				'contact' => array(
					'eyebrow'      => 'Get in touch',
					'heading'      => 'Book a consultation',
					'text'         => 'Tell us what you\'re trying to grow. We\'ll come back within one business day with a view on whether we\'re the right partner — and what it would take.',
					'form_options' => array( 'A retailer or brand partner', 'A household comparing bills', 'A business comparing bills', 'A supplier or vendor' ),
					'submit_label' => 'Send enquiry',
					'fine_print'   => 'We\'ll only use these details to respond to your enquiry. See our Privacy Policy.',
				),
				'site' => array(
					'phone'   => '1300 110 829',
					'email'   => 'info@mindlabz.com.au',
					'address' => '10.1, 3 Bowen Crescent, Melbourne VIC 3004',
				),
			);

		default:
			return null;
	}
}

/**
 * Auto-generates a page's default Page Content JSON the moment it's
 * assigned one of this child theme's own templates, if that page
 * doesn't already have any saved content — per explicit user report
 * (2026-09-01): "when I creating new page and assign broadband
 * template to that page, it should generate the json. then if i
 * create new page and assign the same template it should create new
 * json for that page. now it doesnt create any json." Each page gets
 * its OWN independent row, keyed by its own page ID (hex_get_page_content()/
 * hex_save_page_content() are always page-ID-scoped) — assigning the
 * same template to a second page never shares or overwrites the
 * first page's JSON.
 *
 * Fires on save_post_page (both the classic Page Attributes template
 * dropdown and the block editor's sidebar template picker end up
 * setting `_wp_page_template` via wp_insert_post()/wp_update_post()
 * before save_post fires, for either editor) at priority 20 so the
 * template meta is guaranteed already saved by the time this runs.
 * Idempotent and safe to fire on every save: bails out immediately if
 * the page already has ANY saved content, so it never overwrites an
 * editor's own edits — it only ever fills a genuinely empty row once.
 *
 * @param int      $post_id The page's ID.
 * @param \WP_Post $post    The page object.
 * @return void
 */
function hexnity_wp_child_maybe_backfill_page_content( $post_id, $post ) {
	if ( wp_is_post_revision( $post_id ) || wp_is_post_autosave( $post_id ) ) {
		return;
	}

	if ( ! isset( $post->post_type ) || 'page' !== $post->post_type || 'trash' === $post->post_status ) {
		return;
	}

	if ( ! function_exists( 'hex_get_page_content' ) || ! function_exists( 'hex_save_page_content' ) ) {
		return;
	}

	$template = get_page_template_slug( $post_id );
	$defaults = hexnity_wp_child_get_template_defaults( $template );

	if ( null === $defaults ) {
		return;
	}

	$existing = hex_get_page_content( $post_id );

	if ( ! empty( $existing ) ) {
		return;
	}

	hex_save_page_content( $post_id, $defaults );
}
add_action( 'save_post_page', 'hexnity_wp_child_maybe_backfill_page_content', 20, 2 );

/**
 * Applies this theme's known template-home.php Page Content field
 * migrations to one page's ALREADY-SAVED JSON, without touching
 * anything else an editor has customized. Added 2026-09-02 per
 * explicit user instruction after a Home page redesign (logo strip
 * names → a `[hex_partner_logos]` shortcode slot, compliance text
 * badges → a single image, brand-card star ratings removed): the user
 * had already hand-edited that page's live Page Content JSON and was
 * explicit that a resync must NOT blow away those edits — "it should
 * update what I asked, because in the live site I changed the json
 * data. so it should not be replaced" — so this only ever touches the
 * three specific sub-keys this migration concerns, via hex_get_page_content()
 * as the base (never a full array_replace over the whole payload,
 * which would risk duplicating the numerically-keyed `cards` array or
 * clobbering unrelated fields).
 *
 * Deliberately narrow and hand-written per migration, not a generic
 * "reset to defaults" — a blind merge of hexnity_wp_child_get_template_defaults()
 * over live content would silently overwrite any other copy an editor
 * has already customized (headings, ledes, CTAs, etc.), which is
 * exactly what this feature exists to avoid.
 *
 * @param int $post_id The page's ID.
 * @return bool Whether hex_save_page_content() reported success.
 */
function hexnity_wp_child_sync_home_page_content_migrations( $post_id ) {
	if ( ! function_exists( 'hex_get_page_content' ) || ! function_exists( 'hex_save_page_content' ) ) {
		return false;
	}

	$content = hex_get_page_content( $post_id );

	if ( empty( $content['logo_strip']['shortcode'] ) ) {
		$content['logo_strip']['shortcode'] = '[hex_partner_logos]';
	}
	unset( $content['logo_strip']['names'] );

	if ( empty( $content['compliance']['image_url'] ) ) {
		$content['compliance']['image_url'] = get_stylesheet_directory_uri() . '/assets/images/compliance-badges-placeholder.svg';
	}
	if ( empty( $content['compliance']['image_alt'] ) ) {
		$content['compliance']['image_alt'] = 'ISO/IEC 27001:2022 · IAS/IAF accredited · Consent-based data only';
	}
	unset( $content['compliance']['badges'] );

	if ( ! empty( $content['brands']['cards'] ) && is_array( $content['brands']['cards'] ) ) {
		foreach ( $content['brands']['cards'] as &$card ) {
			unset( $card['rating'] );
		}
		unset( $card );
	}

	return (bool) hex_save_page_content( $post_id, $content );
}

/**
 * Renders a small "Sync template updates" meta box on the Edit Page
 * screen, only for a page currently assigned template-home.php — a
 * one-click button that runs hexnity_wp_child_sync_home_page_content_migrations()
 * so the 2026-09-02 field changes above reach a page's already-saved
 * JSON (the automatic hexnity_wp_child_maybe_backfill_page_content()
 * hook only ever fills a genuinely EMPTY row, by design — it never
 * touches a page that already has content, which this page does).
 */
function hexnity_wp_child_register_sync_meta_box() {
	add_meta_box(
		'hexnity_wp_child_sync_home_defaults',
		'Sync Template Updates',
		'hexnity_wp_child_render_sync_meta_box',
		'page',
		'side',
		'default'
	);
}
add_action( 'add_meta_boxes_page', 'hexnity_wp_child_register_sync_meta_box' );

function hexnity_wp_child_render_sync_meta_box( $post ) {
	if ( 'template-home.php' !== get_page_template_slug( $post->ID ) ) {
		echo '<p style="color:#666;">No pending template updates for this page.</p>';
		return;
	}

	if ( isset( $_GET['hexnity_synced'] ) && '1' === $_GET['hexnity_synced'] ) {
		echo '<p style="color:#1a7f37;">Synced.</p>';
	}

	wp_nonce_field( 'hexnity_wp_child_sync_home_defaults_' . $post->ID, 'hexnity_wp_child_sync_nonce' );
	?>
	<p style="margin-top:0;">Pulls in the latest Home template field changes (logo strip shortcode, compliance image, brand card ratings) without touching any other content you've already customized on this page.</p>
	<button type="submit" name="hexnity_wp_child_sync_home_defaults" value="1" class="button button-secondary">Sync now</button>
	<?php
}

/**
 * Handles the "Sync now" button above: runs on the normal Page save
 * request (same nonce, same form submit) rather than a separate
 * admin-post.php action, so it can't run without also going through
 * WordPress's own Page save/permission flow.
 */
function hexnity_wp_child_maybe_handle_sync_button( $post_id ) {
	if ( empty( $_POST['hexnity_wp_child_sync_home_defaults'] ) ) {
		return;
	}

	if ( wp_is_post_revision( $post_id ) || wp_is_post_autosave( $post_id ) ) {
		return;
	}

	if ( ! isset( $_POST['hexnity_wp_child_sync_nonce'] ) ||
		! wp_verify_nonce( wp_unslash( $_POST['hexnity_wp_child_sync_nonce'] ), 'hexnity_wp_child_sync_home_defaults_' . $post_id ) ) {
		return;
	}

	if ( ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}

	hexnity_wp_child_sync_home_page_content_migrations( $post_id );
}
add_action( 'save_post_page', 'hexnity_wp_child_maybe_handle_sync_button', 20 );

/**
 * Backfills default Header/Footer Site Content JSON the first time
 * this theme's code runs in wp-admin on a site where those rows don't
 * exist yet -- e.g. right after a fresh install, or right after this
 * theme is updated (via the GitHub updater) on a site whose "Header &
 * Footer" admin page has never been saved. Added 2026-09-01 per
 * explicit user need: a theme code update only ships files, never
 * database content -- this theme is deployed to more than one site,
 * and a brand-new/never-configured site would otherwise show a blank
 * Header & Footer JSON until someone manually typed values in. This
 * makes the same non-empty defaults this theme originally shipped
 * with (matching site-header.php's/site-footer.php's own hardcoded
 * `??` fallback copy) appear automatically -- the same idempotent
 * "fill only if truly empty, never overwrite" pattern
 * hexnity_wp_child_maybe_backfill_page_content() above already uses
 * for per-page content.
 *
 * Deliberately does NOT set logo_url/logo_alt here: leaving those
 * unset is what lets hexnity_wp_child_get_site_logo() fall through to
 * the site's own Customizer "Site Identity" logo automatically (see
 * that function, above). Only set logo_url explicitly, via the admin
 * page, when a section genuinely needs an image different from the
 * site's single Customizer logo -- an image file is inherently a
 * per-site editorial choice this function cannot invent on its own.
 *
 * Also deliberately leaves the footer's `columns`/`bottom_links` keys
 * out of the backfilled payload: site-footer.php already has its own
 * full structural `?? array(...)` fallback for those, so an absent
 * key there already renders the original 3-column design -- writing
 * them here too would just duplicate that default in two places.
 *
 * @return void
 */
function hexnity_wp_child_maybe_backfill_site_content() {
	if ( ! function_exists( 'hex_get_site_content' ) || ! function_exists( 'hex_save_site_content' ) ) {
		return;
	}

	if ( empty( hex_get_site_content( 'header' ) ) ) {
		hex_save_site_content(
			'header',
			array(
				'brand_name'      => 'Mindlabz',
				'phone'           => '1300 110 829',
				'cta_label'       => 'Book a consultation',
				'cta_label_short' => 'Enquire',
				'cta_url'         => '#contact',
				'skip_link_text'  => 'Skip to content',
			)
		);
	}

	if ( empty( hex_get_site_content( 'footer' ) ) ) {
		hex_save_site_content(
			'footer',
			array(
				'brand_name' => 'Mindlabz',
				'blurb'      => 'Sales technology and managed acquisition for Australian energy, telecom, insurance and finance brands.',
				'phone'      => '1300 110 829',
				'email'      => 'info@mindlabz.com.au',
				'abn'        => 'ABN 00 000 000 000',
				'address'    => '10.1, 3 Bowen Crescent, Melbourne VIC 3004',
			)
		);
	}
}
add_action( 'admin_init', 'hexnity_wp_child_maybe_backfill_site_content' );
