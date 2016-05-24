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
    $metaboxes[] = array (  "name" => "custom",
                            "label" => "Custom field",
                            "type" => "text",
                            "desc" => "description");
  }
  return $metaboxes;
}

add_filter('tanvas_metaboxes', 'tanvas_add_newtab_metabox_field');

function tanvas_load_flexslider_mod_js_footer(){
  if(TANVAS_DEBUG) error_log("flexslider mods: loading js");
  wp_enqueue_script(
    'override-flexslider-target',
    wp_make_link_relative(get_stylesheet_directory_uri(). '/js/override-flexslider-target.js') ,
    // plugin_dir_url( __FILE__ ) . 'js/overwrite-flexslider-target.js',
    array('jquery'),
    false,
    true
  );
  wp_localize_script(
    'override-flexslider-target',
    'flexslider_target_params',
    array('target_overrides' => array(
      '10712' => '_blank'
    ) )
  );
}

function tanvas_maybe_load_flexslider_mod_js_footer(){
  global $woo_options;

  $load_slider_js = false;

  if ( ( is_page_template( 'template-biz.php' ) && isset( $woo_options['woo_slider_biz'] ) && $woo_options['woo_slider_biz'] == 'true' ) ||
     ( is_page_template( 'template-magazine.php' ) && isset( $woo_options['woo_slider_magazine'] ) && $woo_options['woo_slider_magazine'] == 'true' ) ||
       is_page_template( 'template-widgets.php' ) ||
       is_active_sidebar( 'homepage' ) ) { $load_slider_js = true; }

  // Allow child themes/plugins to load the slider JavaScript when they need it.
  $load_slider_js = (bool)apply_filters( 'woo_load_slider_js', $load_slider_js );

  if($load_slider_js){
    tanvas_load_flexslider_mod_js_footer();
  }
}

add_action('woo_head', 'tanvas_maybe_load_flexslider_mod_js_footer', 9);


?>
