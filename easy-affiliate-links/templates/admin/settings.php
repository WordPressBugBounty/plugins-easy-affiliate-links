<?php
/**
 * Template for settings page.
 *
 * @link       https://bootstrapped.ventures
 * @since      2.0.0
 *
 * @package    Easy_Affiliate_Links
 * @subpackage Easy_Affiliate_Links/templates/admin
 */

// Subpage.
$sub = isset( $_GET['sub'] ) ? sanitize_key( wp_unslash( $_GET['sub'] ) ) : ''; // Input var okay.

$tabs = apply_filters( 'eafl_settings_tabs', array(
	'general' => __( 'General', 'easy-affiliate-links' ),
	'advanced' => __( 'Advanced', 'easy-affiliate-links' ),
) );

if ( ! array_key_exists( $sub, $tabs ) ) {
	$sub = 'general';
}
?>

<div class="wrap eafl-settings">
		<h1><?php esc_html_e( 'Easy Affiliate Links Settings', 'easy-affiliate-links' ); ?></h1>

		<h2 class="nav-tab-wrapper">
			<?php
			foreach ( $tabs as $tab => $label ) {
				$url = add_query_arg( 'sub', $tab, admin_url( 'admin.php?page=eafl_settings' ) );
				$active = $sub === $tab ? ' nav-tab-active' : '';

				echo '<a href="' . esc_url( $url ) . '" class="nav-tab' . esc_attr( $active ) . '">' . esc_html( $label ) . '</a>';
			}
			?>
		</h2>

		<?php do_action( 'eafl_settings_page', $sub ); ?>
</div>
