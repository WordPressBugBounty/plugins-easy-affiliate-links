<?php
/**
 * Represents a link.
 *
 * @link       https://bootstrapped.ventures
 * @since      2.0.0
 *
 * @package    Easy_Affiliate_Links
 * @subpackage Easy_Affiliate_Links/includes/public
 */

/**
 * Represents a link.
 *
 * @since      2.0.0
 * @package    Easy_Affiliate_Links
 * @subpackage Easy_Affiliate_Links/includes/public
 * @author     Brecht Vandersmissen <brecht@bootstrapped.ventures>
 */
class EAFL_Link {

	/**
	 * WP_Post object associated with this link post type.
	 *
	 * @since    2.0.0
	 * @access   private
	 * @var      object    $post    WP_Post object of this link post type.
	 */
	private $post;

	/**
	 * Metadata associated with this link post type.
	 *
	 * @since    2.0.0
	 * @access   private
	 * @var      array    $meta    Link metadata.
	 */
	private $meta = false;

	/**
	 * Get new link object from associated post.
	 *
	 * @since    2.0.0
	 * @param    object $post WP_Post object for this link post type.
	 */
	public function __construct( $post ) {
		$this->post = $post;
	}

	/**
	 * Get link data.
	 *
	 * @since    2.0.0
	 */
	public function get_data() {
		$link = array();

		// Technical Fields.
		$link['id'] = $this->id();

		// Link Details.
		$link['name'] = $this->name();
		$link['description'] = $this->description();
		$link['replacement'] = $this->replacement();
		$link['categories'] = $this->categories();
		$link['type'] = $this->type();
		$link['text'] = $this->text();
		$link['classes'] = $this->classes();
		$link['url'] = $this->url();
		$link['html'] = $this->html();
		$link['slug'] = $this->slug();
		$link['cloak'] = $this->cloak( true );
		$link['target'] = $this->target( true );
		$link['redirect_type'] = $this->redirect_type( true );
		$link['nofollow'] = $this->nofollow( true );
		$link['sponsored'] = $this->sponsored();
		$link['ugc'] = $this->ugc();

		return apply_filters( 'eafl_link_data', $link, $this );
	}

	/**
	 * Get link data for the manage page.
	 *
	 * @since    3.0.0
	 */
	public function get_data_manage() {
		$link = $this->get_data();

		$link['clicks'] = EAFL_Clicks::summary( $this->id() );
		$link['date'] = $this->date();
		$link['shortlink'] = $this->shortlink();
		$link['status'] = $this->status();
		$link['status_ignore'] = $this->status_ignore();

		$link['replacement_name'] = '';
		if ( $link['replacement'] ) {
			$replacement = EAFL_Link_Manager::get_link( $link['replacement'] );

			if ( $replacement ) {
				$link['replacement_name'] = $replacement->name();
			} else {
				$link['replacement_name'] = 'n/a';
			}
		}

		return apply_filters( 'eafl_link_manage_data', $link, $this );
	}

	/**
	 * Get metadata value.
	 *
	 * @since    2.0.0
	 * @param    mixed $field   Metadata field to retrieve.
	 * @param	 mixed $default	Default to return if metadata is not set.
	 */
	public function meta( $field, $default ) {
		if ( ! $this->meta ) {
			$this->meta = get_post_custom( $this->id() );
		}

		if ( isset( $this->meta[ $field ] ) && null !== $this->meta[ $field ][0] ) {
			$value = $this->meta[ $field ][0];
		} else {
			$value = $default;
		}

		return apply_filters( 'eafl_link_meta', $value, $this, $field, $default );
	}

	/**
	 * Get the link ID.
	 *
	 * @since    2.0.0
	 */
	public function id() {
		return $this->post ? $this->post->ID : 0;
	}

	/**
	 * Get the link post status.
	 *
	 * @since    3.4.0
	 */
	public function post_status() {
		return $this->post ? $this->post->post_status : 'publish';
	}

	/**
	 * Get the link type.
	 *
	 * @since    3.4.0
	 */
	public function type() {
		return $this->meta( 'eafl_type', 'text' );
	}

	/**
	 * Get the link categories.
	 *
	 * @since    2.0.0
	 */
	public function categories() {
		$terms = get_the_terms( $this->id(), 'eafl_category' );

		return is_array( $terms ) ? $terms : array();
	}

	/**
	 * Get the link categories as a list.
	 *
	 * @since    2.0.0
	 */
	public function categories_list() {
		$terms = $this->categories();
		$term_list = wp_list_pluck( $terms, 'name' );

		return implode( ', ', $term_list );
	}

