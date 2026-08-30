<?php
/**
 * Header template — overrides the parent theme's neutral header.php
 * while this child theme is active (WordPress checks the child theme
 * first for get_header()). Opens <html>/<body>, then delegates the
 * actual "Meridian" brand header markup to
 * template-parts/site-header.php (this child theme's own), same
 * architecture the parent theme uses for its own header.php. See
 * GUIDELINES.md §9.
 *
 * @package HexnityWPChild
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1">
<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<?php get_template_part( 'template-parts/site-header' ); ?>
