<?php

/**
 * Check at checkout if the user has used the trial level already
 *
 * @filter pmpro_registration_checks 10
 */
function pmproott_registration_checks( $value ) {
	global $pmpro_msg, $pmpro_msgt;

	$level_id = intval( isset( $_REQUEST['level'] ) ? $_REQUEST['level'] : 0 );

	if ( pmproott_level_is_trial( $level_id ) && ! pmproott_user_can_subscribe_level( $level_id ) ) {
		$value = false;

		$pmpro_msgt = 'pmpro_error';
		$pmpro_msg  = pmproott_checkout_error_message( $level_id );
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
	global $pmpro_level;

	$level_id = (int) $pmpro_level->id;

	if ( pmproott_level_is_trial( $level_id ) && ! pmproott_user_can_subscribe_level( $level_id ) ) {
		$pmpro_msg = pmproott_checkout_error_message( $level_id );

		if ( ! empty( $pmpro_msg ) ) {
			?>
			<p class="pmpro_message pmpro_error">
				<?php esc_html_e( $pmpro_msg ) ?>
			</p>
			<?php
		}
	}
}

add_action( 'pmpro_checkout_before_form', 'pmproott_checkout_before_form_cant_subscribe_error' );
