<?php
/**
 * Template for the statistics data cleanup page.
 *
 * @link       https://bootstrapped.ventures
 * @since      2.1.1
 *
 * @package    Easy_Affiliate_Links
 * @subpackage Easy_Affiliate_Links/templates/admin/menu/statistics
 */

?>

<div class="eafl-statistics-cleanup">
	<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
		<input type="hidden" name="action" value="eafl_statistics_cleanup">
		<?php wp_nonce_field( 'eafl_statistics', 'eafl_statistics', false ); ?>
		<h2 class="title"><?php esc_html_e( 'Clean up link clicks', 'easy-affiliate-links' ); ?></h2>
		<p>
			<?php esc_html_e( 'Warning! Using this function will remove matching clicks from the database. Make a backup first if unsure.', 'easy-affiliate-links' ); ?>
		</p>
		<table class="form-table">
			<tbody>
				<tr>
					<th scope="row">
						<label for="remove_bots"><?php esc_html_e( 'Remove Bots', 'easy-affiliate-links' ); ?></label>
					</th>
					<td>
						<label for="remove_bots">
							<input name="remove_bots" type="checkbox" id="remove_bots" checked="checked" disabled="disabled" />
							<?php esc_html_e( 'Robots and crawlers that slipped through are automatically removed when cleaning up.', 'easy-affiliate-links' ); ?>
						</label>
					</td>
				</tr>
				<tr>
					<th scope="row">
						<label for="remove_all"><?php esc_html_e( 'Remove All Clicks', 'easy-affiliate-links' ); ?></label>
					</th>
					<td>
						<label for="remove_all">
							<input name="remove_all" type="checkbox" id="remove_all" />
							<?php esc_html_e( 'Remove ALL clicks during clean up.', 'easy-affiliate-links' ); ?>
						</label>
					</td>
				</tr>
				<tr>
					<th scope="row">
						<label for="remove_user_roles"><?php esc_html_e( 'Remove Clicks By Role', 'easy-affiliate-links' ); ?></label>
					</th>
					<td>
						<?php
						$roles = get_editable_roles();
						$roles_setting = EAFL_Settings::get( 'statistics_remove_user_roles' );

						foreach ( $roles as $role => $options ) {
							echo '<label for="remove_user_roles_' . esc_attr( $role ) . '" style="margin-right: 10px">';
							$checked = in_array( $role, $roles_setting, true ) ? ' checked="checked"' : '';
							echo '<input name="remove_user_roles[]" value="' . esc_attr( $role ) . '" type="checkbox" id="remove_user_roles_' . esc_attr( $role ) . '" ' . esc_attr( $checked ) . '/>';
							echo esc_html( $options['name'] );
							echo '</label><br/>';
						}
						?>
						<p class="description">
							<?php esc_html_e( 'Remove clicks by logged in users with these roles.', 'easy-affiliate-links' ); ?>
						</p>
					</td>
				</tr>
				<tr>
					<th scope="row">
						<label for="exclude_ips"><?php esc_html_e( 'Exclude IPs', 'easy-affiliate-links' ); ?></label>
					</th>
					<td>
						<textarea name="exclude_ips" rows="6" cols="50" id="exclude_ips" class="large-text code"><?php echo esc_html( EAFL_Settings::get( 'statistics_exclude_ips' ) ); ?></textarea>
						<p class="description" id="tagline-exclude_ips">
							<?php esc_html_e( 'Remove clicks by these IP addresses. One address or range per line. For example:', 'easy-affiliate-links' ); ?><br/>
							192.168.0.1-192.168.1.9<br/>
							127.0.0.1
						</p>
					</td>
				</tr>
			</tbody>
		</table>
		<?php submit_button( __( 'Clean Up Clicks', 'easy-affiliate-links' ) ); ?>
	</form>
</div>
