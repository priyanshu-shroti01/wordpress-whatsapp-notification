<?php

namespace Texty\Gateways;

use WP_Error;

/**
 * Washroti Class
 *
 * @see https://washroti.in
 */
class Washroti implements GatewayInterface {

    /**
     * API Endpoint
     */
    const ENDPOINT = 'https://washroti.in/api/whatsapp-web/create-message';

    /**
     * Get the name
     *
     * @return string
     */
    public function name() {
        return __( 'Washroti', 'texty' );
    }

    /**
     * Get the description
     *
     * @return string
     */
    public function description() {
        return sprintf(
            __(
                'Send Notification using Washroti. <a href="%1$s" target="_blank">Visit Us</a> and <a href="%2$s" target="_blank">Get API Key</a>.',
                'texty'
            ),
            'https://washroti.in/',
            'https://washroti.in/user/whatsapp-web/apps'
        );
    }

    /**
     * Get the logo
     *
     * @return string
     */
    public function logo() {
        return TEXTY_URL . '/assets/images/washroti.svg'; 
    }

    /**
     * Get the settings
     *
     * @return array
     */
    public function get_settings() {
        $creds = texty()->settings()->get( 'washroti' );

        return [
            'appkey' => [
                'name'  => __( 'App Key', 'texty' ),
                'type'  => 'password',
                'value' => isset( $creds['appkey'] ) ? $creds['appkey'] : '',
            ],
            'authkey' => [
                'name'  => __( 'Auth Key', 'texty' ),
                'type'  => 'password',
                'value' => isset( $creds['authkey'] ) ? $creds['authkey'] : '',
            ],
        ];
    }

    /**
     * Send SMS
     *
     * @param string $to
     * @param string $message
     *
     * @return WP_Error|true
     */
    public function send( $to, $message ) {
        $creds = texty()->settings()->get( 'washroti' );

        $body = [
            'appkey'  => $creds['appkey'],
            'authkey' => $creds['authkey'],
            'to'      => $to,
            'message' => $message,
            'sandbox' => 'false'
        ];

        $response = wp_remote_post( self::ENDPOINT, [
            'method'    => 'POST',
            'timeout'   => 30,
            'body'      => $body,
        ]);

        if ( is_wp_error( $response ) ) {
            return $response;
        }

        $response_code = wp_remote_retrieve_response_code( $response );
        $body = wp_remote_retrieve_body( $response );
        $data = json_decode( $body );

        error_log( 'Washroti API Response: ' . print_r( $data, true ) );

        if ( $response_code === 200 && isset( $data->message_status ) && $data->message_status === 'Success' ) {
            return true;
        }

        return new WP_Error(
            $response_code,
            isset( $data->message_status ) ? $data->message_status : 'Unknown error from Washroti API'
        );
    }

    /**
     * Validate a REST API request
     *
     * @param WP_REST_Request $request
     *
     * @return array|WP_Error
     */
    public function validate( $request ) {
        $creds = $request->get_param( 'washroti' );

        return [
            'appkey'  => $creds['appkey'],
            'authkey' => $creds['authkey'],
        ];
    }
}
