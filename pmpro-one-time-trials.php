<?php
/*
Plugin Name: Paid Memberships Pro - One Time Trials Add On
Plugin URI: http://www.paidmembershipspro.com/add-ons/pmpro-one-time-trials/
Description: Offer trial memberships that can only be used once
Version: 1.0.0
Author: Stranger Studios, Mirco Babini
Author URI: http://www.strangerstudios.com
Text Domain: pmpro-one-time-trials
Domain Path: /languages

zip -r pmpro-one-time-trials-1.0.0.zip pmpro-one-time-trials -x "*.DS_Store" -x "*.git*"; open .
*/

define( 'PMPROOTT_VERSION', '1.0.0' );
define( 'PMPROOTT_BASE_FILE', __FILE__ );
define( 'PMPROOTT_DIR', dirname( __FILE__ ) );

require_once PMPROOTT_DIR . '/includes/database.php';
require_once PMPROOTT_DIR . '/includes/functions.php';

require_once PMPROOTT_DIR . '/includes/admin.php';
require_once PMPROOTT_DIR . '/includes/checkout.php';               // Add messages to checkout.
require_once PMPROOTT_DIR . '/includes/checkout-redirect.php';      // Auto-redirect to a paid level.
require_once PMPROOTT_DIR . '/includes/checkout-disable-parts.php'; // Hide following checkout parts.

require_once PMPROOTT_DIR . '/includes/gateway-paypal.php'; // Logics for gateway: PayPal
// require_once PMPROOTT_DIR . '/includes/gateway-stripe.php'; // Logics for gateway: Stripe

/**
 * Include tests
 */
if ( file_exists( PMPROOTT_DIR . '/includes/dev-tests/tests.php' ) ) {
	require_once PMPROOTT_DIR . '/includes/dev-tests/tests.php';
}

/**
 * Include addons compat code
 */
require_once ABSPATH . 'wp-admin/includes/plugin.php';
if ( is_plugin_active( 'pmpro-subscription-delays/pmpro-subscription-delays.php' ) ) {
	require_once PMPROOTT_DIR . '/includes/addons/pmprosd.php';
}

/**
 * Load text domain
 */
function pmproott_load_plugin_text_domain() {
	load_plugin_textdomain( 'pmpro-one-time-trials', false, basename( PMPROOTT_DIR ) . '/languages' );
}

add_action( 'init', 'pmproott_load_plugin_text_domain' );

/**
 * Add links to the plugin row meta
 *
 * @param array $links
 * @param string $file
 *
 * @return array
 */
function pmproott_plugin_row_meta( $links, $file ) {
	if ( strpos( $file, 'pmpro-one-time-trials.php' ) !== false ) {
		$new_links = [
			'<a href="' . esc_url( 'https://www.paidmembershipspro.com/add-ons/plugins-on-github/pmpro-one-time-trials/' ) . '" title="' . esc_attr( __( 'View Documentation', 'pmpro-one-time-trials' ) ) . '">' . __( 'Docs', 'pmpro-one-time-trials' ) . '</a>',
			'<a href="' . esc_url( 'https://www.paidmembershipspro.com/support/' ) . '" title="' . esc_attr( __( 'Visit Customer Support Forum', 'pmpro-one-time-trials' ) ) . '">' . __( 'Support', 'pmpro-one-time-trials' ) . '</a>',
		];

		$links = array_merge( $links, $new_links );
	}

	return $links;
}

add_filter( 'plugin_row_meta', 'pmproott_plugin_row_meta', 10, 2 );
