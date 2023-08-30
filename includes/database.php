<?php
/**
 * @action admin_init
 */
function pmproott_db_check_upgrade() {
	$pmpro_invoices_db_version = get_option( 'pmpro_ott_db_version' );

	global $wpdb;
	$wpdb->hide_errors();
	$wpdb->pmproott_lookup_data = $wpdb->prefix . 'pmproott_lookup_data';

	// if we can't find the db tables, reset version to 0
	$table_exists = $wpdb->query( "SHOW TABLES LIKE '$wpdb->pmproott_lookup_data'" );
	if ( ! $table_exists ) {
		$pmpro_invoices_db_version = 0;
	}

	if ( $pmpro_invoices_db_version < PMPROOTT_VERSION ) {
		pmproott_db_update();

		update_option( 'pmpro_ott_db_version', PMPROOTT_VERSION );
		$pmpro_invoices_db_version = PMPROOTT_VERSION;
	}

	return $pmpro_invoices_db_version;
}

add_action( 'admin_init', 'pmproott_db_check_upgrade' );

function pmproott_db_update() {
	require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

	global $wpdb;
	$wpdb->hide_errors();
	$wpdb->pmproott_lookup_data = $wpdb->prefix . 'pmproott_lookup_data';

	// payer_account_number is useful to detect the same credit card used twice on different gateways
	// payer_email is useful to detect the same user on different gateways
	// payer_id is useful to detect the same user on the same gateway, even if the email changed
	$sqlQuery = "
			CREATE TABLE `" . $wpdb->pmproott_lookup_data . "` (
				`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
				`membership_order_id` int(10) unsigned NOT NULL,
				`payer_id` varchar(20),
				`payer_email` varchar(100),
				`payer_account_number` varchar(32),
				`modified` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
				PRIMARY KEY  (`id`),
				UNIQUE KEY `membership_order_id` (`membership_order_id`)
			);
		";

	dbDelta( $sqlQuery );
}
