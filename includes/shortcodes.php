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


function tanvas_wholesale_content_restricted_shortcode($args, $content=""){
    $args = shortcode_atts( array(
        'object_type' => 'product_cat'
    ), $args);

    $required_authorities = array();
    if (class_exists('Lasercommerce_Tier_Tree')) {
        $required_authorities = array(
            $required_authorities = Lasercommerce_Tier_Tree::instance()->getWholesaleTier()
        );
        // global $Lasercommerce_Plugin;
        // if (isset($Lasercommerce_Plugin)) {
        //     $required_authorities = array($Lasercommerce_Plugin->tree->getWholesaleTier() );
        // }
    }
    $user_authorities = tanvas_get_user_tiers();
    $object_type = $args['object_type'];
    $visible = tanvas_is_user_wholesale();
    $out = tanvas_display_tier_warnings($required_authorities, $user_authorities, $object_type, $visible, false);
    $out .= $content;
    return $out;
}

function tanvas_tier_restrict_content_shortcode($args, $content){
    $_procedure = "TNV_TIER_RESTR_CONT: ";

    $args = shortcode_atts( array(
        'tiers' => '',
        'hide_tiers' => '',
        'logged_in' => '',
    ), $args);

    if (TANVAS_DEBUG) error_log($_procedure . "args: ". serialize($args));
    if (TANVAS_DEBUG) error_log($_procedure . "content: ". serialize($content));

    // $out = '';

    $message_visible = true;
    if(isset($args['logged_in']) and ! empty($args['logged_in']) ){
        switch ($args['logged_in']) {
            case 'true':
                if( is_user_logged_in() ){
                    $message_visible = true;
                } else {
                    $message_visible = false;
                }
                break;
            case 'false':
                if( is_user_logged_in() ){
                    $message_visible = false;
                } else {
                    $message_visible = true;
                }
            default:
                break;
        }
    }
    if($message_visible) {
        $user_tier_ids = tanvas_get_user_visible_tier_ids();
        // $out .= "user_tiers: ".serialize($user_tier_ids)."<br/>";
        if (isset($args['tiers']) and $args['tiers']){
            $message_visible = false;
            $required_tier_ids = explode(',', $args['tiers']);
            // $out .= "required: " . $args['tiers']."<br/>";
            if (class_exists('Lasercommerce_Visibility')) {
                $message_visible = Lasercommerce_Visibility::tier_ids_satisfy_requirement($user_tier_ids, $required_tier_ids);
                // global $Lasercommerce_Plugin;
                // if (isset($Lasercommerce_Plugin)) {
                //     // $message_visible = false;
                //     // $required_tier_ids = array($Lasercommerce_Plugin->tree->getWholesaleTier() );
                //     $message_visible = $Lasercommerce_Plugin->visibility->tier_ids_satisfy_requirement($user_tier_ids, $required_tier_ids);
                //     // $out .= "visible: " . ($message_visible) ."<br/>";
                // }
            }
        } elseif (isset($args['hide_tiers']) and $args['hide_tiers']) {
            $required_tier_ids = explode(',', $args['hide_tiers']);
            // $out .= "hide_tiers: " . $args['hide_tiers']."<br/>";
            if(class_exists('Lasercommerce_Visibility')) {
                $message_visible = ! Lasercommerce_Visibility::tier_ids_satisfy_requirement($user_tier_ids, $required_tier_ids);
                // global $Lasercommerce_Plugin;
                // if(isset($Lasercommerce_Plugin)){
                //     $message_visible = ! $Lasercommerce_Plugin->visibility->tier_ids_satisfy_requirement($user_tier_ids, $required_tier_ids);
                //     // $out .= "visible: " . ($message_visible) . "<br/>";
                // }
            }
        }
    }

    if( $message_visible){
        if (TANVAS_DEBUG) error_log($_procedure . "not visible");

        return do_shortcode($content);
        // return $out . do_shortcode($content);
    } else{
        if (TANVAS_DEBUG) error_log($_procedure . "visible");
        return '';
        // return $out;
    }

}

add_shortcode('tanvas_wholesale_content_restricted_message', 'tanvas_wholesale_content_restricted_shortcode');

add_shortcode('tanvas_tier_restrict_content', 'tanvas_tier_restrict_content_shortcode');

/**
 * Shortcodes for product showcase pages
 */

function tanvas_product_showcase_table_shortcode($args, $content=''){
    $out = '<table class="product-info"><tbody>';
    $out .= do_shortcode($content);
    $out .= '</tbody></table>';
    return $out;
}

