<?php
namespace TQT_PODCASTS_App\Api;

use WP_REST_Controller;

class PodcastsApi extends WP_REST_Controller {

    public function __construct() {
        $this->namespace = 'podcasts/v1';
        $this->rest_base = '/';
    }

    public function register_routes() {
        register_rest_route(
            $this->namespace,
            $this->rest_base . 'createPost',
            array(
                array(
                    'methods'             => \WP_REST_Server::CREATABLE,
                    'callback'            => array( $this, 'create_post' ),
                    'permission_callback' => array( $this, 'check_permission' )
                )
            )
        );

        register_rest_route(
            $this->namespace,
            $this->rest_base . 'fetch',
            array(
                array(
                    'methods'             => \WP_REST_Server::CREATABLE,
                    'callback'            => array( $this, 'fetch' ),
                    'permission_callback' => array( $this, 'check_permission' )
                )
            )
        );
    }

    public function create_post( $request ) {
        $to_insert = [
            'post_content' => $request->get_body()
        ];

        $post_id = wp_insert_post( $to_insert );

        if ( $post_id == 0 ) {  // error
            $response = rest_ensure_response( new WP_Error() );
            return $response;
        } else {
            $response = rest_ensure_response( $post_id );
            return $response;
        }
    }

    public function fetch( $request ) {
        $to_fetch = $request->get_body();
        $url = json_decode( $to_fetch );

        $response = wp_remote_get( $url );

        if (is_wp_error( $response )) {
            return rest_ensure_response( $response->get_error_message() );
        } else {
            $body = wp_remote_retrieve_body( $response );
            return rest_ensure_response( $body );
        }
    }

    public function check_permission( $request ) {
        return true;
    }
}