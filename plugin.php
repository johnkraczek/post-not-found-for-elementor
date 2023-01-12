<?php

namespace ElementorPostNotFound;

use ElementorPostNotFound\Admin\PNF_Manager;

if (! defined('ABSPATH')) {
    exit;
}


class Plugin
{
    /**
     * Instance.
     *
     * Holds the plugin instance.
     *
     * @since 0.1.0
     * @access public
     * @static
     *
     * @var Plugin
     */
    public static $instance = null;


    /** Classes that are part of the plugin will be listed here.
     *
    */

    // /**
    //  * Database.
    //  *
    //  * Holds the plugin database handler which is responsible for communicating
    //  * with the database.
    //  *
    //  * @since 1.0.0
    //  * @access public
    //  *
    //  * @var DB
    //  */
    // public $db;

    /**
     * Page Not Found Manager.
     *
     * Handles checking if the page was not found and outputing the selected template. 
     *
     * @since 0.1.0
     * @access public
     *
     * @var pnf_manager
     */
    public $pnf_manager;

    /**
     * Clone.
     *
     * Disable class cloning and throw an error on object clone.
     *
     * The whole idea of the singleton design pattern is that there is a single
     * object. Therefore, we don't want the object to be cloned.
     *
     * @access public
     * @since 0.1.0
     */
    public function __clone()
    {
        // Cloning instances of the class is forbidden.
        _doing_it_wrong(__FUNCTION__, esc_html__('Something went wrong.', 'post-not-found-for-elementor'), '1.0.0');
    }

    /**
     * Wakeup.
     *
     * Disable unserializing of the class.
     *
     * @access public
     * @since 0.1.0
     */
    public function __wakeup()
    {
        // Unserializing instances of the class is forbidden.
        _doing_it_wrong(__FUNCTION__, esc_html__('Something went wrong.', 'post-not-found-for-elementor'), '1.0.0');
    }

    /**
     * Instance.
     *
     * Ensures only one instance of the plugin class is loaded or can be loaded.
     *
     * @since 1.0.0
     * @access public
     * @static
     *
     * @return Plugin An instance of the class.
     */
    public static function instance()
    {
        if (is_null(self::$instance)) {
            self::$instance = new self();
            /**
             * Plugin loaded.
             *
             * Fires when the Plugin was fully loaded and instantiated.
             *
             * @since 0.1.0
             */
            do_action('ElementorPostNotFound/loaded');
        }
        return self::$instance;
    }

    /**
     * Init .
     *
     * Initialize Page Not Found components. Register actions,
     * initialize admin components.
     *
     * @since 0.1.0
     * @access public
     */
    public function init()
    {
        $this->pnf_manager = new PNF_Manager();

    }

    /**
     * Plugin constructor.
     *
     * Initializing elementor-post-not-found plugin.
     *
     * @since 1.0.0
     * @access private
     */
    private function __construct()
    {
        require_once EL_PNF_PATH."/vendor/autoload.php";
        add_action('init', [ $this, 'init' ], 0);
    }
}

Plugin::instance();