<?php
namespace TQT_PODCASTS_App;

/**
 * Admin Pages Handler
 */
class Admin {

    public function __construct() {
        add_action( 'admin_menu', [ $this, 'admin_menu' ] );
    }

    /**
     * Register our menu page
     *
     * @return void
     */
    public function admin_menu() {
        global $submenu;

        $capability = 'manage_options';
        $slug       = 'podcasts';
        $icon_url   = plugins_url( 'podcasts/assets/images/icon_menu_16.svg' );
        // 'dashicons-text'
        $hook = add_menu_page( __( 'Podcasts', 'textdomain' ), __( 'Podcasts', 'textdomain' ), $capability, $slug, [ $this, 'plugin_page' ], $icon_url );

        if ( current_user_can( $capability ) ) {
            $submenu[ $slug ][] = array( __( 'App', 'textdomain' ), $capability, 'admin.php?page=' . $slug . '#/' );
            // $submenu[ $slug ][] = array( __( 'Search', 'textdomain' ), $capability, 'admin.php?page=' . $slug . '#/search' );
        }

        add_action( 'load-' . $hook, [ $this, 'init_hooks'] );
    }

    /**
     * Initialize our hooks for the admin page
     *
     * @return void
     */
    public function init_hooks() {
        add_action( 'admin_enqueue_scripts', [ $this, 'enqueue_scripts' ] );
    }

    /**
     * Load scripts and styles for the app
     *
     * @return void
     */
    public function enqueue_scripts() {
        wp_localize_script( 'podcasts-admin', 'wp_env', [
            'icons_dir' => PODCASTS_ASSETS . '/images',
            'host' => site_url() . '/wp-json' . '/podcasts/v1'
        ]);

        wp_enqueue_style( 'podcasts-admin' );
        wp_enqueue_style( 'podcasts-vendor' );
        wp_enqueue_script( 'podcasts-admin' );
        wp_enqueue_script( 'podcasts-vendor' );
    }

    /**
     * Render our admin page
     *
     * @return void
     */
    public function plugin_page() {
        echo '<div class="wrap"><div id="vue-admin-app"></div></div>';
    }
}
