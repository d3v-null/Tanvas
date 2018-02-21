<?php
/**
 * Header Template
 *
 * Here we setup all logic and XHTML that is required for the header section of all screens.
 *
 * @package WooFramework
 * @subpackage Template
 */

$woo_options = get_option( 'woo_options' );
global $woo_options, $wp_query;

?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
	<head>
		<meta charset="<?php echo esc_attr( get_bloginfo( 'charset' ) ); ?>" />
		<title><?php woo_title(); ?></title>
		<?php woo_meta(); ?>
		<link rel="pingback" href="<?php echo esc_url( get_bloginfo( 'pingback_url' ) ); ?>" />
		<?php wp_head(); ?>
		<?php woo_head(); ?>
	</head>

<body <?php body_class(); ?>>

<div class="off-canvas-wrap" data-offcanvas>
<div class="inner-wrap">

<!-- main content goes here -->
<section class="main-section">

	<header id="header-section" class="foundation">

		<?php get_template_part('templates/content', 'topheader'); ?>

		<?php get_template_part('templates/content', 'header'); ?>

		<?php get_template_part('templates/content', 'menu'); ?>

	</header>


	<div class="main-slider">
		<?php if ( ( isset( $woo_options['woo_slider_biz'] ) && 'true' == $woo_options['woo_slider_biz'] ) && ( isset( $woo_options['woo_slider_biz_full'] ) && 'true' == $woo_options['woo_slider_biz_full'] ) ) { $saved = $wp_query; woo_slider_biz(); $wp_query = $saved; } ?>
	</div>

<div id="wrapper-section">
<div id="wrapper">
	<div id="inner-wrapper">

		<?php //woo_header_before(); ?>
		<!--header id="header" class="col-full">
			<?php //woo_header_inside(); ?>
		</header-->
		<?php //woo_header_after(); ?>
		<?php //woo_top(); ?>
