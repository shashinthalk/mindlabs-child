<?php
/**
 * Template Name: Results
 *
 * Gives the Home page's "Results" section (`#results`, the primary
 * nav's "Results" link — GUIDELINES.md §9) its own dedicated page/URL,
 * same pattern as template-services.php: a `.page-hero`/`.breadcrumb`
 * title band, then the section's own content reusing template-home.php's
 * exact `.stats-grid` markup and copy.
 *
 * Per rule 10, calls get_header()/get_footer() rather than rendering
 * its own header/footer.
 *
 * Text content: per rule 8/`GUIDELINES.md` §8, every piece of
 * editor-facing copy reads from hex_get_page_content() with a `??`
 * fallback. The stats themselves are template-home.php's original
 * "Results" section copy exactly; the hero lede is new copy written for
 * this page (the original section had no lede of its own, only an
 * eyebrow/heading) — kept short and in the same voice as the rest of
 * the site's hero copy. This page has its own separate JSON row, keyed
 * by its own page ID.
 *
 * @package HexnityWPChild
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

$content = function_exists( 'hex_get_page_content' ) ? hex_get_page_content() : array();

$result_stats = $content['stats'] ?? array(
	array( 'figure' => '<em>+124%</em>', 'label' => 'Sales growth delivered for partner brands' ),
	array( 'figure' => '18', 'label' => 'Energy, telco and insurance brands supported' ),
	array( 'figure' => '100%', 'label' => 'Of sales calls recorded and QA sampled' ),
	array( 'figure' => '4 min', 'label' => 'Average time for a household to switch' ),
);
?>

<div class="page-hero">
	<div class="hex-container">
		<span class="breadcrumb hex-small"><a href="<?php echo esc_url( home_url( '/' ) ); ?>">Home</a> / <?php echo esc_html( $content['hero']['breadcrumb_label'] ?? 'Results' ); ?></span>
		<h1 class="hex-h1"><?php echo wp_kses_post( $content['hero']['heading'] ?? 'What partnership looks like in <span class="accent-text">practice</span>.' ); ?></h1>
		<p class="lede hex-lead"><?php echo esc_html( $content['hero']['lede'] ?? 'Every figure below is pulled from the same dashboards our partners see themselves — updated in real time, not a monthly deck.' ); ?></p>
		<div class="hero-cta">
			<a class="btn btn-primary btn-lg" href="<?php echo esc_url( $content['hero']['cta_url'] ?? home_url( '/contact/' ) ); ?>"><?php echo esc_html( $content['hero']['cta_label'] ?? 'Book a partner consultation' ); ?></a>
		</div>
	</div>
</div>

<main id="primary">
	<section>
		<div class="stats-grid">
			<?php foreach ( $result_stats as $stat ) : ?>
			<div><span class="stats-figure hex-h2 font-accent"><?php echo wp_kses_post( $stat['figure'] ?? '' ); ?></span><span class="hex-meta"><?php echo esc_html( $stat['label'] ?? '' ); ?></span></div>
			<?php endforeach; ?>
		</div>
	</section>
</main>

<?php get_footer(); ?>
