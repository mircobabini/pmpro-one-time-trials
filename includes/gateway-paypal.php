<?php

/**
 * This is gonna be called twice during a PPE checkout.
 * 1) with GetExpressCheckoutDetails, we have some interesting data but still don't know if the sub will be created;
 * 2) with CreateRecurringPaymentsProfile, we can check the PROFILESTATUS to be sure that the sub got created;
 * => then we can save the details into the lookup table
 *
 * @action pmpro_ppe_http_post_response
 * This hook is available since PMPro [TODO add version]
 * https://github.com/strangerstudios/paid-memberships-pro/pull/1992
 *
 * @param string $methodName
 * @param array $httpParsedResponseAr
 *
 * @return void
 */
function pmproott_ppe_http_post_response( $methodName, $httpParsedResponseAr ) {
	global $wpdb, $pmproott_checkout_payer_email, $pmproott_checkout_payer_id;

	$ack = strtoupper( $httpParsedResponseAr['ACK'] );
	if ( ! in_array( $ack, [ 'SUCCESS', 'SUCCESSWITHWARNING' ] ) ) {
		return;
	}

	switch ( $methodName ) {
		case 'GetExpressCheckoutDetails':
			// the order gonna change status to "review"
			// now we have the EMAIL and the PAYERID of the customer on the gateway
			$pmproott_checkout_payer_email = $httpParsedResponseAr['EMAIL'];
			$pmproott_checkout_payer_id    = $httpParsedResponseAr['PAYERID'];
			break;

		case 'CreateRecurringPaymentsProfile':
			$profilestatus = isset( $this->httpParsedResponseAr['PROFILESTATUS'] ) ? $this->httpParsedResponseAr['PROFILESTATUS'] : '';
			if ( in_array( $profilestatus, [ 'ActiveProfile', 'PendingProfile' ] ) ) {
				// the order gonna change status to "success"
				// now we can save the gateway informations into the lookup table

				$wpdb->insert(
					$wpdb->pmproott_lookup_data,
					[
						'membership_order_id' => '',
						'payer_email'         => $pmproott_checkout_payer_email,
						'payer_id'            => $pmproott_checkout_payer_id,
					],
					[
						'%d',
						'%s',
						'%s',
					]
				);
			}
			break;
	}
}

add_action( 'pmpro_ppe_http_post_response', 'pmproott_ppe_http_post_response', 10, 2 );
