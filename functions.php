<?php

define( 'TANVAS_DOMAIN', 'tanvas');
if(!defined('TANVAS_DEBUG') ){
	define( 'TANVAS_DEBUG', 'true');
}

function woo_metaboxes_add($metaboxes){
	// if(TANVAS_DEBUG) error_log("TANVAS woo_custom_template:".serialize(get_option('woo_custom_template')));
	if(TANVAS_DEBUG) error_log("TANVAS METABOXES:".serialize($metaboxes));
	$metaboxes = apply_filters('tanvas_metaboxes', $metaboxes);
	return $metaboxes;
}

function woo_options_add($options){

	$options[] = array( "name" => __( 'Tanvas Settings'),
						"icon" => "styling",
						"type" => "heading");
	$options[] = array( "name" => __( 'Brand Styling'),
						"type" => "subheading");
	$options[] = array( "name" =>  __( 'Brand Color' ),
						"desc" => __( 'Pick a custom color for site branding or add a hex color code e.g. #e6e6e6' ),
						"id" => "tanvas_style_brand_color",
						"std" => "",
						"type" => "color");
	$options[] = array( "name" =>  __( 'Brand Color 2' ),
						"desc" => __( 'Pick a second color for site branding or add a hex color code e.g. #e6e6e6' ),
						"id" => "tanvas_style_brand_color2",
						"std" => "",
						"type" => "color");
	$options[] = array( "name" =>  __( 'Brand Hover Color' ),
						"desc" => __( 'Pick a custom color for site branding or add a hex color code e.g. #e6e6e6' ),
						"id" => "tanvas_style_brand_hover_color",
						"std" => "",
						"type" => "color");
	$options[] = array( "name" =>  __( 'Brouhore Background' ),
						"desc" => __( 'Pick a custom color for site branding or add a hex color code e.g. #e6e6e6' ),
						"id" => "tanvas_style_brochure_color",
						"std" => "",
						"type" => "color");
    $options[] = array( "name" => __( 'Email Signature' ),
                        "desc" => __( 'Enter the signature used in emails as html.' ),
                        'id' => 'email_signature',
                        'type' => 'textarea');
	return $options;
}

/**
 * Helper function to return the theme option value.
 * If no value has been saved, it returns $default.
 * Needed because options are saved as serialized strings.
 *
 * Not in a class to support backwards compatibility in themes.
 */
if ( ! function_exists( 'of_get_option' ) ) :
function of_get_option( $name, $default = false ) {

	$option_name = '';
	// Get option settings from database
	$options = get_option( 'tanvas' );

	// Return specific option
	if ( isset( $options[$name] ) ) {
		return $options[$name];
	}

	return $default;
}
endif;

include_once('widgets/doorway-button-widget.php');
include_once('widgets/custom-latest-posts-widget.php');
include_once('widgets/custom-social-media-widget.php');
include_once('widgets/woocommerce-my-account-widget.php');
include_once('includes/warnings.php');
include_once('includes/PNG_Reader.php');
include_once('includes/flexSlider_Mods.php');
include_once('includes/extras.php');
include_once('includes/shortcodes.php');
include_once('includes/login-customization.php');
include_once('includes/store-customization.php');

// $woo_options = get_option( 'woo_options' );



// TODO: Allow discounts to be specified based on how many liters of solution

/* pretends to be canvas then quits if woocommerce not installed */
function theme_enqueue_styles(){
	wp_enqueue_style('parent-style', get_template_directory_uri() . '/style.css' );
	wp_enqueue_style('foundation', get_stylesheet_directory_uri() . '/css/foundation.css' );
	wp_enqueue_style('owl.carousel', get_stylesheet_directory_uri() . '/css/owl.carousel.css');
	wp_enqueue_style('owl.theme', get_stylesheet_directory_uri() . '/css/owl.theme.css');
	wp_enqueue_style('design-style', get_stylesheet_directory_uri() . '/css/design-style.css');
	wp_enqueue_style('recent-posts', get_stylesheet_directory_uri() . '/css/recent-posts-widget.css');
	wp_enqueue_style('flexboxgrid', get_stylesheet_directory_uri() . '/css/flexboxgrid.css');
	wp_enqueue_style('tanvas_extra', get_stylesheet_directory_uri() . '/css/extra.css');

	// wp_enqueue_style('this-style', get_stylesheet_uri() );
	wp_enqueue_style( 'prefix-font-awesome', '//netdna.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css', array(), '4.4.0' );
	wp_enqueue_style( 'font-awesome-5', 'https://use.fontawesome.com/releases/v5.0.6/css/all.css', array(), '5.0.6' );
	global $is_IE;
	if ( $is_IE ) {
	    wp_enqueue_style( 'prefix-font-awesome-ie', '//netdna.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome-ie7.min.css', array('prefix-font-awesome'), '4.4.0' );
	    // Add IE conditional tags for IE 7 and older
	    global $wp_styles;
	    $wp_styles->add_data( 'prefix-font-awesome-ie', 'conditional', 'lte IE 7' );
	}
}
add_action('wp_enqueue_scripts', 'theme_enqueue_styles');