	/**
	 * Get the link cloak setting.
	 *
	 * @since    2.5.0
	 * @param    mixed $keep_default If false default will be replaced by the actual default value.
	 */
	public function cloak( $keep_default = false ) {
		$cloak = $this->meta( 'eafl_cloak', 'default' );

		if ( ! $keep_default && 'default' === $cloak ) {
			$cloak = EAFL_Settings::get( 'default_cloak' );
		}
		return $cloak;
	}

	/**
	 * Get the link publish date.
	 *
	 * @since    3.0.0
	 */
	public function date() {
		return $this->post->post_date;
	}

	/**
	 * Get the link description.
	 *
	 * @since    2.0.0
	 */
	public function description() {
		return $this->meta( 'eafl_description', '' );
	}

	/**
	 * Get the link replacement.
	 *
	 * @since    3.7.0
	 */
	public function replacement() {
		$replacement = intval( $this->meta( 'eafl_replacement', 0 ) );

		return $replacement && $replacement !== $this->id() ? $replacement : false;
	}

	/**
	 * Get the link name.
	 *
	 * @since    2.0.0
	 */
	public function name() {
		return $this->post ? $this->post->post_title : '';
	}

	/**
	 * Get the link nofollow type.
	 *
	 * @since    2.0.0
	 * @param    mixed $keep_default If false default will be replaced by the actual default value.
	 */
	public function nofollow( $keep_default = false ) {
		$nofollow = $this->meta( 'eafl_nofollow', 'default' );

		if ( ! $keep_default && 'default' === $nofollow ) {
			$nofollow = EAFL_Settings::get( 'default_nofollow' );
		}
		return $nofollow;
	}

	/**
	 * Wether the link is sponsored.
	 *
	 * @since    3.3.0
	 */
	public function sponsored() {
		return $this->meta( 'eafl_sponsored', false );
	}

	/**
	 * Wether the link is User Generated Content.
	 *
	 * @since    3.3.0
	 */
	public function ugc() {
		return $this->meta( 'eafl_ugc', false );
	}

	/**
	 * Get the link prefix.
	 *
	 * @since    2.0.0
	 */
	public function prefix() {
		return $this->meta( 'eafl_prefix', '' );
	}

	/**
	 * Get the link redirect type.
	 *
	 * @since    2.0.0
	 * @param    mixed $keep_default If false default will be replaced by the actual default value.
	 */
	public function redirect_type( $keep_default = false ) {
		$redirect_type = $this->meta( 'eafl_redirect_type', 'default' );

		if ( ! $keep_default && ( 'default' === $redirect_type || 999 === $redirect_type ) ) {
			$redirect_type = intval( EAFL_Settings::get( 'default_redirect_type' ) );
		}
		return $redirect_type;
	}

	/**
	 * Get the shortlink.
	 *
	 * @since    2.0.0
	 */
	public function shortlink() {
		return $this->post ? get_permalink( $this->post->ID ) : '';
	}

	/**
	 * Get the link slug.
	 *
	 * @since    2.0.0
	 */
	public function slug() {
		return $this->post ? $this->post->post_name : '';
	}

	/**
	 * Get the link target type.
	 *
	 * @since    2.0.0
	 * @param    mixed $keep_default If false default will be replaced by the actual default value.
	 */
	public function target( $keep_default = false ) {
		$target = $this->meta( 'eafl_target', 'default' );

		if ( ! $keep_default && 'default' === $target ) {
			$target = EAFL_Settings::get( 'default_target' );
		}
		return $target;
	}

	/**
	 * Get the link text.
	 *
	 * @since    2.0.0
	 */
	public function text() {
		$text = maybe_unserialize( $this->meta( 'eafl_text', '' ) );
		return is_array( $text ) ? $text : array( $text );
	}

	/**
	 * Get the link HTML.
	 *
	 * @since    3.4.0
	 */
	public function html() {
		return $this->meta( 'eafl_html', '' );
	}

	/**
	 * Get the link classes.
	 *
	 * @since    3.4.0
	 */
	public function classes() {
		return $this->meta( 'eafl_classes', '' );
	}

	/**
	 * Get the link url.
	 *
	 * @since    2.0.0
	 */
	public function url() {
		return $this->meta( 'eafl_url', '' );
	}

	/**
	 * Get the link status.
	 *
	 * @since	3.1.0
	 */
	public function status() {
		return maybe_unserialize( $this->meta( 'eafl_status_details', array() ) );
	}

	/**
	 * Get the link ignore status.
	 *
	 * @since	3.4.0
	 */
	public function status_ignore() {
		return $this->meta( 'eafl_status_ignore', false );
	}
}
