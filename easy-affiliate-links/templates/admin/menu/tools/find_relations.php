<?php
/**
 * Template for the find relations tools page.
 *
 * @link       https://bootstrapped.ventures
 * @since      3.2.0
 *
* @package    Easy_Affiliate_Links
 * @subpackage Easy_Affiliate_Links/templates/admin/menu/tools
 */

?>

<div class="wrap eafl-tools">
	<h2><?php esc_html_e( 'Find Link Usage', 'easy-affiliate-links' ); ?></h2>
	<?php printf( esc_html( _n( 'Searching %d post', 'Searching %d posts', count( $items ), 'easy-affiliate-links' ) ), count( $items ) ); ?>.
	<div id="eafl-tools-progress-container">
		<div id="eafl-tools-progress-bar"></div>
	</div>
	<a href="<?php echo esc_url( admin_url( 'admin.php?page=easyaffiliatelinks#/usage' ) ); ?>" id="eafl-tools-finished"><?php esc_html_e( 'Finished succesfully. Click here to continue.', 'easy-affiliate-links' ); ?></a>
</div>
