<?php
/**
 * Template Name: Services
 *
 * Gives the Home page's "Services" section (`#services`, the primary
 * nav's "Services" link — GUIDELINES.md §9) its own dedicated page/URL,
 * the same pattern template-ai-technology.php already established for
 * "AI & Technology": a `.page-hero`/`.breadcrumb` title band (per
 * TEMPLATE-CONVERSION-GUIDE.md step 6/10), then the section's own
 * content reusing template-home.php's exact `.rows`/`.row-item` markup
 * and copy — no new CSS needed, every class already exists.
 *
 * Per rule 10, calls get_header()/get_footer() rather than rendering
 * its own header/footer.
 *
 * Text content: per rule 8/`GUIDELINES.md` §8, every piece of
 * editor-facing copy reads from hex_get_page_content() with a `??`
 * fallback equal to template-home.php's original "Services" section
 * copy exactly, so this page renders identically to that section until
 * an editor customizes its own, separate JSON (this page has its own
 * row, keyed by its own page ID — not shared with the Home page's).
 *
 * @package HexnityWPChild
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

$content = function_exists( 'hex_get_page_content' ) ? hex_get_page_content() : array();

$service_rows = $content['rows'] ?? array(
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
);
?>

<div class="page-hero">
	<div class="hex-container">
		<span class="breadcrumb hex-small"><a href="<?php echo esc_url( home_url( '/' ) ); ?>">Home</a> / <?php echo esc_html( $content['hero']['breadcrumb_label'] ?? 'Services' ); ?></span>
		<h1 class="hex-h1"><?php echo wp_kses_post( $content['hero']['heading'] ?? 'Four services. One accountable <span class="accent-text">partner</span>.' ); ?></h1>
		<p class="lede hex-lead"><?php echo esc_html( $content['hero']['lede'] ?? 'Retailers come to us for volume they can defend in an audit. Households come to us because switching should take four minutes, not four hours.' ); ?></p>
		<div class="hero-cta">
			<a class="btn btn-primary btn-lg" href="<?php echo esc_url( $content['hero']['cta_url'] ?? home_url( '/contact/' ) ); ?>"><?php echo esc_html( $content['hero']['cta_label'] ?? 'Book a consultation' ); ?></a>
		</div>
	</div>
</div>

<main id="primary">
	<section>
		<div class="hex-container">
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
</main>

<?php get_footer(); ?>
