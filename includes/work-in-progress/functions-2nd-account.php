<?php
/**
 * Sometimes customers create a second account to skip new/returning customer checks.
 *
 * This is useful to detect if this is happening during a checkout (BEFORE confirmation).
 *
 * @return bool
 */
function pmproott_user_creating_second_account() {
	$bool = false;

	$accountNumber = ! empty( $_REQUEST['AccountNumber'] ) ? $_REQUEST['AccountNumber'] : '';
	if ( ! empty( $accountNumber ) ) {
		// TODO search the lookup table for $user_ids with this account number associated
		$user_ids = [];

		if ( is_user_logged_in() ) {
			// filter the user from the list
			$pos = array_search( get_current_user_id(), $user_ids );
			if ( false !== $pos ) {
				unset( $user_ids[ $pos ] );
			}
		}

		// is there someone registered with that AccountNumber?
		if ( ! empty( $user_ids ) ) {
			$bool = true;
		}
	}

	$bool = apply_filters( 'pmproott_user_creating_second_account', $bool );

	return $bool;
}

/**
 * Sometimes customers create a second account to skip new/returning customer checks.
 *
 * This is useful to detect if this happened during a checkout (AFTER confirmation).
 *
 * @return bool
 */
function pmproott_user_created_second_account() {
	$bool = false;

	if ( ! is_user_logged_in() ) {
		return false;
	}

	// TODO get from the lookup table the customer_ids for the current user
	// è una lookup table dedicata perché devo poter fare query rapide
	// inoltre per ogni utente sono consentiti più gateway => customer_id
	// perché oggi potrei pagare con una carta, domani con un altra
	// cosi come oggi potrei pagare con un account paypal, domani con un altro...
	$gateways_customer_ids = array();

	// è un valore serializzato: per ogni gateway => customer id identificato
	foreach ( $gateways_customer_ids as $gateway => $customer_id ) {
		// TODO search into the lookup table for other users ( ->user_id != current )
		// AND gateway === $gateway AND customer_id = $customer_id
		// which means... another user id usign the same gateway credentials.
		$other_user_id = null; // user id dell'altro account.

		if ( ! empty( $other_user_id ) ) {
			$bool = true;
		}
	}

	$bool = apply_filters( 'pmproott_user_created_second_account', $bool );

	return $bool;
}
