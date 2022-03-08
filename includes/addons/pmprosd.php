<?php
/**
 * This snippet should be moved to:
 * https://github.com/strangerstudios/pmpro-subscription-delays
 *
 * TODO this will become useless as soon as PR35 is merged
 * https://github.com/strangerstudios/pmpro-subscription-delays/pull/35
 * 
 * @param array $trial_levels
 *
 * @returns array
 */
add_filter( 'pmproott_trial_levels', function ( $trial_levels ) {
	$levels = pmpro_getAllLevels( true, true );
	foreach ( $levels as $level ) {
		$delay = get_option( 'pmpro_subscription_delay_' . $level->id, '' );
		if ( ! empty( $delay ) ) {
			$trial_levels[ (int) $level->id ] = $level;
		}
	}

	return $trial_levels;
} );
