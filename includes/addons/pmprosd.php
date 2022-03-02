<?php
/**
 * This snippet should be moved to:
 * https://github.com/strangerstudios/pmpro-subscription-delays
 *
 * @param $trial_levels array
 *
 * @returns array
 */
add_filter( 'pmproott_trial_levels', function ( $trial_levels ) {
	$levels = pmpro_getAllLevels( true, true );
	foreach ( $levels as $level ) {
		if ( 0.00 === $level->initial_payment && $level->billing_amount > 0 ) {
			$delay = get_option( 'pmpro_subscription_delay_' . $level->id, '' );
			if ( ! empty( $delay ) ) {
				$trial_levels[ (int) $level->id ] = $level;
			}
		}
	}

	return $trial_levels;
} );
