<?php

if (!defined('JCSS_PLUGIN_DIR')) {
	header('HTTP/1.1 403 Forbidden', true, 403);
	exit;
}

function stylesheet_installed($array_css)
{
    global $wp_styles;
    
    foreach( $wp_styles->queue as $style ) 
    {
        foreach ($array_css as $css)
        {
            if (false !== strpos( $wp_styles->registered[$style]->src, $css ))
            {
                return 1;
            }
        }
    }
    return 0; 
}

function jcss_scripts()
{
    /* enqueue styles to head, font-awesome only if wasn't enqueued before */
    $font_awesome = array('font-awesome', 'fontawesome');        
    if (stylesheet_installed($font_awesome) === 0)
    {
        wp_enqueue_style('jcss-font-awesome', '//cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css'); 
    }      
    wp_enqueue_style('jcss-styles', JCSS_PLUGIN_URL . 'assets/css/jc-social-sharing.css', array(), JCSS_VERSION);

    /* enqueue scripts to body */
    wp_register_script('jcss-javascript', JCSS_PLUGIN_URL . 'assets/js/jc-social-sharing.js', array('jquery'), JCSS_VERSION, true);	
	wp_enqueue_script('jcss-javascript');    
}
add_action( 'wp_enqueue_scripts', 'jcss_scripts', 999 );


function jcss_register_shortcodes() {
	
	add_shortcode( 'jc_buttons', 'jcss_social_buttons' );
    add_shortcode( 'jc_shares', 'jcss_shares_count' );
	
}
add_action( 'init', 'jcss_register_shortcodes');


function jcss_add_social_buttons($content) 
{    
    $options = jcss_get_buttons_options();   
    $jcss_content = $content;
    
    if( !empty( $options['post_types'] ) && in_array( get_post_type(), $options['post_types'] ) && is_singular( $options['post_types'] ) ) 
    {
        if ( !empty( $options['placement']) && $options['placement'] === "before")
            $jcss_content = jcss_social_buttons() . $content;
        else
            $jcss_content .= jcss_social_buttons();              
    }
    return $jcss_content;
}
add_filter('the_content', 'jcss_add_social_buttons');


function jcss_load_plugin_textdomain() 
{
    load_plugin_textdomain( 'social-sharing-buttons-and-counters', false, 'social-sharing-buttons-and-counters/languages/' );
}
add_action( 'plugins_loaded', 'jcss_load_plugin_textdomain' );