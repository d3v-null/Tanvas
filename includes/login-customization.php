<?php

/**
 * Log In Mods
 */

/* Forgotten email message */

function tanvas_output_fake_login_div_start(){
    echo "<div class='fake-login'>";
}

function tanvas_output_fake_login_div_end(){
    echo "</div><!-- end of fake-login -->";
}

add_action('login_footer', 'tanvas_output_fake_login_div_start', 4, 0);
add_action('login_footer', 'tanvas_output_fake_login_div_end', 6, 0);


function tanvas_output_login_message($message, $link){
    echo "<p class='under-backtoblog'>";
    echo "<a href='$link'>$message</a>";
    echo "</p>";
}

function tanvas_output_login_forgot_email_message(){
    tanvas_output_login_message(
        __( "Forgot your email?" ),
        "/contact-us"
    );
}

add_action('login_footer', 'tanvas_output_login_forgot_email_message', 5, 0);

/* Login help */

function tanvas_output_login_help(){
    $help_link = get_site_url(0,"/my-account/help");
    tanvas_output_login_message(
        __( "Account help" ),
        $help_link
    );
}

add_action( 'login_footer', 'tanvas_output_login_help', 5, 0);

/* add register link to login */

function tanvas_output_register_link(){
    $register_link = get_site_url(0, "/register/");
    tanvas_output_login_message(
        __( "Create an Account" ),
        $register_link
    );
}

add_action( 'login_footer', 'tanvas_output_register_link', 5, 0);

/* Change registration url */

add_filter( 'register_url', 'custom_register_url' );
function custom_register_url( $register_url )
{
    $register_url = get_permalink( 14493 );
    return $register_url;
}

/**
 * Login Customizations
 */

function my_login_logo() { ?>
    <style type="text/css">
        .login h1 a {
            background-image: url(<?php echo get_stylesheet_directory_uri(); ?>/img/TechnoTan-Logo.png);
            padding-bottom: 30px;
            background-size: 240px;
            width: 240px;
            padding-bottom: 0px;
        }
    </style>
<?php }
add_action( 'login_enqueue_scripts', 'my_login_logo' );

function my_login_stylesheet() {
    wp_enqueue_style( 'custom-login', get_stylesheet_directory_uri() . '/css/style-login.css' );
}
add_action( 'login_enqueue_scripts', 'my_login_stylesheet' );

function my_login_logo_url() {
    return home_url();
}
add_filter( 'login_headerurl', 'my_login_logo_url' );

?>
