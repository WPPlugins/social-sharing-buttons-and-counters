<?php

if (!defined('JCSS_PLUGIN_DIR')) {
	header('HTTP/1.1 403 Forbidden', true, 403);
	exit;
}

function jcss_social_buttons() {
    
    $options = jcss_get_buttons_options();
    $counters = jcss_get_counters_options();
    
    $title = urlencode(html_entity_decode(get_the_title()));
    $url = get_permalink();
    
    $socials =  explode(',', $options['social_options']);
    $twitter_username = $options['twitter_username']; 

    ob_start();
    ?>
       
    <div id = "jcss-social-buttons">
        <?php
        if (!empty($options['sharing_text']) && !empty($options['sharing_text_position']) && $options['sharing_text_position']==="above") {
            echo jcss_get_sharing_text($options, "jcss-above-buttons");
        }
        ?>    
        <div id = "jcss-buttons-container">
        <?php 
        if (!empty($options['sharing_text']) && !empty($options['sharing_text_position']) && $options['sharing_text_position']==="left") {
            echo jcss_get_sharing_text($options, "jcss-left-buttons");
        } 
        foreach ($socials as $social) {
            switch ($social) {
                case "Facebook": ?>
                    <a id="jcss-facebook" rel="external nofollow" class="jcss-button" href="http://www.facebook.com/sharer.php?u=<?php echo $url; ?>" target="_blank" >
                        <?php jcss_get_social_name($options, $social); jcss_get_shares_count($counters, $social, $url)  ?>
                    </a>  <?php
                break;
                case "Twitter": ?>
                    <a id="jcss-twitter" rel="external nofollow" class="jcss-button" href="http://twitter.com/intent/tweet/?text=<?php echo $title; ?>&url=<?php echo $url; if(!empty($twitter_username)) { echo '&via=' . $twitter_username; } ?>" target="_blank" >
                        <?php jcss_get_social_name($options, $social); jcss_get_shares_count($counters, $social, $url)  ?>
                    </a>  <?php
                break;
                case "Google+": ?>
                    <a id="jcss-googleplus" rel="external nofollow" class="jcss-button" href="https://plus.google.com/share?url=<?php echo $url; ?>" target="_blank" >
                        <?php jcss_get_social_name($options, $social); jcss_get_shares_count($counters, $social, $url)  ?>
                    </a>  <?php
                break;
                case "LinkedIn": ?>
                    <a id="jcss-linkedin" rel="external nofollow" class="jcss-button" href="https://www.linkedin.com/shareArticle?mini=true&url=<?php echo $url; ?>&title=<?php echo $title; ?>" target="_blank" >
                        <?php jcss_get_social_name($options, $social); jcss_get_shares_count($counters, $social, $url)  ?>
                    </a>  <?php
                break;
                case "WhatsApp": ?>
                    <a id="jcss-whatsapp" rel="external nofollow" class="jcss-button" href="whatsapp://send?text=<?php echo $title.' – '.$url ?>" data-action = "share/whatsapp/share" target="_blank" >
                        <?php jcss_get_social_name($options, $social); jcss_get_shares_count($counters, $social, $url)  ?>
                    </a>  <?php
                break;                
            } 
        }  ?>
        </div>
    </div>
    <?php
    $html = ob_get_contents();
    ob_end_clean();
        
    return $html;
}         

function jcss_shares_count( $atts, $content = null ) 
{     
    $shares_count = 0;
    
	if( isset($atts['social'])) 
    {
        $url = '';
        if (isset($atts['url']))
        {
            $url = $atts['url']; 
        }
        else
        {
            $url = get_permalink();
        }
        $social = trim(strtolower($atts['social']));        
        
        switch ($social) {
            case "facebook":
                    $shares_count = jcss_get_facebook_shares($url); break;        
            case "google-plus":
            case "googleplus":
            case "google+":
                    $shares_count = jcss_get_google_plus_shares($url); break;
            case "linkedin":
                    $shares_count = jcss_get_linkedin_shares($url); break;
            case "twitter":
                    $shares_count = jcss_get_twitter_shares($url); break;
        } 
    }
    return $shares_count;
}