<?php
/**
 * Responsible for the clicks database.
 *
 * @link       https://bootstrapped.ventures
 * @since      2.1.0
 *
 * @package    Easy_Affiliate_Links
 * @subpackage Easy_Affiliate_Links/includes/public
 */

/**
 * Responsible for the clicks database.
 *
 * @since      2.1.0
 * @package    Easy_Affiliate_Links
 * @subpackage Easy_Affiliate_Links/includes/public
 * @author     Brecht Vandersmissen <brecht@bootstrapped.ventures>
 */
class EAFL_Clicks_Database {
	/**
	 * Current version of the click database structure.
	 *
	 * @since    2.1.0
	 * @access   private
	 * @var      mixed $database_version Current version of the click database structure.
	 */
	private static $database_version = '1.0';

	/**
	 * Fields in the clicks database table.
	 *
	 * @since    2.1.1
	 * @access   private
	 * @var      mixed $fields Fields in the clicks database table.
	 */
	private static $fields = array( 'id', 'date', 'link_id', 'user_id', 'ip', 'referer', 'request', 'agent', 'is_mobile', 'is_tablet', 'is_desktop' );

	/**
	 * Register actions and filters.
	 *
	 * @since    2.1.0
	 */
	public static function init() {
		add_action( 'plugins_loaded', array( __CLASS__, 'check_database_version' ), 1 );
		add_action( 'admin_init', array( __CLASS__, 'check_database_exists' ) );
	}

	/**
	 * Check if the correct database version is present.
	 *
	 * @since    2.1.0
	 */
	public static function check_database_version() {
		$current_version = get_option( 'eafl_click_db_version', '0.0' );

		if ( version_compare( $current_version, self::$database_version ) < 0 ) {
			self::update_database( $current_version );
		}
	}

	/**
	 * Check if the database actually exists.
	 *
	 * @since    3.2.0
	 */
	public static function check_database_exists() {
		global $wpdb;
		$table = self::get_table_name();

		if ( ! $wpdb->get_var( "SHOW TABLES LIKE '$table'" ) ) {
			self::update_database( '0.0' );
		}
	}

	/**
	 * Create or update the click database.
	 *
	 * @since    2.1.0
	 * @param    mixed $from Database version to update from.
	 */
	public static function update_database( $from ) {
		if ( '0.0' === $from ) {
			global $wpdb;

			$table_name = self::get_table_name();
			$charset_collate = $wpdb->get_charset_collate();

			$sql = "CREATE TABLE $table_name (
			id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
			date datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
			link_id bigint(20) unsigned NOT NULL,
			user_id bigint(20) unsigned NOT NULL DEFAULT '0',
			ip varchar(39) DEFAULT '' NOT NULL,
			referer varchar(3000) DEFAULT '' NOT NULL,
			request varchar(1000) DEFAULT '' NOT NULL,
			agent varchar(3000) DEFAULT '' NOT NULL,
			is_mobile tinyint(1) DEFAULT '0' NOT NULL,
			is_tablet tinyint(1) DEFAULT '0' NOT NULL,
			is_desktop tinyint(1) DEFAULT '0' NOT NULL,
			PRIMARY KEY  (id),
			KEY date (date),
			KEY link_id (link_id)
			) $charset_collate;";

			require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
			dbDelta( $sql );
		}

