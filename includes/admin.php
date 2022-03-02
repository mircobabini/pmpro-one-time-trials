<?php

/**
 * Add Fallback Redirect Level field to level settings, at the end
 *
 * @filter
 *
 * @return void
 */
function pmproott_membership_level_after_other_settings() {
	$edit_level_id = (int) $_REQUEST['edit'];

	$redirect_level_id = get_pmpro_membership_level_meta( $edit_level_id, 'ott_fallback_redirect_level', true );
	?>
    <table>
        <tbody class="form-table">
        <tr>
            <th scope="row" valign="top">
                <label for="ott_fallback_redirect_level">
					<?php esc_html_e( __( 'Fallback Redirect Level:', 'pmpro-one-time-trials' ) ); ?>
                </label>
            </th>
            <td>
                <select name="ott_fallback_redirect_level">
					<?php
					$levels_options = [];
					foreach ( pmpro_getAllLevels( true, true ) as $level ) {
						if ( (int) $level->id === $edit_level_id ) {
							continue;
						}

						$levels_options[ $level->id ] = "$level->name --- ID: $level->id";
					}

					$options = [ '' => __( 'Disabled. Just show a notice.', 'pmpro-one-time-trials' ) ] + $levels_options;

					foreach ( $options as $level_id => $level_label ) {
						?>
                        <option value="<?php echo esc_attr( $level_id ) ?>" <?php selected( $redirect_level_id, $level_id ) ?>>
							<?php echo esc_html( $level_label ) ?>
                        </option>
						<?php
					}
					?>
                </select>
                <small>
					<?php
					esc_html_e(
						__(
							'Redirect to another level if this one can\'t be subscribed by the current user. ' .
							'Leave it empty to just show a notice.',
							'pmpro-one-time-trials' )
					);
					?>
                </small>
            </td>
        </tr>
        </tbody>
    </table>
	<?php
}

add_action( 'pmpro_membership_level_after_other_settings', 'pmproott_membership_level_after_other_settings' );

/**
 * Save fallback redirect when the level is added/saved
 *
 * @action pmpro_save_membership_level 10
 *
 * @param int $level_id
 *
 * @return void
 */
function pmproott_save_membership_level_redirect( $level_id ) {
	$redirect_level_id = $_REQUEST['ott_fallback_redirect_level'];

	if ( ! empty( $redirect_level_id ) ) {
		update_pmpro_membership_level_meta( $level_id, 'ott_fallback_redirect_level', $redirect_level_id );
	} else {
		delete_pmpro_membership_level_meta( $level_id, 'ott_fallback_redirect_level' );
	}
}

add_action( 'pmpro_save_membership_level', 'pmproott_save_membership_level_redirect' );
