<?php
/**
 * Template for the XML export.
 *
 * @link       https://bootstrapped.ventures
 * @since      3.0.0
 *
 * @package    Easy_Affiliate_Links
 * @subpackage Easy_Affiliate_Links/templates/admin/menu/import-export
 */

header( 'Content-type: text/xml' );
header( 'Content-Disposition: attachment; filename="EAFL_Links.xml"' );

$export_links = isset( $_POST['exportLinks'] ) ? base64_decode( $_POST['exportLinks'] ) : 'Link export failed.';
echo $export_links;

