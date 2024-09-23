<?php
/**
 * Responsible for the relations database.
 *
 * @link       https://bootstrapped.ventures
 * @since      3.2.0
 *
 * @package    Easy_Affiliate_Links
 * @subpackage Easy_Affiliate_Links/includes/public
 */

/**
 * Responsible for the relations database.
 *
 * @since      3.2.0
 * @package    Easy_Affiliate_Links
 * @subpackage Easy_Affiliate_Links/includes/public
 * @author     Brecht Vandersmissen <brecht@bootstrapped.ventures>
 */
class EAFL_Relations_Database {
	/**
	 * Current version of the click database structure.
	 *
	 * @since    3.2.0
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
	private static $fields = array( 'id', 'post_id', 'link_id', 'occurrences' );

	/**
	 * Register actions and filters.
	 *
	 * @since    3.2.0
	 */
	public static function init() {
		add_action( 'plugins_loaded', array( __CLASS__, 'check_database_version' ), 1 );
		add_action( 'admin_init', array( __CLASS__, 'check_database_exists' ) );
	}

	/**
	 * Check if the correct database version is present.
	 *
	 * @since    3.2.0
	 */
	public static function check_database_version() {
		$current_version = get_option( 'eafl_relation_db_version', '0.0' );

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
	 * @since    3.2.0
	 * @param    mixed $from Database version to update from.
	 */
	public static function update_database( $from ) {
		if ( '0.0' === $from ) {
			global $wpdb;

			$table_name = self::get_table_name();
			$charset_collate = $wpdb->get_charset_collate();

			$sql = "CREATE TABLE $table_name (
			id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
			post_id bigint(20) unsigned NOT NULL,
			link_id bigint(20) unsigned NOT NULL,
			occurrences smallint unsigned NOT NULL,
			PRIMARY KEY  (id),
			KEY post_id (post_id),
			KEY link_id (link_id)
			) $charset_collate;";

			require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
			dbDelta( $sql );
		}

		update_option( 'eafl_relation_db_version', self::$database_version );
	}

	/**
	 * Get the name of the relations database table.
	 *
	 * @since    3.2.0
	 */
	public static function get_table_name() {
		global $wpdb;
		return $wpdb->prefix . 'eafl_relations';
	}

	/**
	 * Add a relation to the database.
	 *
	 * @since    3.2.0
	 * @param    mixed $unsanitized_relation Relation to add to the database.
	 */
	public static function add( $unsanitized_relation ) {
		// Sanitize fields.
		$relation = array();
		$relation['post_id'] = isset( $unsanitized_relation['post_id'] ) ? intval( $unsanitized_relation['post_id'] ) : 0;
		$relation['link_id'] = isset( $unsanitized_relation['link_id'] ) ? intval( $unsanitized_relation['link_id'] ) : 0;
		$relation['occurrences'] = isset( $unsanitized_relation['occurrences'] ) ? intval( $unsanitized_relation['occurrences'] ) : 0;

		if ( $relation['post_id'] && $relation['link_id'] ) {
			global $wpdb;
			$table_name = self::get_table_name();

			$wpdb->insert( $table_name, $relation );	
		}
	}

	/**
	 * Query clicks.
	 *
	 * @since    3.2.0
	 * @param    mixed $args Arguments for the query.
	 */
	public static function query( $args ) {
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

		// Construct query.
		$query_where = $where ? ' WHERE ' . $where : '';
		$query_order = ' ORDER BY ' . $orderby . ' ' . $order;
		$query_limit = $limit ? ' LIMIT ' . $offset . ',' . $limit : '';

		// Count without limit.
		$query_count = 'SELECT count(*) FROM ' . $table_name . $query_where;
		$count = $wpdb->get_var( $query_count );

		// Query.
		$query = 'SELECT * FROM ' . $table_name . $query_where . $query_order . $query_limit;
		$relations = $wpdb->get_results( $query );

		return array(
			'total' => $count,
			'relations' => $relations,
		);
	}

	/**
	 * Count all the relations.
	 *
	 * @since    3.2.0
	 */
	public static function count( ) {
		global $wpdb;
		$table_name = self::get_table_name();

		$query_count = 'SELECT count(*) FROM ' . $table_name ;
		return intval( $wpdb->get_var( $query_count ) );
	}

	/**
	 * Delete all relations.
	 *
	 * @since    3.2.0
	 */
	public static function delete_all() {
		global $wpdb;
		$table_name = self::get_table_name();
		$wpdb->query( 'TRUNCATE TABLE ' . $table_name );
	}
}

EAFL_Relations_Database::init();
