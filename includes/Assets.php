<?php
namespace TQT_PODCASTS_App;

/**
 * Scripts and Styles Class
 */
class Assets {

    function __construct() {

        if ( is_admin() ) {
            add_action( 'admin_enqueue_scripts', [ $this, 'register' ], 5 );
        } else {
            // add_action( 'wp_enqueue_scripts', [ $this, 'register' ], 5 );
        }
    }

    /**
     * Register our app scripts and styles
     *
     * @return void
     */
    public function register() {
        $this->register_scripts( $this->get_scripts() );
        $this->register_styles( $this->get_styles() );
    }

    /**
     * Register scripts
     *
     * @param  array $scripts
     *
     * @return void
     */
    private function register_scripts( $scripts ) {
        foreach ( $scripts as $handle => $script ) {
            $deps      = isset( $script['deps'] ) ? $script['deps'] : false;
            $in_footer = isset( $script['in_footer'] ) ? $script['in_footer'] : false;
            $version   = isset( $script['version'] ) ? $script['version'] : PODCASTS_VERSION;

            wp_register_script( $handle, $script['src'], $deps, $version, $in_footer );
        }
    }

    /**
     * Register styles
     *
     * @param  array $styles
     *
     * @return void
     */
    public function register_styles( $styles ) {
        foreach ( $styles as $handle => $style ) {
            $deps       = isset( $style['deps'] ) ? $style['deps'] : false;
            $version    = isset( $style['version'] ) ? $style['version'] : PODCASTS_VERSION;

            wp_register_style( $handle, $style['src'], $deps, $version );
        }
    }

    /**
     * Get all registered scripts
     *
     * @return array
     */
    public function get_scripts() {
        $prefix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '.min' : '';

        $scripts = [
            'podcasts-runtime' => [
                'src'       => PODCASTS_ASSETS . '/js/runtime.js',
                'version'   => filemtime( PODCASTS_PATH . '/assets/js/runtime.js' ),
                'in_footer' => true
            ],
            'podcasts-vendor' => [
                'src'       => PODCASTS_ASSETS . '/js/vendors.js',
                'version'   => filemtime( PODCASTS_PATH . '/assets/js/vendors.js' ),
                'in_footer' => true
            ],
            'podcasts-frontend' => [
                'src'       => PODCASTS_ASSETS . '/js/frontend.js',
                'deps'      => [ 'jquery', 'podcasts-vendor', 'podcasts-runtime' ],
                'version'   => filemtime( PODCASTS_PATH . '/assets/js/frontend.js' ),
                'in_footer' => true
            ],
            'podcasts-admin' => [
                'src'       => PODCASTS_ASSETS . '/js/admin.js',
                'deps'      => [ 'jquery', 'podcasts-vendor', 'podcasts-runtime' ],
                'version'   => filemtime( PODCASTS_PATH . '/assets/js/admin.js' ),
                'in_footer' => true
            ]
        ];

        return $scripts;
    }

    /**
     * Get registered styles
     *
     * @return array
     */
    public function get_styles() {

        $styles = [
            'podcasts-style' => [
                'src'       =>  PODCASTS_ASSETS . '/css/style.css',
                'version'   => filemtime( PODCASTS_PATH . '/assets/css/style.css' ),
            ],
            'podcasts-frontend' => [
                'src' =>  PODCASTS_ASSETS . '/css/frontend.css'
            ],
            'podcasts-admin' => [
                'src'       =>  PODCASTS_ASSETS . '/css/admin.css',
                'version'   => filemtime( PODCASTS_PATH . '/assets/css/admin.css' ),
            ],
            'podcasts-vendor' => [
                'src'       =>  PODCASTS_ASSETS . '/css/vendors.css',
                'version'   => filemtime( PODCASTS_PATH . '/assets/css/vendors.css' ),
            ]
        ];

        return $styles;
    }

}