		update_option( 'eafl_click_db_version', self::$database_version );
	}

	/**
	 * Get the name of the clicks database table.
	 *
	 * @since    2.1.0
	 */
	public static function get_table_name() {
		global $wpdb;
		return $wpdb->prefix . 'eafl_clicks';
	}

	/**
	 * Add a click to the database.
	 *
	 * @since    2.1.0
	 * @param    mixed $unsanitized_click Click to add to the database.
	 */
	public static function add_click( $unsanitized_click ) {
		// Sanitize Click fields.
		$click = array();
		$click['date'] = isset( $unsanitized_click['date'] ) && $unsanitized_click['date'] ? $unsanitized_click['date'] : current_time( 'mysql' );
		$click['link_id'] = isset( $unsanitized_click['link_id'] ) ? intval( $unsanitized_click['link_id'] ) : 0;
		$click['user_id'] = isset( $unsanitized_click['user_id'] ) ? intval( $unsanitized_click['user_id'] ) : 0;
		$click['ip'] = isset( $unsanitized_click['ip'] ) && $unsanitized_click['ip'] ? $unsanitized_click['ip'] : '';
		$click['referer'] = isset( $unsanitized_click['referer'] ) && $unsanitized_click['referer'] ? $unsanitized_click['referer'] : '';
		$click['request'] = isset( $unsanitized_click['request'] ) && $unsanitized_click['request'] ? $unsanitized_click['request'] : '';
		$click['agent'] = isset( $unsanitized_click['agent'] ) && $unsanitized_click['agent'] ? $unsanitized_click['agent'] : '';
		$click['is_mobile'] = isset( $unsanitized_click['is_mobile'] ) && $unsanitized_click['is_mobile'] ? 1 : 0;
		$click['is_tablet'] = isset( $unsanitized_click['is_tablet'] ) && $unsanitized_click['is_tablet'] ? 1 : 0;
		$click['is_desktop'] = isset( $unsanitized_click['is_desktop'] ) && $unsanitized_click['is_desktop'] ? 1 : 0;

		// Remove IP address based on setting.
		if ( $click['ip'] && ! EAFL_Settings::get( 'store_ip_address' ) ) {
			$click['ip'] = md5( $click['ip'] );
		}

		// Insert click.
		global $wpdb;
		$table_name = self::get_table_name();

		$wpdb->insert( $table_name, $click );
	}

	/**
	 * Query clicks.
	 *
	 * @since    2.1.1
	 * @param    mixed $args Arguments for the query.
	 */
	public static function get_clicks( $args ) {
		global $wpdb;
		$table_name = self::get_table_name();

		// Sanitize arguments.
		$order = isset( $args['order'] ) ? strtoupper( $args['order'] ) : '';
		$order = in_array( $order, array( 'ASC', 'DESC' ), true ) ? $order : 'DESC';

		$orderby = isset( $args['orderby'] ) ? strtolower( $args['orderby'] ) : '';
		$orderby = in_array( $orderby, self::$fields, true ) ? $orderby : 'date';

		$offset = isset( $args['offset'] ) ? intval( $args['offset'] ) : 0;
		$limit = isset( $args['limit'] ) ? intval( $args['limit'] ) : 0;

		$where = isset( $args['where'] ) ? trim( $args['where'] ) : '';

		// Query clicks.
		$query_where = $where ? ' WHERE ' . $where : '';
		$query_order = ' ORDER BY ' . $orderby . ' ' . $order;
		$query_limit = $limit ? ' LIMIT ' . $offset . ',' . $limit : '';

		// Count without limit.
		$query_count = 'SELECT count(*) FROM ' . $table_name . $query_where;
		$count = $wpdb->get_var( $query_count );

		// Query clicks.
		$query_clicks = 'SELECT * FROM ' . $table_name . $query_where . $query_order . $query_limit;
		$clicks = $wpdb->get_results( $query_clicks );

		return array(
			'total' => $count,
			'clicks' => $clicks,
		);
	}

	/**
	 * Delete a single click.
	 *
	 * @since    3.1.0
	 * @param    array $ids Click ID to delete.
	 */
	public static function delete_click( $id ) {
		self::delete_clicks( array( $id ) );
	}

	/**
	 * Delete a set of clicks.
	 *
	 * @since    2.1.0
	 * @param    mixed $ids Click IDs to delete.
	 */
	public static function delete_clicks( $ids ) {
		global $wpdb;
		$table_name = self::get_table_name();

		if ( is_array( $ids ) ) {
			// Delete all these click IDs.
			$ids = implode( ',', array_map( 'intval', $ids ) );
			$wpdb->query( 'DELETE FROM ' . $table_name . ' WHERE ID IN (' . $ids . ')' );
		}
	}

	/**
	 * Delete clicks for a specific link.
	 *
	 * @since    3.1.0
	 * @param    int $link_id Link ID to delete the clicks for.
	 */
	public static function delete_clicks_for( $link_id ) {
		global $wpdb;
		$table_name = self::get_table_name();

		$wpdb->delete( $table_name, array( 'link_id' => $link_id ), array( '%d' ) );

		update_post_meta( $link_id, 'eafl_clicks_summary', 0 );
		update_post_meta( $link_id, 'eafl_clicks_total', 0 );
	}

	/**
	 * Delete all clicks.
	 *
	 * @since    2.5.0
	 */
	public static function delete_all_clicks() {
		global $wpdb;
		$table_name = self::get_table_name();
		$wpdb->query( 'TRUNCATE TABLE ' . $table_name );
	}

	/**
	 * Count all the clicks for a specific link or all clicks in general.
	 *
	 * @since    2.1.0
	 * @param    mixed $link_id Link to count the clicks for.
	 */
	public static function count_clicks( $link_id = false ) {
		global $wpdb;
		$table_name = self::get_table_name();

		$query_where = $link_id ? ' WHERE link_id = ' . intval( $link_id ) : '';

		$query_count = 'SELECT count(*) FROM ' . $table_name . $query_where;
		return intval( $wpdb->get_var( $query_count ) );
	}

	/**
	 * Count the clicks for a specific link during a specific month.
	 *
	 * @since    3.6.0
	 * @param    mixed $link_id	Link to count the clicks for.
	 * @param    int $year		Year to count the clicks for.
	 * @param    int $month		Month to count the clicks for.
	 */
	public static function count_clicks_for_month( $link_id, $year, $month ) {
		global $wpdb;
		$table_name = self::get_table_name();

		$query_where = ' WHERE link_id = ' . intval( $link_id ) . ' AND YEAR(date) = ' . intval( $year ) . ' AND MONTH(date) = ' . intval( $month );

		$query_count = 'SELECT count(*) FROM ' . $table_name . $query_where;
		return intval( $wpdb->get_var( $query_count ) );
	}
}

EAFL_Clicks_Database::init();
