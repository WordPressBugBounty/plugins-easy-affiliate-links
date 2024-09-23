<?php
/**
 * Template for the migration page.
 *
 * @link       https://bootstrapped.ventures
 * @since      2.1.0
 *
 * @package    Easy_Affiliate_Links
 * @subpackage Easy_Affiliate_Links/templates/admin/menu
 */

// Subpage.
$sub = isset( $_GET['sub'] ) ? sanitize_key( wp_unslash( $_GET['sub'] ) ) : ''; // Input var okay.
?>

<div class="wrap eafl-migration">
	<h2><?php esc_html_e( 'Migration', 'easy-affiliate-links' ); ?></h2>
	<?php do_action( 'eafl_migration_page', $sub ); ?>
</div>
