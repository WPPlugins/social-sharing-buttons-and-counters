<?php
/*

Plugin Name: Social Sharing Buttons and Counters
Description: A lightweight SEO-friendly plugin that allows you to share your posts and get more traffic
Version:     1.1.0
Author:      Jose Carlos Román Rubio
Author URI:  https://josecarlosroman.com/wordpress
Text Domain: social-sharing-buttons-and-counters
Domain Path: /languages
License:     GPL3
 
Social Sharing Buttons and Counters is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 2 of the License, or
any later version.
 
Social Sharing Buttons and Counters is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.
 
You should have received a copy of the GNU General Public License
along with Social Sharing Buttons and Counters. If not, see https://www.gnu.org/licenses/gpl.html.
*/

if ( !defined('ABSPATH') ) {
	header('HTTP/1.1 403 Forbidden', true, 403);
	exit;
}

define('JCSS_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('JCSS_PLUGIN_URL', plugin_dir_url(__FILE__));

require_once JCSS_PLUGIN_DIR . 'inc/functions.php';
require_once JCSS_PLUGIN_DIR . 'inc/template-functions.php';
require_once JCSS_PLUGIN_DIR . 'inc/plugin.php';  

if ( is_admin() )
{
    require_once JCSS_PLUGIN_DIR . 'inc/admin.php';
}

register_uninstall_hook(__FILE__, 'jcss_uninstall');