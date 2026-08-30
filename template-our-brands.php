<?php
/**
 * Template Name: Our Brands
 *
 * Gives the Home page's "Brands" section (`#brands`, the primary nav's
 * "Our Brands" link — GUIDELINES.md §9) its own dedicated page/URL,
 * same pattern as template-services.php: a `.page-hero`/`.breadcrumb`
 * title band, then the section's own content reusing template-home.php's
 * exact `.brand-grid`/`.brand-card` markup and copy. Also carries the
 * `.quote-block` testimonial (a Compare Your Bills customer quote) —
 * thematically it's evidence for the brands themselves, so it travels
 * with this page rather than staying only on Home.
 *
 * Per rule 10, calls get_header()/get_footer() rather than rendering
 * its own header/footer.
 *
 * Text content: per rule 8/`GUIDELINES.md` §8, every piece of
 * editor-facing copy reads from hex_get_page_content() with a `??`
 * fallback equal to template-home.php's original "Brands"/quote copy
 * exactly. This page has its own separate JSON row, keyed by its own
 * page ID.
 *
 * @package HexnityWPChild
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

$content = function_exists( 'hex_get_page_content' ) ? hex_get_page_content() : array();

$brand_cards = $content['cards'] ?? array(
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
?>

<div class="page-hero">
	<div class="hex-container">
		<span class="breadcrumb hex-small"><a href="<?php echo esc_url( home_url( '/' ) ); ?>">Home</a> / <?php echo esc_html( $content['hero']['breadcrumb_label'] ?? 'Our Brands' ); ?></span>
		<h1 class="hex-h1"><?php echo wp_kses_post( $content['hero']['heading'] ?? 'We don\'t just sell the service — <span class="accent-text">we run it.</span>' ); ?></h1>
		<p class="lede hex-lead"><?php echo esc_html( $content['hero']['lede'] ?? "Our comparison brands are where the technology is proven: real households, real switches, real compliance load. That's what partners are actually buying." ); ?></p>
		<div class="hero-cta">
			<a class="btn btn-primary btn-lg" href="<?php echo esc_url( $content['hero']['cta_url'] ?? home_url( '/contact/' ) ); ?>"><?php echo esc_html( $content['hero']['cta_label'] ?? 'Partner with us' ); ?></a>
		</div>
	</div>
</div>

<main id="primary">
	<section>
		<div class="hex-container">
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

	<section class="section-tight-top">
		<div class="hex-container">
			<div class="quote-block">
				<span class="quote-mark hex-h1 font-accent">&#8220;</span>
				<div>
					<p class="hex-h3 font-accent"><?php echo esc_html( $content['quote']['text'] ?? "Compare Your Bills' staff were very professional and helped me find the best package that met my needs — the whole transfer of electricity and gas was easy and simplified." ); ?></p>
					<div class="quote-who hex-meta font-mono"><?php echo esc_html( $content['quote']['attribution'] ?? 'Rizwan Saeed · Compare Your Bills customer' ); ?></div>
				</div>
			</div>
		</div>
	</section>
</main>

<?php get_footer(); ?>
