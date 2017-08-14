<?php

if (!defined('JCSS_PLUGIN_DIR')) {
	header('HTTP/1.1 403 Forbidden', true, 403);
	exit;
}

function jcss_admin_page() { ?>

    <div class="wrap">     

        <h2> Social sharing Buttons and Counters </h2>

        <?php $active_tab = isset( $_GET[ 'tab' ] ) ? $_GET[ 'tab' ] : 'buttons'; ?>

        <h2 class="nav-tab-wrapper">
            <a href="?page=social-sharing-buttons-and-counters&tab=buttons" class="nav-tab <?php echo $active_tab == 'buttons' ? 'nav-tab-active' : ''; ?>">
                <?php _e('Buttons', 'social-sharing-buttons-and-counters'); ?>
            </a>
            <a href="?page=social-sharing-buttons-and-counters&tab=counters" class="nav-tab <?php echo $active_tab == 'counters' ? 'nav-tab-active' : ''; ?>"> 
                <?php _e('Counters', 'social-sharing-buttons-and-counters'); ?>
            </a>
            <a href="?page=social-sharing-buttons-and-counters&tab=shortcodes" class="nav-tab <?php echo $active_tab == 'shortcodes' ? 'nav-tab-active' : ''; ?>"> 
                Shortcodes
            </a>
        </h2>

            <?php if ( $active_tab == 'buttons' ) { 
            $options = jcss_get_buttons_options();
                    
            ?>         
            <form action="options.php" method="post">  
                <?php       
                    settings_fields( 'jcss_plugin_options' );
                    @do_settings_fields('jcss_plugin_options'); 
                ?>
                <table class="form-table">			
                    <tbody>
                        <tr>
                            <th scope="row"><label for="social_options"><?php _e('Social buttons', 'social-sharing-buttons-and-counters'); ?></label></th>
                            <td>
                                <div id="social-list"> 
                                    <ul class="sortable connectable">                   
                                        <?php echo jcss_get_social_list( $options['social_options'], false ) ?>
                                    </ul>
                                </div>  

                                <p class="description" id="social_options_description"><?php _e('Drop below the sharing buttons you want to add', 'social-sharing-buttons-and-counters'); ?> </p>

                                <div id="social-selected" > 
                                    <ul class="sortable connectable">                         
                                        <?php echo jcss_get_social_list( $options['social_options'], true ) ?>
                                    </ul>
                                </div>                    

                                <div id="twitter-username">
                                    <h4><label for="twitter-username"><?php _e('Twitter username', 'social-sharing-buttons-and-counters')?> </label></h4>
                                        <input id="twitter-username" type="text" name="jcss_buttons_options[twitter_username]" 
                                         placeholder="<?php _e('username without @', 'social-sharing-buttons-and-counters') ?>" value="<?php echo esc_attr($options['twitter_username']) ?>">
                                        <p class="description"> <?php _e('Enter your username if you wanted it to be appended to the tweets', 'social-sharing-buttons-and-counters') ?></p>

                                </div>

                                <input type="hidden" id="social-options" name="jcss_buttons_options[social_options]" value="<?php echo $options['social_options'] ?>"/>
                            </td>
                        </tr>

                        <tr>
                            <th scope="row">
                            <label for="buttons-text"><?php _e('Buttons text ', 'social-sharing-buttons-and-counters'); ?></label>
                            </th>
                            <td class="jcss-radio">
                                <label><input type="radio" name="jcss_buttons_options[display_names]" value="1" <?php checked($options['display_names'], 1); ?> > <?php _e('Yes', 'social-sharing-buttons-and-counters'); ?></label>
                                <label><input type="radio" name="jcss_buttons_options[display_names]" value="0" <?php checked($options['display_names'], 0); ?> > <?php _e('No'); ?></label> 
                                <p class="description"> <?php _e('Display social network names? You can hide them if you don\'t have enough room for the buttons', 'social-sharing-buttons-and-counters') ?></p>                
                            </td>				             
                        </tr>

                        <tr>
                            <th scope="row">
                                <label for="buttons-location"><?php _e('Buttons location', 'social-sharing-buttons-and-counters') ?></label>
                            </th>
                            <td>
                                <select id="buttons-location" name="jcss_buttons_options[placement]">
                                    <option value="before" <?php selected($options['placement'], 'before') ?> ><?php _e('Before content', 'social-sharing-buttons-and-counters') ?></option>
                                    <option value="after" <?php selected($options['placement'], 'after'); ?> ><?php _e('After content', 'social-sharing-buttons-and-counters') ?></option>
                                </select>
                            </td>		
                        </tr>

                        <tr>
                            <th scope="row">
                                <label for="add-to"><?php _e('Add buttons to', 'social-sharing-buttons-and-counters') ?></label>
                            </th>
                            <td>
                                <ul>
                                    <?php $post_types = get_post_types(array( 'public' => true ), 'objects'); 
                                        foreach ($post_types as $post_type_id => $post_type): ?>
                                        <li>
                                            <label>
                                                <input type="checkbox" name="jcss_buttons_options[post_types][]" value="<?php echo esc_attr( $post_type_id ); ?>" 
                                                <?php checked( in_array( $post_type_id, $options['post_types'] ), true ) ?>> <?php echo $post_type->labels->name; ?>
                                            </label>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>						
                            </td>				
                        </tr>      

                        <tr>
                            <th scope="row">
                                <label for="sharing-text"><?php _e('Sharing text', 'social-sharing-buttons-and-counters') ?></label>
                            </th>
                            <td>
                                <input id="sharing-text" type="text" name="jcss_buttons_options[sharing_text]" 
                                     placeholder="<?php _e('Share this!', 'social-sharing-buttons-and-counters') ?>" value="<?php echo esc_attr($options['sharing_text'])?>">
                                <p class="description"> <?php _e("Left the field empty if you don't want to display a text before the sharing buttons", 'social-sharing-buttons-and-counters') ?></p>

                            </td>
                        </tr>

                        <tr>
                            <th scope="row">
                                <label for="sharing-text-position"><?php _e('Sharing text position', 'social-sharing-buttons-and-counters') ?></label>
                            </th>
                            <td>
                                <select id="sharing-text-position" name="jcss_buttons_options[sharing_text_position]">
                                    <option value="left" <?php selected($options['sharing_text_position'], left) ?> ><?php _e('Left', 'social-sharing-buttons-and-counters') ?></option>
                                    <option value="above" <?php selected($options['sharing_text_position'], above); ?> ><?php _e('Above', 'social-sharing-buttons-and-counters') ?></option>
                                </select>
                            </td>
                        </tr> 
                        <tr>
                            <th scope="row">      
                                <label for="sharing-text-weight"><?php _e('Sharing text weight', 'social-sharing-buttons-and-counters') ?></label>
                            </th>
                            <td>
                                <select id="sharing-text-weight" name="jcss_buttons_options[sharing_text_weight]">
                                    <?php for ($i = 100; $i <= 900; $i+=100) { ?>
                                        <option value="<?php echo $i ?>" <?php selected($options['sharing_text_weight'], $i) ?> > <?php echo $i ?></option>
                                    <?php } ?>
                                </select>
                            </td>
                        </tr>
                    </tbody>  
                </table>
                <?php submit_button(); ?>             
            </form> <?php 
            } else if ( $active_tab == 'counters' ) { 
            $options = jcss_get_counters_options(); ?>
            <form action="options.php" method="post">         
                <?php
                    settings_fields( 'jcss_plugin_options' );
                    @do_settings_fields('jcss_plugin_options');
                ?>        
                <table class="form-table">			
                    <tbody>
                        <tr>
                            <th scope="row"><label for="shares-count"><?php _e('Display shares count?', 'social-sharing-buttons-and-counters')?> </label></th>
                            <td id="shares-count" class="jcss-radio">
                                <label><input type="radio" name="jcss_counters_options[display]" value="1" <?php checked($options['display'], 1); ?> > <?php _e('Yes', 'social-sharing-buttons-and-counters'); ?></label>
                                <label><input type="radio" name="jcss_counters_options[display]" value="0" <?php checked($options['display'], 0); ?> > <?php _e('No'); ?></label> 

                                <p class="description" class="widefat"> 
                                <?php _e("<strong> Facebook shares counter: </strong> This counter will be updated every hour.", 'social-sharing-buttons-and-counters') ?></p>

                                <p class="description" class="widefat">                     
                                <?php _e('<strong> Twitter shares counter: </strong> On November 2015 Twitter took the shares count away from their API. This plugin uses the newsharecounts API, as most developers and WordPress plugins do.', 'social-sharing-buttons-and-counters') ?></p>                    
                                <p class="description" class="widefat"><?php _e('<strong> Important! How to turn the Twitter shares count ON </strong> If you want to turn the twitter shares count ON you need to go to the <a href="http://newsharecounts.com/" target="_blank"> newsharecounts page </a> and enter your website. Once you have followed the instructions the shares count for you website will be tracked. Easy as pie! (It may take a few days for everything to catch up and syncronize)', 'social-sharing-buttons-and-counters') ?></p>

                                <p class="description" class="widefat"> 
                                <?php _e("<strong> WhatsApp shares counter: </strong> There is not an official way to track the WhatsApp shares so we're not offering this functionality now. We are working to add this functionality as soon as we can", 'social-sharing-buttons-and-counters') ?></p>                                                                                 
                            </td>
                        </tr>
                        <tr>
                            <th scope="row">
                                <label for="buttons-location"><?php _e('Counter style', 'social-sharing-buttons-and-counters') ?></label>
                            </th>
                            <td>
                                <select id="buttons-location" name="jcss_counters_options[style]">
                                    <option value="normal" <?php selected($options['style'], normal) ?> ><?php _e('Normal', 'social-sharing-buttons-and-counters') ?></option>
                                    <option value="box" <?php selected($options['style'], box); ?> ><?php _e('Box-like', 'social-sharing-buttons-and-counters') ?></option>
                                </select>
                            </td>		                        	
                        </tr>           
                    </tbody>
                </table>
                <?php submit_button(); ?>    
            </form> <?php
            } else { ?>
                <h3>[jc_buttons] </h3>
                <p><?php _e('The shortcode <strong>[jc_buttons]</strong> will render the sharing buttons as you have customised them in this page. Thanks to this shortcode you can place the buttons anywhere!', 'social-sharing-buttons-and-counters') ?> </p>
                <h3>[jc_shares] </h3>
                <p><?php _e('The shortcode <strong>[jc-shares social=xxxx]</strong> will render the actual post/page shares count on the social network passed in (facebook, twitter, linkedin, googleplus, google-plus). You can also render any URL shares count by using the url parameter. For example, you can type in the WP editor: "The http://whatever-page.com page has been shared [jc_shares social=xxxx url=http://whatever-page.com] times. Here are some examples for a random WordPress\'s blog post:', 'social-sharing-buttons-and-counters') ?> </p>
                <p>[jc_shares social=facebook url="https://es.wordpress.org/2016/12/06/wordpress-4-7-vaughan/"] <?php _e('renders 35 (as of the plugin development date)', 'social-sharing-buttons-and-counters') ?></p>
                <p>[jc_shares social=twitter url="https://es.wordpress.org/2016/12/06/wordpress-4-7-vaughan/"] <?php _e('renders 29 (as of the plugin development date)', 'social-sharing-buttons-and-counters') ?></p>
                <p>[jc_shares social=linkedin url="https://es.wordpress.org/2016/12/06/wordpress-4-7-vaughan/"] <?php _e('renders 6 (as of the plugin development date)', 'social-sharing-buttons-and-counters') ?></p>
                <p>[jc_shares social=googleplus url="https://es.wordpress.org/2016/12/06/wordpress-4-7-vaughan/"] <?php _e('renders 3 (as of the plugin development date)', 'social-sharing-buttons-and-counters') ?></p> <?php
            } ?>         
    </div> <?php
}