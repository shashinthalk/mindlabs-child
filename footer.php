<?php
/**
 * Footer template — overrides the parent theme's neutral footer.php
 * while this child theme is active (WordPress checks the child theme
 * first for get_footer()). Delegates the actual "Meridian" brand
 * footer markup to template-parts/site-footer.php (this child theme's
 * own), then closes the document opened by header.php. See
 * GUIDELINES.md §9.
 *
 * @package HexnityWPChild
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<?php get_template_part( 'template-parts/site-footer' ); ?>
<?php wp_footer(); ?>
</body>
</html>
