<?php

/**
 * Check docs below.
 *
 * @action pmpro_checkout_after_user_fields 1
 */
function pmproott_checkout_maybe_open_disable_block() {
	$level_id = intval( isset( $_REQUEST['level'] ) ? $_REQUEST['level'] : 0 );

	if ( ! pmproott_user_can_subscribe_level( $level_id ) ) {
		echo '<!-- PMPro One Time Trials: BEGIN disable checkout -->';
		echo '<div style="display: none">';
	}
}

/**
 * Check docs below.
 *
 * @action pmpro_checkout_after_form 50
 */
function pmproott_checkout_maybe_close_disable_block() {
	$level_id = intval( isset( $_REQUEST['level'] ) ? $_REQUEST['level'] : 0 );

	if ( ! pmproott_user_can_subscribe_level( $level_id ) ) {
		echo '</div>';
		echo '<!-- PMPro One Time Trials: END disable checkout -->';
	}
}

/**
 * If a user can't subscribe the current level, we are trying to hide
 * all the following checkout form parts as soon as possible.
 *
 * TODO add a filter in core to have the "display: none" wrapper:
 * - opened before the pmpro_checkout_after_user_fields filter
 * - closed after the pmpro_checkout_after_form filter
 */
add_action( 'pmpro_checkout_after_user_fields', 'pmproott_checkout_maybe_open_disable_block', 1 );
add_action( 'pmpro_checkout_after_form', 'pmproott_checkout_maybe_close_disable_block', 50 );
