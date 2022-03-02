<?php

/**
 * Check at checkout if the user has used the trial level already
 *
 * @filter pmpro_registration_checks 10
 */
function pmproott_registration_checks( $value ) {
	global $current_user, $pmpro_msg, $pmpro_msgt;

	$level_id = intval( isset( $_REQUEST['level'] ) ? $_REQUEST['level'] : 0 );

	if ( pmproott_level_is_trial( $level_id ) ) {
		if ( $current_user->ID ) {
			if ( pmproott_user_has_consumed_trial() ) {
				$pmpro_msg  = __( 'You have already used up your trial membership. Please select a full membership to checkout.', 'pmpro-one-time-trials' );
				$pmpro_msgt = 'pmpro_error';

				$value = false;
			} elseif ( pmproott_user_has_been_subscribed() ) {
				$pmpro_msg  = __( 'You have already had a membership. Please select a full membership to checkout.', 'pmpro-one-time-trials' );
				$pmpro_msgt = 'pmpro_error';

				$value = false;
			} else {
				// TODO add second account check
			}
		}
	}

	return $value;
}

add_filter( 'pmpro_registration_checks', 'pmproott_registration_checks' );

/**
 * Show an error message on top of checkout if the user can't subscribe this level
 *
 * @action pmpro_checkout_before_form 10
 */
function pmproott_checkout_before_form_cant_subscribe_error() {
	global $current_user, $pmpro_level;

	$level_id = (int) $pmpro_level->id;

	if ( pmproott_level_is_trial( $level_id ) ) {
		$level_notice = '';

		if ( $current_user->ID ) {
			if ( pmproott_user_has_consumed_trial() ) {
				$level_notice = __( 'You have already used up your trial membership. Please select a full membership to checkout.', 'pmpro-one-time-trials' );
			} elseif ( pmproott_user_has_been_subscribed() ) {
				$level_notice = __( 'You have already had a membership. Please select a full membership to checkout.', 'pmpro-one-time-trials' );
			} else {
				// TODO add second account check
			}
		}

		if ( ! empty( $level_notice ) ) {
			?>
            <p class="pmpro_message pmpro_error">
				<?php esc_html_e( $level_notice ) ?>
            </p>
			<?php
		}
	}
}

add_action( 'pmpro_checkout_before_form', 'pmproott_checkout_before_form_cant_subscribe_error' );
