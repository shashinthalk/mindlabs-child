<?php
/**
 * Template Name: Meridian Home
 *
 * (File renamed from template-meridian-home.php to template-home.php
 * by the user on 2026-08-29 — see functions.php's is_page_template()
 * check, kept in sync.)
 *
 * Page template for "Concept B: Meridian" (converted from
 * concept-b-meridian_1.html). As of 2026-08-30 this calls get_header()/
 * get_footer() like every other template in this theme, instead of
 * rendering its own inline header/footer markup — that markup was
 * split out into header.php/footer.php + template-parts/site-header.php
 * /site-footer.php (this child theme's own global "Meridian" chrome,
 * see GUIDELINES.md §9) once it became clear the same header/footer
 * needed to appear on every page, not just this one. The header/footer
 * now read their content from the Site Content JSON framework
 * (hex_get_site_content()) rather than this page's own Page Content
 * JSON — brand/phone/nav/footer-links are site-wide, not per-page.
 *
 * Every value from the design concept was mapped pixel-for-pixel onto
 * real Theme Options fields (theme-options.css) — colors, fonts,
 * sizes, radii — and every component uses a real class: the parent's
 * existing .btn/.card/.badge/.badge-outline/.badge-mono/.form-control/
 * .nav-menu/.nav-link/.icon, the theme's 11 real fluid text-size
 * classes (.hex-h1...hex-h6/.hex-lead/.hex-small/.hex-meta — never a
 * hardcoded font-size), the 2 font-family utilities (.font-accent/
 * .font-mono, driven by the 4 real --hex-font-heading/-body/-accent/
 * -mono Theme Options fields), plus new layout classes (.hero/
 * .hero-card/.rows/.row-item/.dark-band/.stats-grid/.brand-grid/
 * .quote-block/.compliance-box/.paths-split/.form-split/.footer-grid/
 * .logo-strip/.hex-container/.btn-ghost/etc.) — all authored directly
 * in assets/css/src/site-theme.css, this child theme's own directly-
 * enqueued CSS file. No other CSS file is used or needed.
 *
 * Text content: per GUIDELINES.md §8 (Page Content JSON framework),
 * every piece of editor-facing copy is read from
 * hex_get_page_content() (the parent theme's per-page JSON store,
 * edited via the "Page Content (JSON)" meta box on this page's Edit
 * Page screen) rather than hardcoded here. Every ?? fallback below is
 * the exact original design-concept copy, so a brand-new page using
 * this template (no JSON saved yet) still renders identically to the
 * concept until an editor customizes it. Decorative/structural markup
 * that isn't really "content" (SVG icons, wrapper divs, CSS classes)
 * stays hardcoded. Fields containing safe inline HTML (an accent
 * <span>, a bold lead-in word) are rendered with wp_kses_post();
 * everything else with esc_html()/esc_url() as appropriate — never
 * skip output escaping just because hex_get_page_content() already
 * sanitized on save.
 *
 * @package HexnityWPChild
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

$content = function_exists( 'hex_get_page_content' ) ? hex_get_page_content() : array();

$logo_strip_names = $content['logo_strip']['names'] ?? array(
	'Origin', 'AGL', 'EnergyAustralia', 'Momentum', 'Sumo',
	'OVO Energy', '1st Energy', 'Nectr', 'Aussie Broadband', 'Next Business Energy',
);

$service_rows = $content['services']['rows'] ?? array(
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
);

$platform_features = $content['platform']['features'] ?? array(
	array( 'index' => 'A', 'title' => 'Intent scoring', 'desc' => 'Models rank every enquiry on likelihood to convert before an agent picks up the phone.' ),
	array( 'index' => 'B', 'title' => 'Smart routing', 'desc' => 'Leads land with the team, script and offer most likely to close them — automatically.' ),
	array( 'index' => 'C', 'title' => 'Conversation automation', 'desc' => 'AI handles qualification, callbacks and follow-up so people spend their time on live intent.' ),
	array( 'index' => 'D', 'title' => 'Live partner dashboards', 'desc' => 'Volume, conversion, cost-per-sale and QA outcomes, shared with partners in real time.' ),
	array( 'index' => 'E', 'title' => 'Compliance by default', 'desc' => 'Consent capture, call recording and audit trails aligned to ISO/IEC 27001:2022.' ),
);

$result_stats = $content['results']['stats'] ?? array(
	array( 'figure' => '<em>+124%</em>', 'label' => 'Sales growth delivered for partner brands' ),
	array( 'figure' => '18', 'label' => 'Energy, telco and insurance brands supported' ),
	array( 'figure' => '100%', 'label' => 'Of sales calls recorded and QA sampled' ),
	array( 'figure' => '4 min', 'label' => 'Average time for a household to switch' ),
);

$brand_cards = $content['brands']['cards'] ?? array(
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
		'desc'       => "A second opinion on what you're paying. Send through a bill and get a like-for-like breakdown against what's actually available in your postcode today.",
		'link_label' => 'Visit Check Your Bill',
		'url'        => '#',
		'domain'     => 'checkyourbill.com.au',
		'rating'     => '★★★★★',
	),
);

$compliance_badges = $content['compliance']['badges'] ?? array(
	'ISO/IEC 27001:2022', 'IAS / IAF accredited', 'Consent-based data only',
);

$paths = $content['paths'] ?? array(
	array(
		'kicker'     => 'For retailers & brands',
		'heading'    => 'Grow your book, defensibly.',
		'text'       => "You need connected customers, compliant channels and a partner who reports honestly. Let's talk volume, unit economics and integration.",
		'cta_label'  => 'Book a partner consultation',
		'cta_url'    => '#contact',
		'cta_style'  => 'primary',
	),
	array(
		'kicker'     => 'For households & business',
		'heading'    => "Find out if you're overpaying.",
		'text'       => 'Power, gas, broadband, mobile or health cover — a like-for-like comparison takes about four minutes and costs you nothing.',
		'cta_label'  => 'Compare my bills',
		'cta_url'    => '#',
		'cta_style'  => 'ghost',
	),
);

$contact_form_options = $content['contact']['form_options'] ?? array(
	'A retailer or brand partner', 'A household comparing bills', 'A business comparing bills', 'A supplier or vendor',
);

?>

<!-- HERO -->
<div class="hero">
  <div class="hex-container hero-inner">
    <div>
      <span class="eyebrow hex-h5 font-mono"><?php echo wp_kses_post( $content['hero']['eyebrow'] ?? '<b>Melbourne</b> · Energy · Telecom · Insurance · Finance' ); ?></span>
      <h1 class="hex-h1"><?php echo wp_kses_post( $content['hero']['heading'] ?? 'Growth for the brands that <span class="accent-text">power</span>, <span class="accent-text">connect</span> and <span class="accent-text">cover</span> Australia.' ); ?></h1>
      <p class="lede hex-lead"><?php echo esc_html( $content['hero']['lede'] ?? 'Mindlabz combines AI-built sales technology with trained, compliant human teams — and proves it every day through our own consumer brands, Compare Your Bills and Check Your Bill.' ); ?></p>
      <div class="hero-cta">
        <a class="btn btn-primary btn-lg" href="<?php echo esc_url( $content['hero']['cta_primary_url'] ?? '#contact' ); ?>"><?php echo esc_html( $content['hero']['cta_primary_label'] ?? 'Partner with us' ); ?></a>
        <a class="btn btn-ghost btn-lg" href="<?php echo esc_url( $content['hero']['cta_secondary_url'] ?? '#services' ); ?>"><?php echo esc_html( $content['hero']['cta_secondary_label'] ?? 'Explore our services' ); ?></a>
      </div>
    </div>
    <div class="hero-card">
      <span class="hero-card-label hex-h5 font-mono"><?php echo esc_html( $content['hero']['card']['label'] ?? 'Partner sales growth' ); ?></span>
      <div class="hero-card-figure hex-h2 font-accent"><?php echo wp_kses_post( $content['hero']['card']['figure'] ?? '<em>+124%</em>' ); ?></div>
      <p class="hex-small"><?php echo esc_html( $content['hero']['card']['caption'] ?? 'Average uplift delivered across supported retail brands.' ); ?></p>
      <div class="hero-card-divider"></div>
      <div class="hero-card-stats">
        <?php
        $hero_stats = $content['hero']['card']['stats'] ?? array(
			array( 'value' => '18', 'label' => 'Brands supported' ),
			array( 'value' => '27001', 'label' => 'ISO/IEC certified' ),
        );
        foreach ( $hero_stats as $stat ) :
        ?>
        <div><b class="hex-h3 font-accent"><?php echo esc_html( $stat['value'] ?? '' ); ?></b><span class="hex-meta"><?php echo esc_html( $stat['label'] ?? '' ); ?></span></div>
        <?php endforeach; ?>
      </div>
    </div>
  </div>

  <div class="logo-strip">
    <div class="hex-container">
      <span class="logo-strip-label hex-h5 font-mono"><?php echo esc_html( $content['logo_strip']['label'] ?? 'Trusted across energy, telecom & insurance' ); ?></span>
      <div class="logo-strip-names hex-small">
        <?php foreach ( $logo_strip_names as $name ) : ?>
        <span><?php echo esc_html( $name ); ?></span>
        <?php endforeach; ?>
      </div>
    </div>
  </div>
</div>

<!-- SERVICES -->
<section id="services">
  <div class="hex-container">
    <div class="sec-head">
      <span class="eyebrow hex-h5 font-mono"><?php echo esc_html( $content['services']['eyebrow'] ?? 'What we do' ); ?></span>
      <h2 class="hex-h2"><?php echo esc_html( $content['services']['heading'] ?? 'Four services. One accountable partner.' ); ?></h2>
      <p class="lede hex-lead"><?php echo esc_html( $content['services']['lede'] ?? 'Retailers come to us for volume they can defend in an audit. Households come to us because switching should take four minutes, not four hours.' ); ?></p>
    </div>
    <div class="rows">
      <?php foreach ( $service_rows as $row ) : ?>
      <a class="row-item" href="<?php echo esc_url( $row['url'] ?? '#' ); ?>">
        <span class="row-item-num hex-h5 font-mono"><?php echo esc_html( $row['num'] ?? '' ); ?></span>
        <h3 class="hex-h3"><?php echo esc_html( $row['title'] ?? '' ); ?></h3>
        <div class="row-item-desc hex-small"><?php echo esc_html( $row['desc'] ?? '' ); ?>
          <ul>
            <?php foreach ( ( $row['tags'] ?? array() ) as $tag ) : ?>
            <li class="badge badge-outline hex-h5 font-mono"><?php echo esc_html( $tag ); ?></li>
            <?php endforeach; ?>
          </ul>
        </div>
        <span class="row-item-go"><svg class="icon icon-sm" viewBox="0 0 16 16" fill="none"><path d="M3 8h10M9 4l4 4-4 4" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"/></svg></span>
      </a>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- PLATFORM (dark band) -->
<div class="dark-band" id="platform">
  <section>
    <div class="hex-container dark-band-grid">
      <div>
        <span class="eyebrow hex-h5 font-mono"><?php echo wp_kses_post( $content['platform']['eyebrow'] ?? '<b>AI & Technology</b>' ); ?></span>
        <h2 class="hex-h2"><?php echo esc_html( $content['platform']['heading'] ?? 'A sales floor that runs on evidence, not instinct.' ); ?></h2>
        <p class="lede hex-lead"><?php echo esc_html( $content['platform']['lede'] ?? 'Every enquiry is scored, routed, worked and recorded. Partners see the same numbers we do, in the same hour — not in a monthly spreadsheet.' ); ?></p>
        <div class="dark-band-cta"><a class="btn btn-primary btn-lg" href="<?php echo esc_url( $content['platform']['cta_url'] ?? '#contact' ); ?>"><?php echo esc_html( $content['platform']['cta_label'] ?? 'Request a walkthrough' ); ?></a></div>
      </div>
      <div class="feature-list hex-small">
        <?php foreach ( $platform_features as $feature ) : ?>
        <div><span class="feature-list-index hex-h5 font-mono"><?php echo esc_html( $feature['index'] ?? '' ); ?></span><div><h4 class="hex-h4"><?php echo esc_html( $feature['title'] ?? '' ); ?></h4><p><?php echo esc_html( $feature['desc'] ?? '' ); ?></p></div></div>
        <?php endforeach; ?>
      </div>
    </div>
  </section>
</div>

<!-- RESULTS -->
<section id="results" class="section-tight-bottom">
  <div class="hex-container">
    <div class="sec-head">
      <span class="eyebrow hex-h5 font-mono"><?php echo esc_html( $content['results']['eyebrow'] ?? 'By the numbers' ); ?></span>
      <h2 class="hex-h2"><?php echo esc_html( $content['results']['heading'] ?? 'What partnership looks like in practice.' ); ?></h2>
    </div>
  </div>
  <div class="stats-grid">
    <?php foreach ( $result_stats as $stat ) : ?>
    <div><span class="stats-figure hex-h2 font-accent"><?php echo wp_kses_post( $stat['figure'] ?? '' ); ?></span><span class="hex-meta"><?php echo esc_html( $stat['label'] ?? '' ); ?></span></div>
    <?php endforeach; ?>
  </div>
</section>

<!-- BRANDS -->
<section id="brands">
  <div class="hex-container">
    <div class="sec-head">
      <span class="eyebrow hex-h5 font-mono"><?php echo esc_html( $content['brands']['eyebrow'] ?? 'Owned consumer brands' ); ?></span>
      <h2 class="hex-h2"><?php echo wp_kses_post( $content['brands']['heading'] ?? 'We don\'t just sell the service — <span class="accent-text">we run it.</span>' ); ?></h2>
      <p class="lede hex-lead"><?php echo esc_html( $content['brands']['lede'] ?? "Our comparison brands are where the technology is proven: real households, real switches, real compliance load. That's what partners are actually buying." ); ?></p>
    </div>
    <div class="brand-grid">
      <?php foreach ( $brand_cards as $card ) : ?>
      <div class="brand-card card hex-small">
        <span class="tag hex-h5 font-mono"><?php echo esc_html( $card['tag'] ?? '' ); ?></span>
        <h3 class="hex-h3"><?php echo esc_html( $card['title'] ?? '' ); ?></h3>
        <p><?php echo esc_html( $card['desc'] ?? '' ); ?></p>
        <a class="link-arrow" href="<?php echo esc_url( $card['url'] ?? '#' ); ?>"><?php echo esc_html( $card['link_label'] ?? '' ); ?> <svg class="icon icon-sm" viewBox="0 0 16 16" fill="none"><path d="M3 8h10M9 4l4 4-4 4" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg></a>
        <div class="brand-foot"><small class="hex-h5 font-mono"><?php echo esc_html( $card['domain'] ?? '' ); ?></small><small class="hex-h5 font-mono"><?php echo esc_html( $card['rating'] ?? '' ); ?></small></div>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- QUOTE + COMPLIANCE -->
<section class="section-tight-top">
  <div class="hex-container">
    <div class="quote-block">
      <span class="quote-mark hex-h1 font-accent">&#8220;</span>
      <div>
        <p class="hex-h3 font-accent"><?php echo esc_html( $content['quote']['text'] ?? "Compare Your Bills' staff were very professional and helped me find the best package that met my needs — the whole transfer of electricity and gas was easy and simplified." ); ?></p>
        <div class="quote-who hex-meta font-mono"><?php echo esc_html( $content['quote']['attribution'] ?? 'Rizwan Saeed · Compare Your Bills customer' ); ?></div>
      </div>
    </div>

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

<!-- PATHS -->
<section class="section-tight-top">
  <div class="hex-container">
    <div class="paths-split">
      <?php foreach ( $paths as $path ) : ?>
      <div class="path-card hex-small">
        <span class="path-k hex-h5 font-mono"><?php echo esc_html( $path['kicker'] ?? '' ); ?></span>
        <h3 class="hex-h3"><?php echo esc_html( $path['heading'] ?? '' ); ?></h3>
        <p><?php echo esc_html( $path['text'] ?? '' ); ?></p>
        <a class="btn <?php echo ( 'ghost' === ( $path['cta_style'] ?? 'primary' ) ) ? 'btn-ghost' : 'btn-primary'; ?> btn-lg" href="<?php echo esc_url( $path['cta_url'] ?? '#' ); ?>"><?php echo esc_html( $path['cta_label'] ?? '' ); ?></a>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- CONTACT -->
<section id="contact" class="section-tight-top">
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
      <form onsubmit="event.preventDefault();this.querySelector('.btn').textContent='Thanks — we\'ll be in touch';">
        <div>
          <label class="form-label hex-meta">I'm enquiring as <i>*</i></label>
          <select class="form-control hex-small">
            <?php foreach ( $contact_form_options as $option ) : ?>
            <option><?php echo esc_html( $option ); ?></option>
            <?php endforeach; ?>
          </select>
        </div>
        <div class="form-row-split">
          <div><label class="form-label hex-meta">First name <i>*</i></label><input class="form-control hex-small" type="text" placeholder="Jane"></div>
          <div><label class="form-label hex-meta">Company</label><input class="form-control hex-small" type="text" placeholder="Optional"></div>
        </div>
        <div><label class="form-label hex-meta">Email <i>*</i></label><input class="form-control hex-small" type="email" placeholder="jane@company.com.au"></div>
        <div><label class="form-label hex-meta">Phone</label><input class="form-control hex-small" type="tel" placeholder="04XX XXX XXX"></div>
        <div><label class="form-label hex-meta">What are you trying to solve?</label><textarea class="form-control hex-small" placeholder="A sentence or two is plenty."></textarea></div>
        <button class="btn btn-primary btn-lg btn-submit"><?php echo esc_html( $content['contact']['submit_label'] ?? 'Send enquiry' ); ?></button>
        <p class="fine-print hex-meta"><?php echo esc_html( $content['contact']['fine_print'] ?? "We'll only use these details to respond to your enquiry. See our Privacy Policy." ); ?></p>
      </form>
    </div>
  </div>
</section>

<?php get_footer(); ?>
