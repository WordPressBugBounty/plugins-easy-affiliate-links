<?php
/**
 * Template for the import CSV mapping page.
 *
 * @link       https://bootstrapped.ventures
 * @since      3.0.0
 *
 * @package    Easy_Affiliate_Links
 * @subpackage Easy_Affiliate_Links/templates/admin/menu/manage
 */

$url = add_query_arg( 'sub', 'import_csv', admin_url( 'admin.php?page=eafl_import_export' ) );

?>

<h2><?php esc_html_e( 'CSV Preview', 'easy-affiliate-links' ); ?></h2>

<?php
	$nbr_columns = count( $links[0] );
?>
<table class="eafl-import-csv-preview">
	<thead>
	<tr>
	<th>&nbsp;</th>
	<?php
	for ( $i = 1; $i <= $nbr_columns; $i++ ) {
		echo '<th>' . esc_html__( 'Column', 'easy-affiliate-links' ) . '&nbsp;' . intval( $i ) . '</th>';
	}
	?>
	</tr>
	</thead>
	<tbody>
	<?php
	$preview_rows = min( 5, count( $links ) );
	for ( $j = 0; $j < $preview_rows; $j++ ) {
		echo '<tr>';
		echo '<th>' . esc_html__( 'Row', 'easy-affiliate-links' ) . '&nbsp;' . esc_html( $j + 1 ) . '</th>';
		for ( $i = 0; $i < $nbr_columns; $i++ ) {
			echo '<td>' . esc_html( $links[ $j ][ $i ] ) . '</td>';
		}
		echo '</tr>';
	}
	?>
	</tbody>
</table>
<h2><?php esc_html_e( 'Column Matching', 'easy-affiliate-links' ); ?></h2>
<form method="POST" action="<?php echo esc_url( $url ); ?>" class="eafl-import-csv-mapping" enctype="multipart/form-data">
	<?php wp_nonce_field( 'eafl_import_mapping', 'eafl_import_mapping' ); ?>
	<div>
	<label for="eafl-skip-first-row"><?php esc_html_e( 'Skip first row', 'easy-affiliate-links' ); ?></label>
		<input type="checkbox" name="eafl_skip_first_row" id="eafl-skip-first-row">
	</div>
	<?php
	$column_select_options = '<option value="">Do not import</option>';
	for ( $i = 0; $i < $nbr_columns; $i++ ) {
		$column_select_options .= '<option value="' . esc_attr( $i ) . '">' . __( 'Column', 'easy-affiliate-links' ) . ' ' . esc_html( $i + 1 ) . '</option>';
	}
	?>
	<br/>
	<div>
		<label for="eafl-column-id"><?php esc_html_e( 'ID', 'easy-affiliate-links' ); ?></label>
		<select name="eafl_column_id" id="eafl-column-id">
			<?php echo $column_select_options; ?>
		</select> <span><?php _e( 'If a link with this ID exists, it will get updated. Set to "Do not import" to create new links.' , 'easy-affiliate-links' ); ?></span>
	</div>
	<div>
		<label for="eafl-column-name"><?php esc_html_e( 'Name', 'easy-affiliate-links' ); ?></label>
		<select name="eafl_column_name" id="eafl-column-name">
			<?php echo $column_select_options; ?>
		</select>
	</div>
	<div>
		<label for="eafl-column-description"><?php esc_html_e( 'Description', 'easy-affiliate-links' ); ?></label>
		<select name="eafl_column_description" id="eafl-column-description">
			<?php echo $column_select_options; ?>
		</select>
	</div>
	<div>
		<label for="eafl-column-categories"><?php esc_html_e( 'Categories', 'easy-affiliate-links' ); ?></label>
		<select name="eafl_column_categories" id="eafl-column-categories">
			<?php echo $column_select_options; ?>
		</select>
	</div>
	<div>
		<label for="eafl-column-text"><?php esc_html_e( 'Text', 'easy-affiliate-links' ); ?></label>
		<select name="eafl_column_text" id="eafl-column-text">
			<?php echo $column_select_options; ?>
		</select>
	</div>
	<div>
		<label for="eafl-column-url"><?php esc_html_e( 'URL', 'easy-affiliate-links' ); ?></label>
		<select name="eafl_column_url" id="eafl-column-url">
			<?php echo $column_select_options; ?>
		</select>
	</div>
	<div>
		<label for="eafl-column-slug"><?php esc_html_e( 'Slug', 'easy-affiliate-links' ); ?></label>
		<select name="eafl_column_slug" id="eafl-column-slug">
			<?php echo $column_select_options; ?>
		</select>
	</div>
	<?php submit_button( __( 'Import CSV', 'easy-affiliate-links' ) ); ?>
</form>
