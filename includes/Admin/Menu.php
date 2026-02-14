<?php

namespace Texty\Admin;

/**
 * Menu Class
 */
class Menu {

    /**
     * Initialize
     */
    public function __construct() {
        add_action( 'admin_menu', [ $this, 'register_menu' ] );
    }

    /**
     * Register admin menu
     *
     * @return void
     */
    public function register_menu() {
        $position = apply_filters( 'texty_menu_position', 58 );

        $menu = add_menu_page(
            __( 'WAShroti', 'texty' ),
            __( 'WAShroti', 'texty' ),
            'manage_options',
            'texty',
            [ $this, 'render_page' ],
            'data:image/svg+xml;base64,' . base64_encode( '<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="500" zoomAndPan="magnify" viewBox="0 0 375 374.999991" height="500" preserveAspectRatio="xMidYMid meet" version="1.0"><path fill="#6a38ef" d="M 214.484375 37.199219 L 214.40625 37.1875 C 131.226562 25.207031 53.871094 82.957031 41.945312 165.773438 C 37.558594 196.230469 42.605469 226.003906 54.941406 251.953125 L 67.578125 226.960938 C 69.058594 224.023438 72.640625 222.855469 75.574219 224.335938 C 78.511719 225.820312 79.6875 229.40625 78.203125 232.339844 L 42.292969 303.398438 L 123.699219 289.859375 C 126.941406 289.320312 130.007812 291.511719 130.546875 294.753906 C 131.085938 298 128.894531 301.066406 125.652344 301.605469 L 97.929688 306.214844 C 118.433594 322.574219 143.355469 333.800781 171.191406 337.808594 C 254.363281 349.785156 331.726562 292.015625 343.652344 209.222656 C 355.574219 126.425781 297.660156 49.175781 214.484375 37.199219 Z M 251.601562 271.582031 C 243.636719 273.269531 233.234375 274.617188 198.222656 260.164062 C 153.429688 241.695312 124.585938 196.390625 122.339844 193.449219 C 120.183594 190.507812 104.242188 169.460938 104.242188 147.6875 C 104.242188 125.921875 115.34375 115.316406 119.828125 110.769531 C 144.589844 85.570312 173.875 144.621094 159.601562 160.980469 C 157.355469 163.566406 155.21875 165.535156 152.976562 168.300781 C 150.917969 170.710938 148.59375 173.289062 151.183594 177.75 C 153.773438 182.109375 162.726562 196.652344 175.910156 208.335938 C 192.925781 223.410156 206.714844 228.226562 211.652344 230.277344 C 215.328125 231.796875 219.710938 231.433594 222.394531 228.585938 C 225.804688 224.925781 230.019531 218.859375 234.300781 212.886719 C 237.351562 208.601562 241.195312 208.070312 245.238281 209.585938 C 309.945312 231.988281 279.191406 265.640625 251.601562 271.582031 Z M 251.601562 271.582031 " fill-opacity="1" fill-rule="nonzero"/></svg>' ), // phpcs:ignore WordPress.PHP.DiscouragedPHPFunctions.obfuscation_base64_encode
            $position
        );

        add_action( 'admin_print_scripts-' . $menu, [ $this, 'enqueue_scripts' ] );
    }

    /**
     * Render the page
     *
     * @return void
     */
    public function render_page() {
        echo '<div id="texty-app"></div>';
    }

    /**
     * Enqueue JS and CSS
     *
     * @return void
     */
    public function enqueue_scripts() {
        $assets = [
            'version'      => TEXTY_VERSION,
            'dependencies' => [
                'wp-api-fetch',
                'wp-i18n',
            ],
        ];

        $url = TEXTY_URL . '/dist';

        // for local development
        // when webpack "hot module replacement" is enabled, this
        // constant needs to be turned "true" on "wp-config.php"
        if ( defined( 'WP_LOCAL_DEV' ) && WP_LOCAL_DEV ) {
            $url = 'http://localhost:8080';
        }

        // register scripts
        wp_register_script( 'texty-runtime', $url . '/runtime.js', $assets['dependencies'], $assets['version'], true );
        wp_register_script( 'texty-vendors', $url . '/vendors.js', [ 'texty-runtime' ], $assets['version'], true );
        wp_register_script( 'texty-admin', $url . '/app.js', [ 'texty-vendors' ], $assets['version'], true );
        wp_localize_script( 'texty-admin', 'texty', $this->localize_script() );

        // register styles
        wp_register_style( 'texty-vendors-css', $url . '/vendors.css', [ 'wp-components' ], $assets['version'] );
        wp_register_style( 'texty-css', $url . '/app.css', [ 'texty-vendors-css' ], $assets['version'] );

        // enqueue scripts and styles
        wp_enqueue_script( 'texty-admin' );
        wp_enqueue_style( 'texty-css' );
    }

    /**
     * Get the localize script
     *
     * @return array
     */
    public function localize_script() {
        $i18n = [];

        return apply_filters( 'texty_localize_script', $i18n );
    }
}
