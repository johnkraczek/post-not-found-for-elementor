<?php
/**
 * Plugin Name: Post Not Found for Elementor Pro
 * Description: This plugin adds an interface to elementor pro that allows you to select a template to show when a list of posts is displayed and none are found.
 * Plugin URI: https://yourdigitaltoolbox.com/
 * Version: 0.1.0
 * Author: John Kraczek
 * Author URI: http://yourdigitaltoolbox.com/
 * Text Domain: 'post-not-found-for-elementor'
 * Min PHP Version: 7.0
 * Min Elementor Pro Version: 3.0.0
 * License: GNU General Public License v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 *
 * Post Not Found for Elementor Pro is free software: you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation, either version 2 of the License, or any later version.

 * Post Not Found for Elementor Pro is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along with Post Not Found for Elementor Pro. If not, see https://www.gnu.org/licenses/gpl-2.0.html.
 *
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Plugin Constants
 */

define("EL_PNF_URL", plugin_dir_url(__FILE__));
define("EL_PNF_PATH", plugin_dir_path(__FILE__));
define("EL_PNF_MIN_PHP", get_file_data(__FILE__, array('mpv' => 'Min PHP Version'))['mpv']);
define("EL_PNF_EL_PRO_REQ_VERSION", get_file_data(__FILE__, array('mepv' => 'Min Elementor Pro Version'))['mepv']);

/**
 * our wordpress action to call the initialize_plugin function during the plugins_loaded
 */
add_action('plugins_loaded', 'initialize_plugin');


/**
 *   to be able to use the is_plugin_active && deactivate_plugins function we need to include this file.
 */
require_once(ABSPATH . 'wp-admin/includes/plugin.php');

/**
 * Check the plugin prequirements & Initialize the plugin
 *
 * Checks for basic plugin requirements, if one check fail don't continue,
 *
 * @since 0.1.0
 * @access public
 */
function el_post_not_found_check_req()
{
    // include notices file.
    require_once EL_PNF_PATH."/includes/notices/admin.php";

    // Check for required PHP version
    if (version_compare(PHP_VERSION, EL_PNF_MIN_PHP, '<')) {
        add_action('admin_notices', 'ElementorPostNotFound\admin_notice_minimum_php_version');
        return false;
    }

    // if (true) {
    //     add_action('admin_notices', 'ElementorPostNotFound\admin_notice_example_notice');
    //     // return true;
    // }

    if (!is_plugin_active('elementor-pro/elementor-pro.php')) {
        add_action('admin_notices', 'ElementorPostNotFound\admin_notice_elementor_missing');
        return false;
    }

    if (version_compare(ELEMENTOR_PRO_VERSION, EL_PNF_EL_PRO_REQ_VERSION)<1) {
        add_action('admin_notices', 'ElementorPostNotFound\admin_notice_wrong_elementor');
        return false;
    }

    return true;
}

/**
 * Initialize the plugin code.
 *
 * Checks for basic plugin requirements, if one check fail don't continue,
 *
 * @since 0.1.0
 * @access public
 */

function initialize_plugin()
{
    if (!el_post_not_found_check_req()) {
        deactivate_plugins(plugin_basename(__FILE__));
        return;
    }

    // once we have reached here then we are good to include our plugin.
    require_once EL_PNF_PATH."/plugin.php";
}
