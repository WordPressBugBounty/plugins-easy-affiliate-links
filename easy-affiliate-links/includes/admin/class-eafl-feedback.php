<?php
/**
 * Asks for feedback.
 *
 * @link       https://bootstrapped.ventures
 * @since      2.6.0
 *
 * @package    Easy_Affiliate_Links
 * @subpackage Easy_Affiliate_Links/includes/admin
 */

/**
 * Asks for feedback.
 *
 * @since      2.6.0
 * @package    Easy_Affiliate_Links
 * @subpackage Easy_Affiliate_Links/includes/admin
 * @author     Brecht Vandersmissen <brecht@bootstrapped.ventures>
 */
class EAFL_Feedback {

	/**
	 * Register actions and filters.
	 *
	 * @since    2.6.0
	 */
	public static function init() {
		add_action( 'eafl_modal_notice', array( __CLASS__, 'modal_notice' ) );

		add_action( 'wp_ajax_eafl_feedback', array( __CLASS__, 'ajax_give_feedback' ) );
	}

	/**
	 * Show a notice in the modal.
	 *
	 * @since    2.6.0
	 */
	public static function modal_notice() {
		if ( current_user_can( 'manage_options' ) && '' === get_user_meta( get_current_user_id(), 'eafl_feedback', true ) ) {
			$count = wp_count_posts( EAFL_POST_TYPE )->publish;

			if ( 42 <= intval( $count ) ) {
				echo '<div class="eafl-feedback-notice">';
				echo '<strong>Wow, you\'ve create ' . esc_html( $count ) . ' affiliate links!</strong><br/>Are you enjoying our plugin so far?<br/>';
				echo '<button id="eafl-feedback-stop" class="button button-small">Stop asking me</button> <button id="eafl-feedback-no" class="button button-primary button-small">No...</button> <button id="eafl-feedback-yes" class="button button-primary button-small">Yes!</button>';
				echo '</div>';
			}
		}
	}

	/**
	 * Give feedback via AJAX.
	 *
	 * @since    2.6.0
	 */
	public static function ajax_give_feedback() {
		if ( check_ajax_referer( 'eafl', 'security', false ) ) {
			$answer = isset( $_POST['answer'] ) ? sanitize_text_field( wp_unslash( $_POST['answer'] ) ) : ''; // Input var okay.
			update_user_meta( get_current_user_id(), 'eafl_feedback', $answer );
		}

		wp_die();
	}
}

EAFL_Feedback::init();
