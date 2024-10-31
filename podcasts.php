<?php
/*
Plugin Name: Podcast to Article
Plugin URI: http://podcasts.bluepill.life
Description: A podcast trascriber
Version: 1.0.2
Author: Tqtifnypmb
License: GPL2
License URI: https://www.gnu.org/licenses/gpl-2.0.html
Text Domain: podcasts
Domain Path: /languages
*/

/**
 * Copyright (c) 2020 Tqtifnypmb (email: Tqtifnypmb@gmail.com). All rights reserved.
 *
 * Released under the GPL license
 * http://www.opensource.org/licenses/gpl-license.php
 *
 * This is an add-on for WordPress
 * http://wordpress.org/
 *
 * **********************************************************************
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 * **********************************************************************
 */

// don't call the file directly
if ( !defined( 'ABSPATH' ) ) exit;

/**
 * TQT_PODCASTS_Plugin class
 *
 * @class TQT_PODCASTS_Plugin The class that holds the entire TQT_PODCASTS_Plugin plugin
 */
final class TQT_PODCASTS_Plugin {

    /**
     * Plugin version
     *
     * @var string
     */
    public $version = '1.0.0';

    /**
     * Holds various class instances
     *
     * @var array
     */
    private $container = array();

    /**
     * Constructor for the TQT_PODCASTS_Plugin class
     *
     * Sets up all the appropriate hooks and actions
     * within our plugin.
     */
    public function __construct() {

        $this->define_constants();

        register_activation_hook( __FILE__, array( $this, 'activate' ) );
        register_deactivation_hook( __FILE__, array( $this, 'deactivate' ) );

        add_action( 'plugins_loaded', array( $this, 'init_plugin' ) );
    }

    /**
     * Initializes the TQT_PODCASTS_Plugin() class
     *
     * Checks for an existing TQT_PODCASTS_Plugin() instance
     * and if it doesn't find one, creates it.
     */
    public static function init() {
        static $instance = false;

        if ( ! $instance ) {
            $instance = new TQT_PODCASTS_Plugin();
        }

        return $instance;
    }

    /**
     * Magic getter to bypass referencing plugin.
     *
     * @param $prop
     *
     * @return mixed
     */
    public function __get( $prop ) {
        if ( array_key_exists( $prop, $this->container ) ) {
            return $this->container[ $prop ];
        }

        return $this->{$prop};
    }

    /**
     * Magic isset to bypass referencing plugin.
     *
     * @param $prop
     *
     * @return mixed
     */
    public function __isset( $prop ) {
        return isset( $this->{$prop} ) || isset( $this->container[ $prop ] );
    }

    /**
     * Define the constants
     *
     * @return void
     */
    public function define_constants() {
        define( 'PODCASTS_VERSION', $this->version );
        define( 'PODCASTS_FILE', __FILE__ );
        define( 'PODCASTS_PATH', dirname( PODCASTS_FILE ) );
        define( 'PODCASTS_INCLUDES', PODCASTS_PATH . '/includes' );
        define( 'PODCASTS_URL', plugins_url( '', PODCASTS_FILE ) );
        define( 'PODCASTS_ASSETS', PODCASTS_URL . '/assets' );
    }

    /**
     * Load the plugin after all plugis are loaded
     *
     * @return void
     */
    public function init_plugin() {
        $this->includes();
        $this->init_hooks();
    }

    /**
     * Placeholder for activation function
     *
     * Nothing being called here yet.
     */
    public function activate() {

        $installed = get_option( 'podcasts_installed' );

        if ( ! $installed ) {
            update_option( 'podcasts_installed', time() );
        }

        update_option( 'podcasts_version', PODCASTS_VERSION );
    }

    /**
     * Placeholder for deactivation function
     *
     * Nothing being called here yet.
     */
    public function deactivate() {

    }

    /**
     * Include the required files
     *
     * @return void
     */
    public function includes() {

        require_once PODCASTS_INCLUDES . '/Assets.php';

        if ( $this->is_request( 'admin' ) ) {
            require_once PODCASTS_INCLUDES . '/Admin.php';
        }

        if ( $this->is_request( 'frontend' ) ) {
            // require_once PODCASTS_INCLUDES . '/Frontend.php';
        }

        if ( $this->is_request( 'ajax' ) ) {
            // require_once PODCASTS_INCLUDES . '/class-ajax.php';
        }

        require_once PODCASTS_INCLUDES . '/Api.php';
    }

    /**
     * Initialize the hooks
     *
     * @return void
     */
    public function init_hooks() {

        add_action( 'init', array( $this, 'init_classes' ) );

        // Localize our plugin
        add_action( 'init', array( $this, 'localization_setup' ) );
    }

    /**
     * Instantiate the required classes
     *
     * @return void
     */
    public function init_classes() {

        if ( $this->is_request( 'admin' ) ) {
            $this->container['admin'] = new TQT_PODCASTS_App\Admin();
        }

        if ( $this->is_request( 'frontend' ) ) {
            // $this->container['frontend'] = new TQT_PODCASTS_App\Frontend();
        }

        if ( $this->is_request( 'ajax' ) ) {
            // $this->container['ajax'] =  new TQT_PODCASTS_App\Ajax();
        }

        $this->container['api'] = new TQT_PODCASTS_App\Api();
        $this->container['assets'] = new TQT_PODCASTS_App\Assets();
    }

    /**
     * Initialize plugin for localization
     *
     * @uses load_plugin_textdomain()
     */
    public function localization_setup() {
        load_plugin_textdomain( 'podcasts', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
    }

    /**
     * What type of request is this?
     *
     * @param  string $type admin, ajax, cron or frontend.
     *
     * @return bool
     */
    private function is_request( $type ) {
        switch ( $type ) {
            case 'admin' :
                return is_admin();

            case 'ajax' :
                return defined( 'DOING_AJAX' );

            case 'rest' :
                return defined( 'REST_REQUEST' );

            case 'cron' :
                return defined( 'DOING_CRON' );

            case 'frontend' :
                return ( ! is_admin() || defined( 'DOING_AJAX' ) ) && ! defined( 'DOING_CRON' );
        }
    }

} // TQT_PODCASTS_Plugin

$podcasts = TQT_PODCASTS_Plugin::init();
