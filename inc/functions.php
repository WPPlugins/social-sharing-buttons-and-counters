<?php

if (!defined('JCSS_PLUGIN_DIR')) {
	header('HTTP/1.1 403 Forbidden', true, 403);
	exit;
}

define( 'JCSS_VERSION', '1.1.0' );

function jcss_get_default_buttons_options() {
	
	$default_options = array();
    
	try {				
		$default_options = array(
			'social_options' => 'Facebook,Twitter',
            'post_types' => array ( 'post' ),
            'placement' => 'after',
            'display_names' => 1,  
            'display_shares' => 1,
            'twitter_username' => '',                      
            'sharing_text' => '',
            'sharing_text_position' => 'left',
            'sharing_text_weight' => 500
		);
	
	} catch( Exception $e) {				
	}	
	return $default_options;		
}

function jcss_get_default_counters_options() {
	
	$default_options = array();
    
	try {				
		$default_options = array(  
            'display' => 1,
            'style' => 'normal'
		);
	
	} catch( Exception $e) {				
	}	
	return $default_options;		
}

function jcss_get_buttons_options() {
	$options = array(); 
	
	try {
		$options = get_option('jcss_buttons_options') ? get_option('jcss_buttons_options') : jcss_get_default_buttons_options();
	
	} catch( Exception $e ) {	
	}
	
	return $options;	
}

function jcss_get_counters_options() {
	$options = array(); 
	
	try {
		$options = get_option('jcss_counters_options') ? get_option('jcss_counters_options') : jcss_get_default_counters_options();
	
	} catch( Exception $e ) {	
	}
	return $options;
}

function jcss_get_facebook_shares($url)
{
    $result = 0;    
    $transient_facebook = get_transient('facebook' . $url);
    
    if (!$transient_facebook)
    {    
        $response = wp_remote_get( 'http://graph.facebook.com/?id=' . $url);

        if ( is_array( $response ) && ! is_wp_error( $response ) )
        {
            $body = json_decode($response['body'], true);         
            $result = isset($body['share']['share_count']) ? $body['share']['share_count'] : 0;
            set_transient('facebook' . $url , $result , 60 * MINUTE_IN_SECONDS);
        }
        return $result;
    }
    else 
    {
        return $transient_facebook;
    }
}

function jcss_get_google_plus_shares($url)
{
    $result = 0;
    
    $post = array (
        'method' => 'POST',
        'headers' => array(
            'Content-Type' => 'application/json'
        ),
        'body' => json_encode(array(
                'method' => 'pos.plusones.get',
                'id' => 'p',
                'params' => array(
                    'nolog'=>true,
                    'id'=> $url,
                    'source'=>'widget',
                    'userId'=>'@viewer',
                    'groupId'=>'@self'
                ),
                'jsonrpc' => '2.0',
                'key' => 'p',
                'apiVersion' => 'v1'            
            )),
        'sslverify'=>false
    );
    $response = wp_remote_post( 'https://clients6.google.com/rpc' , $post);
    
    if ( is_array( $response ) && ! is_wp_error( $response ) )  
    {
        $body = json_decode($response['body'], true); 
        $result = isset($body['result']['metadata']['globalCounts']['count']) ? $body['result']['metadata']['globalCounts']['count'] : 0;
    }
    
    return $result;
}

function jcss_get_linkedin_shares($url)
{
    $result = 0;    
    $response = wp_remote_get('http://www.linkedin.com/countserv/count/share?url=' . $url);
    
    if ( is_array( $response ) && ! is_wp_error( $response ) )  
    {
        $response = str_replace(array('IN.Tags.Share.handleCount(', ');'), '', $response);
        $body = json_decode($response['body'], true); 
        
        $result = isset($body['count']) ? $body['count'] : 0;
    }
    return $result;
}

function jcss_get_shares_count($counters, $social, $url) 
{
    if (!empty($counters['display']) && $social !== "WhatsApp") { 
        $shares = jcss_shares_count(array('social' => $social, 'url' => $url, 'echo' => false));
        if ($shares > 0) { ?>
            <span class="jcss-shares<?php if ($counters['style'] === "box") echo " jcss-shares-box"?>"> <?php echo $shares ?> </span><?php
        }
    }
}

function jcss_get_sharing_text($options, $element_id) 
{
    ?>
    <div id="<?php echo $element_id ?>">
        <span
            <?php if (!empty($options['sharing_text_weight'])):?> style="font-weight:<?php echo $options['sharing_text_weight']?>" <?php endif; ?>>
            <?php echo $options['sharing_text'] ?>
        </span>       
    </div>
    <?php        
}

function jcss_get_social_name($options, $name) 
{
    if (!empty($options['display_names'])) { ?> <span class="jcss-social-name"><?php echo $name; ?></span><?php } 
}

function jcss_get_twitter_shares($url)
{
    $result = 0;
    $response = wp_remote_get('http://public.newsharecounts.com/count.json?url=' . $url);
    
    if ( is_array( $response ) && ! is_wp_error( $response ) )  
    {
        $body = json_decode($response['body'], true); 
        $result = isset($body['count']) ? $body['count'] : 0;
    }
    return $result;
}

function jcss_get_social_list( $values, $include_values ) 
{
    $socials = array('Facebook', 'Twitter', 'Google+', 'LinkedIn', 'WhatsApp');
    $values_array = explode(',', $values);

    $html = '';
    if ($include_values)
    {
        foreach ($values_array as &$value) 
        {    
            if (in_array($value, $socials))  
                $html .= '<div id="'.$value.'" class="social-list-item"><li><span class="jcss-card">'.$value.'</span></li></div>';       
        }
    }
    else
    {    
        foreach ($socials as &$social)
        {
            if (!in_array($social, $values_array) )
                $html .= '<div id="'.$social.'" class="social-list-item"><li><span class="jcss-card">'.$social.'</span></li></div>'; 
        }
    }
    return $html;
}

function jcss_sanitize_buttons($input)
{
    $options = jcss_get_buttons_options();
    
    if( isset( $input['social_options'] ) ) $options['social_options'] = sanitize_text_field( $input['social_options'] );     
    if( isset( $input['post_types'] ) ) $options['post_types'] = array_map( function( $val ) { return sanitize_text_field( $val );}, $input['post_types']  );
    if( isset( $input['placement'] ) ) $options['placement'] = sanitize_text_field( $input['placement'] );     
    if( isset( $input['display_names'] ) ) $options['display_names'] = sanitize_text_field( $input['display_names'] );
    if( isset( $input['display_shares'] ) ) $options['display_shares'] = sanitize_text_field( $input['display_shares'] );     
    if( isset( $input['twitter_username'] ) ) $options['twitter_username'] = sanitize_text_field( $input['twitter_username'] );
    if( isset( $input['sharing_text'] ) ) $options['sharing_text'] = sanitize_text_field( $input['sharing_text'] );     
    if( isset( $input['sharing_text_position'] ) ) $options['sharing_text_position'] = sanitize_text_field( $input['sharing_text_position'] );
    if( isset( $input['sharing_text_weight'] ) ) $options['sharing_text_weight'] = sanitize_text_field( $input['sharing_text_weight'] );
    
    return $options;
}

function jcss_sanitize_counters($input)
{
    $options = jcss_get_counters_options();
 
    if( isset( $input['display'] ) ) $options['display'] = sanitize_text_field( $input['display'] );     
    if( isset( $input['style'] ) )$options['style'] = sanitize_text_field( $input['style'] );

    return $options;
}