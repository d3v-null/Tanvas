<?php
if ( ! function_exists( 'get_tanvas_theme_options' ) ) {
    /**
     * Get information from Theme Options and add it into wp_head
     */
    function get_tanvas_theme_options(){
        echo '<style type="text/css">';

        global $woo_options;

        if( isset($woo_options['tanvas_style_brand_color'])){
            $brand_color = $woo_options['tanvas_style_brand_color'];
        } else {
            $brand_color = '#d1aa67';
        }
        if ( $brand_color ) {
            $css_statement = implode(",\n", array(
                "div.header-widget div.widget p",
                "div.header-widget div.widget a",
                "div.widget-area.sidebar#tanvas-home-doorway-sidebar div.widget h2",
                "div.widget-area#tanvas_home_right div.widget h2",
                "aside#sidebar div.widget h3",
                "#loopedSlider.business-slider div.content div.title h2.title",
                "#loopedSlider.business-slider div.content div.title h2.title a",
                "table.product-info th",
                "a#doorway-link",
                "a.phone",
                ".main-menu-bottom ul#top-nav li a"
            ));
            $css_statement .= "\n { color: $brand_color !important; }\n";
            $css_statement .= implode(",\n", array(
                "section#footer-widgets",
                "a.shop",
                "span.office-hours",
                ".main-menu",
            ));
            $css_statement .= "\n { background-color: $brand_color !important; }\n";
            $css_statement .= implode(",\n",array(
                "table.product-info th",
                "span.office-hours:before",
                "ul#recent-posts-items .list",
                "ul.contact-us li.border"
            ));
            $css_statement .= "\n { border-color: $brand_color !important; }\n";
            echo $css_statement;
        }

        if( isset($woo_options['tanvas_style_brand_hover_color'])){
            $brand_hover_color = $woo_options['tanvas_style_brand_hover_color'];
        } else {
            $brand_hover_color = '#A5854F';
        }
        if ($brand_hover_color) {
            $css_statement .= "a.shop:hover";
            $css_statement .= " { background-color: $brand_hover_color !important; }\n";
            echo $css_statement;
        }

        echo '</style>';
    }

}


add_action( 'wp_head', 'get_tanvas_theme_options', 10 );

?>
