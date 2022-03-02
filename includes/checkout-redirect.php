<?php

/**
 * @action wp 10
 *
 * Do a redirect to another level if the user cant subscribe this level
 * and there's a fallback level id set up in level settings.
 */
function pmproott_checkout_redirect_to_fallback_level() {
	global $pmpro_pages, $pmpro_level;

	if ( ! empty( $pmpro_pages['checkout'] ) && is_page( $pmpro_pages['checkout'] ) ) {
		$level_id = (int) $pmpro_level->id;

		if ( pmproott_level_is_trial( $level_id ) && ! pmproott_user_can_subscribe_level( $level_id ) ) {
			$redirect_level_id = pmproott_get_fallback_level_redirect( $level_id );

			if ( false !== $redirect_level_id ) {
				wp_safe_redirect(
					add_query_arg( [
						'level'               => $redirect_level_id,
						'fallback_from_level' => $level_id
					], pmpro_url( 'checkout' ) )
				);
				exit;
			}
		}
	}
}

add_action( 'wp', 'pmproott_checkout_redirect_to_fallback_level' );

/**
 * Show a notice message on top of checkout if the user was redirected from a non-subscribable level
 *
 * @action pmpro_checkout_before_form 10
 */
function pmproott_checkout_before_form_redirect_fallback_notice() {
	global $pmpro_level;

	if ( isset( $_REQUEST['fallback_from_level'] ) ) {
		$level_id = (int) $_REQUEST['fallback_from_level'];
		$level    = pmpro_getLevel( $level_id );

		if ( false !== $level ) {
			?>
            <p class="pmpro_message pmpro_success">
				<?php echo wp_kses_post(
					sprintf(
						__( 'Since you can\'t subscribe <strong>%s</strong>, we think you want to subscribe <strong>%s</strong>.', 'pmpro-one-time-trials' ),
						$level->name,
						$pmpro_level->name
					)
				) ?>
            </p>
			<?php
		}
	}
}

add_action( 'pmpro_checkout_before_form', 'pmproott_checkout_before_form_redirect_fallback_notice' );
