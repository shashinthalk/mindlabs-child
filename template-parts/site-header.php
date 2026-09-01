<?php
/**
 * "Meridian" brand header — this child theme's own global header,
 * overriding the parent theme's neutral header.php while this child
 * theme is active. Same markup/classes as template-home.php's own
 * inline <header> block (site-nav/site-logo/nav-menu), but as
 * reusable global chrome: nav links come from the real "primary" WP
 * nav menu (theme_location) rather than a hardcoded array, and the
 * brand name/logo/phone/CTA come from the parent's "Site Content"
 * JSON framework (hex_get_site_content(), inc/site-content.php in the
 * parent theme) via the "Header & Footer" admin page, rather than a
 * specific page's own Page Content JSON — this is site-wide chrome,
 * not one page's content. See GUIDELINES.md §9.
 *
 * Logo (added 2026-09-01, replacing the former inline-SVG mark +
 * brand-name text): a real `<img>`, `logo_url`/`logo_alt` keys under
 * this same header JSON, falling back to this theme's own
 * assets/images/site-logo.svg placeholder — swap `logo_url` for a
 * Media Library upload once a real logo exists, no code change
 * needed, same pattern as template-broadband.php's `brand.logo_url`.
 *
 * No mobile menu toggle by design — matches the Meridian concept's own
 * behavior (.nav-menu is simply hidden below 1000px via site-theme.css,
 * no replacement control), not an oversight.
 *
 * @package HexnityWPChild
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$hex_header_content = function_exists( 'hex_get_site_content' ) ? hex_get_site_content( 'header' ) : array();

$hex_brand_name     = $hex_header_content['brand_name'] ?? get_bloginfo( 'name' );
$hex_brand_name     = '' !== $hex_brand_name ? $hex_brand_name : 'Mindlabz';
$hex_logo_url       = $hex_header_content['logo_url'] ?? get_stylesheet_directory_uri() . '/assets/images/site-logo.svg';
$hex_logo_alt       = $hex_header_content['logo_alt'] ?? $hex_brand_name;
$hex_phone          = $hex_header_content['phone'] ?? '1300 110 829';
$hex_cta_label      = $hex_header_content['cta_label'] ?? 'Book a consultation';
$hex_cta_label_short = $hex_header_content['cta_label_short'] ?? 'Enquire';
$hex_cta_url        = $hex_header_content['cta_url'] ?? '#contact';
$hex_skip_link_text = $hex_header_content['skip_link_text'] ?? __( 'Skip to content', 'hexnity-wp-child' );
?>
<a class="sr-only focus:not-sr-only focus:fixed focus:left-2 focus:top-2 focus:z-[100000] focus:rounded focus:bg-gray-900 focus:px-4 focus:py-3 focus:text-white" href="#primary">
	<?php echo esc_html( $hex_skip_link_text ); ?>
</a>

<header>
	<div class="hex-container site-nav">
		<a class="site-logo" href="<?php echo esc_url( home_url( '/' ) ); ?>">
			<img class="site-logo-img" src="<?php echo esc_url( $hex_logo_url ); ?>" alt="<?php echo esc_attr( $hex_logo_alt ); ?>">
		</a>
		<nav id="site-navigation" aria-label="<?php esc_attr_e( 'Primary', 'hexnity-wp-child' ); ?>">
			<?php
			wp_nav_menu(
				array(
					'theme_location' => 'primary',
					'menu_id'        => 'primary-menu',
					'menu_class'     => 'nav-menu',
					'container'      => false,
					'fallback_cb'    => false,
				)
			);
			?>
		</nav>
		<span class="spacer"></span>
		<span class="site-tel hex-small font-mono"><?php echo esc_html( $hex_phone ); ?></span>
		<a class="btn btn-primary" href="<?php echo esc_url( $hex_cta_url ); ?>">
			<span class="lbl-long"><?php echo esc_html( $hex_cta_label ); ?></span>
			<span class="lbl-short"><?php echo esc_html( $hex_cta_label_short ); ?></span>
		</a>
	</div>
</header>