// /* enqueue font-awesome */
// function theme_enqueue_scripts(){
//     wp_enqueue_script( 'font-awesome-5', 'https://use.fontawesome.com/releases/v5.0.6/js/all.js', array(), '5.0.6');
// }
// add_action('wp_enqueue_scripts', 'theme_enqueue_scripts');


function Tanvas_noticeWoocommerceNotInstalled() {
    echo
        '<div class="updated fade">' .
        __('Error: Theme "Tanvas" requires WooCommerce to be installed',  'LaserCommerce') .
        '</div>';
}

function Tanvas_WoocommerceCheck() {
    if( !in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
        add_action('admin_notices', 'Tanvas_noticeWoocommerceNotInstalled');
        return false;
    }
    return true;
}

function Tanvas_noticeLasercommerceNotInstalled() {
    echo
        '<div class="updated fade">' .
        __('Error: Theme "Tanvas" requires LaserCommerce to be installed',  'LaserCommerce') .
        '</div>';
}

function Tanvas_LasercommerceCheck() {
    if( !in_array( 'lasercommerce/lasercommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
        add_action('admin_notices', 'Tanvas_noticeLasercommerceNotInstalled');
        return false;
    }
    return true;
}

if(!Tanvas_WoocommerceCheck() or !Tanvas_WoocommerceCheck()) {
	return;
}

/**
 * Demo Store Notice Mods
 */

/* Loads script to move site notice to within wrapper */
add_action('wp_enqueue_scripts', function(){
		wp_enqueue_script(
			'reposition-site-message',
			get_stylesheet_directory_uri().'/js/reposition-site-message.js',
			array('jquery'),
			0.1
		);
	}
);

/* Ensure slider js is loaded */
add_filter('woo_load_slider_js', function($load_slider_js){
	// if(WP_DEBUG) error_log("woo_load_slider_js filter called wit h" . ($load_slider_js?"T":"F"));
	if(is_page_template( 'template-home.php')){
		$load_slider_js = true;
	}
	// if(WP_DEBUG) error_log("woo_load_slider_js returning ". ($load_slider_js?"T":"F"));
	return $load_slider_js;

}, 999, 1);

function tanvas_widgets_init() {
	register_sidebar( array(
		'name' 			=> 'Home Sliders',
		'id' 			=> 'tanvas_home_sliders',
		'before_widget'	=> '<div class="slider-container">',
		'after_widget'	=> '</div>',
		'before_title'	=> '<h5 class="slider-title">',
		'after_title'	=> '</h5>'
	));
	register_sidebar( array(
		'name' 			=> 'Home Products',
		'id' 			=> 'tanvas_home_products',
		'before_widget'	=> '<div class="product-container">',
		'after_widget'	=> '</div>',
		'before_title'	=> '<h5 class="product-title">',
		'after_title'	=> '</h5>'
	));
	register_sidebar( array(
		'name' 			=> 'Home Doorway Buttons',
		'id' 			=> 'tanvas_home_doorway',
		'before_widget'	=> '<div class="doorway-container">',
		'after_widget'	=> '</div>',
		'before_title'	=> '<h5 class="doorway-title">',
		'after_title'	=> '</h5>'
	));
	register_sidebar( array (
		'name' 			=> 'Home Doorway Sidebar',
		'id'			=> 'tanvas_home_doorway_sidebar',
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget'	=> '</div>',
		'before_title'	=> '<h2>',
		'after_title'	=> '</h2>'
	));
	register_sidebar( array (
		'name' 			=> 'Home Below Doorway Left',
		'id'			=> 'tanvas_home_left',
		'before_widget' => '<div class="widget">',
		'after_widget'	=> '</div>',
		'before_title'	=> '<h2>',
		'after_title'	=> '</h2>'
	));
	register_sidebar( array (
		'name' 			=> 'Home Below Doorway Right',
		'id'			=> 'tanvas_home_right',
		'before_widget' => '<div class="widget">',
		'after_widget'	=> '</div>',
		'before_title'	=> '<h2>',
		'after_title'	=> '</h2>'
	));
	register_sidebar( array (
		'name' 			=> 'Home Doorway Bottom',
		'id'			=> 'tanvas_home_doorway_bottom',
		'before_widget' => '<div class="widget-doorway-bottom">',
		'after_widget'	=> '</div>',
		'before_title'	=> '<h2>',
		'after_title'	=> '</h2>'
	));

	register_sidebar(array(
		'name' => __( 'Footer Widgets One', TANVAS_DOMAIN ),
		'id' => 'widget-one',
		'before_widget' => '<div class="footer-wigget">',
		'after_widget' => '</div>',
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>'
	));

	register_sidebar(array(
		'name' => __( 'Footer Widgets Two', TANVAS_DOMAIN ),
		'id' => 'widget-two',
		'before_widget' => '<div class="footer-wigget">',
		'after_widget' => '</div>',
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>'
	));

	register_sidebar(array(
		'name' => __( 'Footer Widgets Three', TANVAS_DOMAIN ),
		'id' => 'widget-three',
		'before_widget' => '<div class="footer-wigget">',
		'after_widget' => '</div>',
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>'
	));

	register_sidebar(array(
		'name' => __( 'Footer Widgets Four', TANVAS_DOMAIN ),
		'id' => 'widget-four',
		'before_widget' => '<div class="footer-wigget">',
		'after_widget' => '</div>',
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>'
	));

	register_sidebar(array(
		'name' => __( 'Footer Widgets Five', TANVAS_DOMAIN ),
		'id' => 'widget-five',
		'before_widget' => '<div class="footer-wigget">',
		'after_widget' => '</div>',
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>'
	));

	register_widget( 'lc_doorway_button');
	register_widget( 'CUSTOM_SOCIAL_MEDIA_WIDGETS' );
	register_widget( 'CUSTOM_LATEST_POSTS_WIDGETS' );
	register_widget( 'WooCommerceMyAccountWidget');

}
add_action('widgets_init', 'tanvas_widgets_init');



/**
 * Adds social media and newsletter icons to nav menu
 */

// function tanvas_add_social_icons () {
// 	echo "<ul><li class='fr fa-facebook'><i class='facebook-official'></i></lu></ul>";
// }
//add_action( 'woo_nav_inside', 'tanvas_add_social_icons', 20);

// function woo_options_add($options){
// 	//if(WP_DEBUG) error_log("woo_options_add called with :".serialize($options));
// 	return $options;
// }

/**
 * Clear Attribute Select box if no available Variations
 */

// do_action( 'woocommerce_before_add_to_cart_form' );
function maybe_clear_attribute_select_box( ) {
	global $product;
	if( isset($product) and $product->is_type('variable')){
		$available_variations = $product->get_available_variations();
		$any_available = False;
		foreach ($available_variations as $variation_data) {
			if(isset($variation_data['variation_is_visible']) and $variation_data['variation_is_visible']){
				$any_available = True;
				//stop output of option box
			}
		}
		if(!$any_available){ ?>
			<p><?php echo __('Please', 'tanvas') . " <a href=''>" . __('sign in', 'tanvas') . "</a> " . __('or', 'tanvas') . " <a href=''>". __('register', 'tanvas'). "</a> ". __("to view prices", 'tanvas') ?></p>
			<style type="text/css">
				form.variations_form.cart{
					display:none;
				}
			</style>
		<?php }
	}
}
add_action('woocommerce_before_add_to_cart_form', 'maybe_clear_attribute_select_box');


add_action( 'init', 'register_my_menu' );
function register_my_menu() {
    register_nav_menu( 'new-menu', __( 'New Menu' ) );
}

/**
 * Change In Stock / Out of Stock Text
 */

// Reversed back to original after consideration
// add_filter( 'woocommerce_get_availability', 'wcs_custom_get_availability', 1, 2);
// function wcs_custom_get_availability( $availability, $_product ) {
//     // Change Out of Stock Text
//     if ( ! $_product->is_in_stock() ) {
// 		$availability['availability'] = __('Coming Soon', 'woocommerce');
//     }
//     return $availability;
// }


/**
 * Woocommerce cart prices notice
 */
function tanvas_output_cart_price_notice(){
	echo do_shortcode(
		'[box type="info"]'.
			__('Dear customer our new cart has just been launched, while we have endeavored to ensure all pricing is correct we reserve the right to revise all pricing in line with our current listed prices. We thank you for your understanding.', TANVAS_DOMAIN).'<br/>'.
		'[/box]'
	);
}
add_action( 'woocommerce_before_cart', 'tanvas_output_cart_price_notice');


//custom excerpt length
function excerpt($limit) {
  $excerpt = explode(' ', get_the_excerpt(), $limit);
  if (count($excerpt)>=$limit) {
    array_pop($excerpt);
    $excerpt = implode(" ",$excerpt).'...';
  } else {
    $excerpt = implode(" ",$excerpt);
  }
  $excerpt = preg_replace('`[[^]]*]`','',$excerpt);
  return $excerpt;
}

/**
 * Remove deprecated constructor warnings
 */
add_filter('deprecated_constructor_trigger_error', '__return_false');

/**
 * Add timestamp to woocommerce order emails
 */

function tanvas_print_timestamp() {
	echo "Timestamp: " . date('r');
	// echo "<br/>";
}

add_action('woocommerce_email_footer', 'tanvas_print_timestamp', 9, 2 );

/* Remove strict password requirements */

function remove_wc_password_meter() {
wp_dequeue_script( 'wc-password-strength-meter' );
}
add_action( 'wp_print_scripts', 'remove_wc_password_meter', 100 );

/* Just looking for all wp image sizes */

function display_wp_image_sizes() {
	global $_wp_additional_image_sizes;
	print '<pre>';
	print_r( $_wp_additional_image_sizes );
	print '</pre>';
}

// add_action( 'the_content', 'display_wp_image_sizes');

?>
