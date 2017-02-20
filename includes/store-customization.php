<?php

/**
 * Product Category / Taxonomy Display Mods
 */

/** Add category image to category archive page */

// add_action( 'woocommerce_archive_description', 'woocommerce_category_image', 2 );
function woocommerce_category_image() {
    if ( is_product_category() ){
	    global $wp_query;
	    $cat = $wp_query->get_queried_object();
	    $thumbnail_id = get_woocommerce_term_meta( $cat->term_id, 'thumbnail_id', true );

	    // if( $thumbnail_id ){
	    // 	echo wp_get_attachment_image( $thumbnail_id, 'full' );
	    // }

	    // echo "<h2>" . __("subcategories") . "</h2>";

	    /*$image = wp_get_attachment_url( $thumbnail_id );
	    if ( $image ) {
		    echo '<img src="' . $image . '" alt="" />';
		}*/
	}
}

/** Add log in warning to category **/


/** Removes sort by dropdown **/
remove_action( 'woocommerce_before_shop_loop', 'woocommerce_catalog_ordering', 30 );


/** allow html in category / tax descriptions */

foreach ( array( 'pre_term_description' ) as $filter ) {
    remove_filter( $filter, 'wp_filter_kses' );
}

foreach ( array( 'term_description' ) as $filter ) {
    remove_filter( $filter, 'wp_kses_data' );
}

/* add category description after subcategory title */

// add_action('woocommerce_before_subcategory', function($category){
// 	if(is_product_category() && !woocommerce_products_will_display()){
// 		echo "<style>body.archive.tax-product_cat ul.products { display: table; } </style>";
// 	}
// });

// add_action('woocommerce_before_subcategory_title', function($category){

// 	if(is_product_category() && !woocommerce_products_will_display()){
// 		echo '<div class="product-category-description">';
// 	}
// });

// add_action('woocommerce_after_subcategory_title', function($category){

// 	if(is_product_category() && !woocommerce_products_will_display()){
// 		$desc = esc_attr($category->description);
// 		echo "<p>$desc</p>";
// 		echo '</div> <!-- end product-category-description-->';
// 	}
// });

// add_filter(
// 	'loop_shop_columns',
// 	function($cols){
// 		if(is_product_category() && !woocommerce_products_will_display()){
// 			return 1;
// 		} else {
// 			return $cols;
// 		}
// 	},
// 	999,
// 	1
// );


/**
 * Dynamic pricing customization
 */

// function tanvas_remove_dynamic_cumulative( $default, $module_id, $cart_item, $cart_item_key){
// 	// error_log("tanvas dynamic cumulative: ");
// 	// error_log(" -> def: ".serialize($default));
// 	// error_log(" -> mod: ".serialize($module_id));
// 	// error_log(" -> car: ".serialize($cart_item));
// 	// error_log(" -> cak: ".serialize($cart_item_key));
// 	return $default;
// }

// add_filter('woocommerce_dynamic_pricing_is_cumulative', 'tanvas_remove_dynamic_cumulative', 10, 4);

/**
 * change loop shop columns
 */
// THIS IS NOT NEEDED NOW THAT WE USE PRODUCT ARCHIVE CUSTOMIZER
// if (!function_exists('change_loop_columns')) {
// 	function change_loop_columns() {
// 		return 3; // 3 products per row
// 	}
// }
// add_filter('loop_shop_columns', 'change_loop_columns', 999, 3);

// change the css if there are 3 columns
// function inject_column_css(){
// 	// if(WP_DEBUG) error_log("called inject_column_css callback");
// 	$columns = apply_filters( 'loop_shop_columns', 3);
// 	// if(WP_DEBUG) error_log("-> columns: $columns");
// 	if ($columns == 3 ){
// 		<!-- <style type="text/css">
// 		ul.products li.product {
// 			width: 30%;
// 		}
// 		</style> -->
// 	  }
// }
// add_action('woocommerce_before_shop_loop', 'inject_column_css', 999, 0);
/**
 * title customizations for products in category
 */

function shrink_product_title($title, $id){
	// if(WP_DEBUG) error_log("called shrink_product_title callback | title: $title, id: $id");
	if(is_product_category() && !is_admin()){
		$title_length = strlen($title);
		$title = preg_replace("/^(.*)( &#8212; | - | &#8211; | — )(.*)$/u", '<span>$1</span><small>$3</small>', $title );
		if ($title_length > 64){
			$title = "<span class='long-title'>".$title."</span>";
		}
	}
	// if(WP_DEBUG) error_log("-> returning $title");
	return $title;
}
add_action('the_title', 'shrink_product_title', 9999, 2);


/*
 * wc_remove_related_products
 *
 * Clear the query arguments for related products so none show.
 * Add this code to your theme functions.php file.
 */
// function wc_remove_related_products( $args ) {
// 	return array();
// }
// add_filter('woocommerce_related_products_args','wc_remove_related_products', 10);

//TODO: show "available in ..." on variable product page
//TODO: Change "Select Options" and "READ MORE" to "VIEW" when product is unavailable

?>
