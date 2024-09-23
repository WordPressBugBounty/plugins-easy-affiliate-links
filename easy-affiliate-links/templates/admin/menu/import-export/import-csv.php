<?php
/**
 * Template for the import CSV page.
 *
 * @link       https://bootstrapped.ventures
 * @since      3.0.0
 *
 * @package    Easy_Affiliate_Links
 * @subpackage Easy_Affiliate_Links/templates/admin/menu/manage
 */

$url = add_query_arg( 'sub', 'import_csv', admin_url( 'admin.php?page=eafl_import_export' ) );

?>

<p><?php esc_html_e( "It's a good idea to backup your WP database before using the import feature.", 'easy-affiliate-links' ); ?></p>
<p><?php esc_html_e( 'Select the CSV file containing links in any structure. You will able to match columns in the next step.', 'easy-affiliate-links' ); ?></p>
<form method="POST" action="<?php echo esc_url( $url ); ?>" enctype="multipart/form-data">
	<?php wp_nonce_field( 'eafl_import', 'eafl_import' ); ?>
	<input type="file" name="csv">
	<?php submit_button( __( 'Match CSV Columns...', 'easy-affiliate-links' ) ); ?>
</form>
