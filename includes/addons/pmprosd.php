<?php
/**
 * This snippet should be moved to:
 * https://github.com/strangerstudios/pmpro-subscription-delays
 *
 * TODO Update: we will not merge because https://github.com/strangerstudios/pmpro-subscription-delays/pull/35#issuecomment-1072541697
 *
 * @param array $trial_levels
 *
 * @returns array
 */
add_filter( 'pmproott_trial_levels', function ( $trial_levels ) {
	$levels = pmpro_getAllLevels( true, true );
	foreach ( $levels as $level ) {
		$delay = get_option( 'pmpro_subscription_delay_' . $level->id, '' );
		if ( ! empty( $delay ) && is_numeric( $delay ) ) {
			$trial_levels[ (int) $level->id ] = $level;
		}
	}

	return $trial_levels;
} );
