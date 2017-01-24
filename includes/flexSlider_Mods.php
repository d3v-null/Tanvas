<?php
  // registers js for adding newtab on sliders
  // registers js for newtab option in slider admin
  // registers js for slider begin/end in admin
  // outputs slider meta in html data entity for newtab mod
  // outputs js for newtab mod
  // deletes slides that have expired, adds scheduled sliders

// add_action( 'admin_head','woo_options' );

// if(TANVAS_DEBUG) error_log("TANVAS woo_custom_template:".serialize(get_option('woo_custom_template')));

function tanvas_add_newtab_metabox_field($metaboxes){
  if ( get_post_type() == "slide" ) {
    $metaboxes[] = array (  "name" => "newtab",
                            "label" => "Open in new tab",
                            "type" => "checkbox",
                            "desc" => "Opens the slider link in a new tab");
  }
  return $metaboxes;
}

add_filter('tanvas_metaboxes', 'tanvas_add_newtab_metabox_field');

//gets a list of slideIDs that have been elected to have their links open in a newtab
function tanvas_get_newtab_slides(){
  $query_args = array(
    'post_type' => 'slide' ,
    'meta_key' => 'newtab',
    'meta_value' => 'true',
  );

  $slides = false;

  $query = get_posts( $query_args );

  if ( ! is_wp_error( $query ) && ( 0 < count( $query ) ) ) {
    $slides = $query;
  }

  if(TANVAS_DEBUG) error_log("flexslider mods: newtab slides:".serialize($slides));

  return $slides;
}

function tanvas_load_flexslider_mod_js_footer(){
  if(TANVAS_DEBUG) error_log("flexslider mods: loading js");
  $newtab_slides = tanvas_get_newtab_slides();
  if($newtab_slides){
    wp_enqueue_script(
      'override-flexslider-target',
      wp_make_link_relative(get_stylesheet_directory_uri(). '/js/override-flexslider-target.js') ,
      // plugin_dir_url( __FILE__ ) . 'js/overwrite-flexslider-target.js',
      array('jquery'),
      false,
      true
    );
    $target_overrides = array();
    foreach ($newtab_slides as $post) {
    $target_overrides[$post->ID] = '_blank';
    }
    wp_localize_script(
      'override-flexslider-target',
      'flexslider_target_params',
      array('target_overrides' => $target_overrides)
    );
  }
}

function tanvas_check_load_slider(){
  global $woo_options;

  $load_slider_js = false;

  if ( ( is_page_template( 'template-biz.php' ) && isset( $woo_options['woo_slider_biz'] ) && $woo_options['woo_slider_biz'] == 'true' ) ||
     ( is_page_template( 'template-magazine.php' ) && isset( $woo_options['woo_slider_magazine'] ) && $woo_options['woo_slider_magazine'] == 'true' ) ||
       is_page_template( 'template-widgets.php' ) ||
       is_active_sidebar( 'homepage' ) ) { $load_slider_js = true; }

  // Allow child themes/plugins to load the slider JavaScript when they need it.
  $load_slider_js = (bool)apply_filters( 'woo_load_slider_js', $load_slider_js );

  if($load_slider_js){
    // tanvas_make_scheduled_slide_changes();
    tanvas_load_flexslider_mod_js_footer();
  }
}

add_action('woo_head', 'tanvas_check_load_slider', 9);

function tanvas_change_woo_slider_width($slider_args){
    $slider_args['width'] = '1200';
    return $slider_args;
}

add_filter('woo_slider_biz_view_args', 'tanvas_change_woo_slider_width');

// $view_args = apply_filters( 'woo_slider_biz_view_args', $view_args );


?>
