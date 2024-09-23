<?php
/**
 * Responsible for handling the redirects.
 *
 * @link       https://bootstrapped.ventures
 * @since      2.0.0
 *
 * @package    Easy_Affiliate_Links
 * @subpackage Easy_Affiliate_Links/includes/public
 */

/**
 * Responsible for handling the redirects.
 *
 * @since      2.0.0
 * @package    Easy_Affiliate_Links
 * @subpackage Easy_Affiliate_Links/includes/public
 * @author     Brecht Vandersmissen <brecht@bootstrapped.ventures>
 */
class EAFL_Redirect {
	/**
	 * Register actions and filters.
	 *
	 * @since    2.0.0
	 */
	public static function init() {
		add_action( 'template_redirect', array( __CLASS__, 'redirect' ) );
	}

	/**
	 * Handle the link redirect.
	 *
	 * @since    2.0.0
	 */
	public static function redirect() {
		$post = get_post();

		if ( $post && EAFL_POST_TYPE === $post->post_type ) {
			$link = EAFL_Link_Manager::get_link( $post );
			$link = apply_filters( 'eafl_redirect_link', $link );

			if ( $link && 'html' !== $link->type() ) {
				$url = $link->url();

				// URL encode some characters that would get stripped by wp_redirect otherwise.
				$url = str_replace( '@', '%40', $url );
				$url = str_replace( '|', '%7C', $url );

				if ( $url ) {
					// Try to prevent click register issues from breaking redirect.
					try {
						@EAFL_Clicks::register( $link );
					} catch( Exception $e ) {}

					$redirect_type = $link->redirect_type();
					if ( ! in_array( intval( $redirect_type ), array( 301, 302, 307 ) ) ) {
						$redirect_type = EAFL_Settings::get( 'default_redirect_type' );
					}

					// Noindex the redirect page.
					header( 'X-Robots-Tag: noindex' );

					// Allow destination URL to get filtered and trigger redirect action to hook into.
					$url = apply_filters( 'eafl_redirect_url', $url, $link );
					do_action( 'eafl_redirect', $link, $url );

					wp_redirect( $url, intval( $redirect_type ) );
					exit();
				}
			} else {
				// Force a 404 error.
				global $wp_query;
				$wp_query->set_404();
				status_header( 404 );
				get_template_part( 404 );
				exit();
			}
		}
	}
}

EAFL_Redirect::init();
