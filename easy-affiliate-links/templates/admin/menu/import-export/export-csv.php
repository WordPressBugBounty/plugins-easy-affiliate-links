<?php
/**
 * Template for the CSV export.
 *
 * @link       https://bootstrapped.ventures
 * @since      3.6.0
 *
 * @package    Easy_Affiliate_Links
 * @subpackage Easy_Affiliate_Links/templates/admin/menu/import-export
 */

header( 'Content-type: text/csv' );
header( 'Content-Disposition: attachment; filename="EAFL_Links.csv"' );

$export_links = isset( $_POST['exportLinks'] ) ? base64_decode( $_POST['exportLinks'] ) : 'Link export failed.';
echo $export_links;