function tanvas_product_showcase_row_shortcode($args, $content=''){
    $args = shortcode_atts( array(
        // 'tiers' => '',
        'title' => '',            # SC e.g. 'Tanning Solution'
        'title_href' => null,     #    e.g. '/products/tanning_solution'
        'img_src' => null,        #    e.g. '/wp-content/uploads/Solution-V1-300x300.jpg'
        'img_alt' => null,        #    e.g. 'Tanning Solution'
        'features'      => null,  #    e.g. '{"strong":"Flash Tan Blue", "normal":"Medium, Dark & Extra Dark"}, {"strong":"BioTan Plus", "normal":"Latte, Cappuccino, Mocha & Espresso"}'
        'buttons'      => null,   #    e.g. '{"link":"/products/tanning_solution", "text":"More Info"}, {"link":"/shop/product-category/solution", "text":"Shop"}'
        'pre_features'  => null,  #    e.g. 'available in...'
    ), $args);

    error_log(print_r($args, true));

    if(empty($args['img_alt'])){
        $args['img_alt'] = $args['title'];
    }

    $out = '';

    if(!empty($args['title'])){
        //Then do title <th>

        $title_html = do_shortcode((string)$args['title']);
        // $title_html = '<h2>' . $title_html . '</h2>';
        $title_html = "<h2>$title_html</h2>";
        if(!empty($args['title_href'])){
            $title_html = "<a href=\"{$args['title_href']}\">$title_html</a>";
        }

        $out .= "<tr><th class=\"tanvas_showcase_title\" colspan=\"2\">$title_html</th></tr>";
    }

    $lower_html = '';

    if(!empty($args['img_src'])){
        if(!empty($args['img_src'])){
            // Then do IMG <td>
            $img_html = "<img src=\"{$args['img_src']}\"";
            if(!empty($args['img_alt'])){
                $img_html .= " alt=\"{$args['img_alt']}\"";
            }
            $img_html .= " />";
            $lower_html .= "<td style\"width: 30%important;\">$img_html</td>";
        }
    }

    $content_html = '';
    if(!empty($content)){
        $content_html .= "<p class=\"tanvas_showcase_content\">".do_shortcode($content)."</p>";
    }

    $features = array();
    if(!empty($args['features'])){
        $features = json_decode("[{$args['features']}]", true);
    }
    if(!empty($features)){
        // $content_html .= "<p>".print_r($features, true)."</p>";
        $features_html = "";
        foreach ($features as $feature) {
            $feature_html = "";
            if(isset($feature['strong'])){
                $feature_html .= "<strong>{$feature['strong']}</strong>";
            }
            if(isset($feature['normal'])){
                if(!empty($feature_html)) $feature_html .= ' - ';
                $feature_html .= "{$feature['normal']}";
            }
            $features_html .= "<li>$feature_html</li>";
        }
        $content_html .= "<p class=\"tanvas_showcase_features\"><ul>$features_html</ul></p>";
    }

    $buttons = array();
    if(!empty($args['buttons'])){
        $buttons = json_decode("[{$args['buttons']}]", true);
    }
    if(!empty($buttons)){
        // $content_html .= "<p>".print_r($buttons, true)."</p>";
        $buttons_html = "";
        foreach ($buttons as $button) {
            $button_shortcode = "[button";
            if(isset($button['link'])){
                $button_shortcode .= " link=\"{$button['link']}\"";
            }
            $button_shortcode .= "]";
            if(isset($button['text'])){
                $button_shortcode .= "{$button['text']}";
            }
            $button_shortcode .= "[/button]";
            $buttons_html .= do_shortcode($button_shortcode);
            // $buttons_html .= ($button_shortcode);
        }
        $content_html .= "<p class=\"tanvas_showcase_buttons\"><p>$buttons_html</ul><p>";
    }

    $buttons = array();
    if(!empty($args['buttons'])){
        $buttons = json_decode($args['buttons']);
    }
    if(!empty($buttons)){
        $content_html .= "<p>".print_r($buttons, true)."</p>";
    }

    if(0){
        $content_html .= "<p>".print_r($args, true)."</p>";
    }

    if(!empty($content_html)){
        $lower_html .= "<td>$content_html</td>";
    }

    if(!empty($lower_html)){
        $out .= "<tr>$lower_html</tr>";
    }

    return $out;
}

add_shortcode('tanvas_product_showcase_table', 'tanvas_product_showcase_table_shortcode');
add_shortcode('tanvas_product_showcase_row', 'tanvas_product_showcase_row_shortcode');

/** Email signature shortcode **/

function tanvas_email_signature_shortcode($args, $content=''){
    global $woo_options;
    $signature_html = $content;
    if( isset($woo_options['email_signature'])){
        $signature_html .= $woo_options['email_signature'];
    }
    return wp_kses($signature_html, wp_kses_allowed_html('post'));
}

add_shortcode('tanvas_email_signature', 'tanvas_email_signature_shortcode');

?>
