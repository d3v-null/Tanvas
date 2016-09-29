<?php

/**
 * Twitter shortcode
 */

// Fix twitter button

function lc_shortcode_twitter($atts, $content = null) {
   	global $post;
   	extract(shortcode_atts(array(	'url' => '',
   									'style' => '',
   									'source' => '',
   									'text' => '',
   									'related' => '',
   									'lang' => '',
   									'float' => 'left',
   									'use_post_url' => 'false',
   									'recommend' => '',
   									'hashtag' => '',
   									'size' => '',
   									 ), $atts));
	$output = '';

	if ( $url )
		$output .= ' data-url="' . esc_url( $url ) . '"';

	if ( $source )
		$output .= ' data-via="' . esc_attr( $source ) . '"';

	if ( $text )
		$output .= ' data-text="' . esc_attr( $text ) . '"';

	if ( $related )
		$output .= ' data-related="' . esc_attr( $related ) . '"';

	if ( $hashtag )
		$output .= ' data-hashtags="' . esc_attr( $hashtag ) . '"';

	if ( $size )
		$output .= ' data-size="' . esc_attr( $size ) . '"';

	if ( $lang )
		$output .= ' data-lang="' . esc_attr( $lang ) . '"';

	if ( $style != '' ) {
		$output .= 'data-count="' . esc_attr( $style ) . '"';
	}

	if ( $use_post_url == 'true' && $url == '' ) {
		$output .= ' data-url="' . get_permalink( $post->ID ) . '"';
	}

	$output = '<div class="woo-sc-twitter ' . esc_attr( $float )
            . '"><a href="' . esc_url( 'https://twitter.com/share' )
            . '" class="twitter-share-button"'. $output .'>'
            . __( 'Tweet', 'woothemes' )
            . '</a><script type="text/javascript" src="'
            . esc_url ( 'https://platform.twitter.com/widgets.js' )
            . '"></script></div>';
	return $output;

} // End woo_shortcode_twitter()

add_shortcode( 'twitter-https', 'lc_shortcode_twitter' );

/**
 * Shortcodes for product showcase pages
 */

?>
