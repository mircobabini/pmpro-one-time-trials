<?php
/**
 * Check if a user already had a level on this site.
 *
 * Useful to detect new/returning customers and send them to a specific Thank You Page.
 *
 * @return bool
 */
function pmproott_user_has_been_subscribed() {
	$bool = false;

	if ( is_user_logged_in() ) {
		global $wpdb;

		$membership_id = $wpdb->get_var( "SELECT membership_id FROM $wpdb->pmpro_memberships_users WHERE user_id = '" . esc_sql( get_current_user_id() ) . "' LIMIT 1" );
		if ( ! empty( $membership_id ) ) {
			$bool = true;
		}
	}

	$bool = apply_filters( 'pmproott_user_has_been_subscribed', $bool );

	return $bool;
}

/**
 * Check if the user already consumed one or more levels
 *
 * @param int|array $level_ids
 *
 * @return mixed|void
 */
function pmproott_user_has_consumed_trial( $level_ids = array() ) {
	$bool = false;

	if ( is_user_logged_in() ) {
		global $wpdb;

		if ( empty( $level_ids ) ) {
			$trial_levels     = pmproott_get_trial_levels();
			$trial_levels_ids = array_keys( $trial_levels );
		} else {
			$trial_levels_ids = (array) $level_ids;
		}

		$membership_id = $wpdb->get_var( "SELECT membership_id FROM $wpdb->pmpro_memberships_users WHERE user_id = '" . esc_sql( get_current_user_id() ) . "' AND membership_id IN (" . implode( ',', $trial_levels_ids ) . ") LIMIT 1" );
		if ( ! empty( $membership_id ) ) {
			$bool = true;
		}
	}

	$bool = apply_filters( 'pmproott_user_has_consumed_trial', $bool );

	return $bool;
}

/**
 * Search the trial levels
 *
 * @return array
 */
function pmproott_get_trial_levels() {
	$trial_levels = [];

	$levels = pmpro_getAllLevels( true, true );
	foreach ( $levels as $level ) {
		if ( pmpro_isLevelTrial( $level ) ) {
			$trial_levels[ (int) $level->id ] = $trial_levels;
		}
	}

	$trial_levels = apply_filters( 'pmproott_trial_levels', $trial_levels );

	return $trial_levels;
}

/**
 * @param int $level_id
 *
 * @return bool
 */
function pmproott_level_is_trial( $level_id ) {
	$trial_levels = pmproott_get_trial_levels();

	return in_array( $level_id, array_keys( $trial_levels ) );
}

/**
 * Check if the current user can subscribe to a level
 *
 * @param int $level
 *
 * @return bool
 */
function pmproott_user_can_subscribe_level( $level_id ) {
	global $current_user;

	if ( pmproott_level_is_trial( $level_id ) ) {
		if ( $current_user->ID ) {
			if ( pmproott_user_has_consumed_trial() ) {
				return false;
			} elseif ( pmproott_user_has_been_subscribed() ) {
				return false;
			} else {
				// TODO add second account check
				return true;
			}
		} else {
			// TODO add second account check
			return true;
		}
	} else {
		return true;
	}
}

/**
 * Se ?? impostato, va in redirect direttamente con un messaggio di avviso nel nuovo livello.
 * Se non ?? impostato invece, utilizza la vecchia logica presa e ottimizzata dal gist di Andrew.
 *
 * @param int $level_id
 *
 * @return int|bool
 */
function pmproott_get_fallback_level_redirect( $level_id ) {
	$redirect_level_id = get_pmpro_membership_level_meta( $level_id, 'ott_fallback_redirect_level', true );

	if ( empty( $redirect_level_id ) ) {
		return false;
	} else {
		return (int) $redirect_level_id;
	}
}
