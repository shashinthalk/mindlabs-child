<?php
/**
 * "Meridian" brand footer — this child theme's own global footer,
 * overriding the parent theme's neutral footer.php while this child
 * theme is active. Same markup/classes as template-home.php's own
 * inline <footer> block (footer-grid/footer-blurb/footer-bottom), but
 * driven by the parent's "Site Content" JSON framework
 * (hex_get_site_content(), inc/site-content.php in the parent theme)
 * via the "Header & Footer" admin page rather than one page's own
 * Page Content JSON — this is site-wide chrome. Every default below
 * matches template-home.php's own hardcoded fallback copy exactly, so
 * this footer looks identical to the Home page's footer until an
 * editor customizes the JSON. See GUIDELINES.md §9.
 *
 * Logo (added 2026-09-01, replacing the former inline-SVG mark +
 * brand-name text): a real `<img>`, resolved by
 * hexnity_wp_child_get_site_logo() (functions.php) in priority order —
 * this footer JSON's own `logo_url`/`logo_alt` override, else the
 * site's own Customizer "Site Identity" logo (same one the header
 * uses, unless this section's own `logo_url` is set to something
 * different), else this theme's own assets/images/site-logo-white.svg
 * placeholder (a light-on-dark variant, since the footer background is
 * dark — see the site-header.php entry for the header's own colored
 * variant).
 *
 * @package HexnityWPChild
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$hex_footer_content = function_exists( 'hex_get_site_content' ) ? hex_get_site_content( 'footer' ) : array();

$hex_brand_name = $hex_footer_content['brand_name'] ?? get_bloginfo( 'name' );
$hex_brand_name = '' !== $hex_brand_name ? $hex_brand_name : 'Mindlabz';
$hex_logo        = function_exists( 'hexnity_wp_child_get_site_logo' )
	? hexnity_wp_child_get_site_logo( $hex_footer_content, get_stylesheet_directory_uri() . '/assets/images/site-logo-white.svg' )
	: array( 'url' => get_stylesheet_directory_uri() . '/assets/images/site-logo-white.svg', 'alt' => '' );
$hex_logo_url    = $hex_logo['url'];
$hex_logo_alt    = '' !== $hex_logo['alt'] ? $hex_logo['alt'] : $hex_brand_name;
$hex_blurb       = $hex_footer_content['blurb'] ?? 'Sales technology and managed acquisition for Australian energy, telecom, insurance and finance brands.';
$hex_phone       = $hex_footer_content['phone'] ?? '1300 110 829';
$hex_email       = $hex_footer_content['email'] ?? 'info@mindlabz.com.au';
$hex_abn         = $hex_footer_content['abn'] ?? 'ABN 00 000 000 000';
$hex_address     = $hex_footer_content['address'] ?? '10.1, 3 Bowen Crescent, Melbourne VIC 3004';

$hex_footer_columns = $hex_footer_content['columns'] ?? array(
	array(
		'heading' => 'Company',
		'links'   => array(
			array(
				'label' => 'About us',
				'url'   => '#',
			),
			array(
				'label' => 'Careers',
				'url'   => '#',
			),
			array(
				'label' => 'Contact',
				'url'   => '#',
			),
			array(
				'label' => 'Compliance',
				'url'   => '#',
			),
		),
	),
	array(
		'heading' => 'Services',
		'links'   => array(
			array(
				'label' => 'AI & IT Solutions',
				'url'   => '#',
			),
			array(
				'label' => 'Energy broking',
				'url'   => '#',
			),
			array(
				'label' => 'Broadband & mobile',
				'url'   => '#',
			),
			array(
				'label' => 'Private health cover',
				'url'   => '#',
			),
		),
	),
	array(
		'heading' => 'Our brands',
		'links'   => array(
			array(
				'label' => 'Compare Your Bills',
				'url'   => '#',
			),
			array(
				'label' => 'Check Your Bill',
				'url'   => '#',
			),
		),
	),
);

$hex_footer_bottom_links = $hex_footer_content['bottom_links'] ?? array(
	array(
		'label' => 'Terms & Conditions',
		'url'   => '#',
	),
	array(
		'label' => 'Privacy Policy',
		'url'   => '#',
	),
	array(
		'label' => 'Complaints',
		'url'   => '#',
	),
);
?>
<footer>
	<div class="hex-container">
		<div class="footer-grid hex-small">
			<div>
				<a class="site-logo" href="<?php echo esc_url( home_url( '/' ) ); ?>">
					<img class="site-logo-img" src="<?php echo esc_url( $hex_logo_url ); ?>" alt="<?php echo esc_attr( $hex_logo_alt ); ?>">
				</a>
				<p class="footer-blurb"><?php echo esc_html( $hex_blurb ); ?></p>
			</div>
			<?php foreach ( $hex_footer_columns as $hex_i => $hex_column ) : ?>
			<div>
				<h5 class="hex-h5 font-mono"><?php echo esc_html( $hex_column['heading'] ?? '' ); ?></h5>
				<ul>
					<?php foreach ( ( $hex_column['links'] ?? array() ) as $hex_link ) : ?>
					<li><a href="<?php echo esc_url( $hex_link['url'] ?? '#' ); ?>"><?php echo esc_html( $hex_link['label'] ?? '' ); ?></a></li>
					<?php endforeach; ?>
				</ul>
				<?php if ( 2 === $hex_i ) : ?>
				<h5 class="hex-h5 font-mono footer-subheading"><?php esc_html_e( 'Contact', 'hexnity-wp-child' ); ?></h5>
				<ul>
					<li><a href="<?php echo esc_url( 'tel:' . preg_replace( '/\s+/', '', $hex_phone ) ); ?>"><?php echo esc_html( $hex_phone ); ?></a></li>
					<li><a href="<?php echo esc_url( 'mailto:' . $hex_email ); ?>"><?php echo esc_html( $hex_email ); ?></a></li>
				</ul>
				<?php endif; ?>
			</div>
			<?php endforeach; ?>
		</div>
		<div class="footer-bottom hex-meta">
			<span>© <?php echo esc_html( gmdate( 'Y' ) ); ?> <?php echo esc_html( $hex_brand_name ); ?> Pty Ltd · <?php echo esc_html( $hex_abn ); ?> · <?php echo esc_html( $hex_address ); ?></span>
			<span>
				<?php foreach ( $hex_footer_bottom_links as $hex_index => $hex_link ) : ?>
				<?php echo 0 === $hex_index ? '' : ' · '; ?><a href="<?php echo esc_url( $hex_link['url'] ?? '#' ); ?>"><?php echo esc_html( $hex_link['label'] ?? '' ); ?></a>
				<?php endforeach; ?>
			</span>
		</div>
	</div>
</footer>
