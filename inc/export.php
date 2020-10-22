<?php
/**
 * Export 2 CSV dysfunctional
 *
 * @author Marco Di Bella <mdb@marcodibella.de>
 **/


// Check & Quit
defined( 'ABSPATH' ) OR exit;


define( 'TABLE_TEILNEHMER', 'cm_kartenkontingent_teilnehmer' );
define( 'PLUGIN_PATH', __DIR__ );


require_once( ABSPATH . 'wp-admin/includes/wp-db.php' );



/** Neue Datei erstellen **/

$file_name = 'kartenkontingent-export-' . date("Y-m-d") . 'csv';
$file      = fopen( PLUGIN_PATH . '/' . $file_name, 'w' );


/** Kopfzeile in Datei schreiben **/

$row = array( 'Nachname', 'Vorname', 'E-Mail', 'Anmeldezeitpunkt' );
fputcsv( $file, $row);


/** Daten abrufen und in Datei schreiben **/

global $wpdb;

$table_name = $wpdb->prefix . TABLE_TEILNEHMER;
$table_data = $wpdb->get_results( "SELECT nachname, vorname, email, zeitpunkt FROM $table_name", 'ARRAY_A' );

foreach( $table_data as $row ) :
    fputcsv( $file, $row );
endforeach;

//    fclose( $file );

    /** Datei schließen **/

    /*//fclose( $file );
    header( 'Content-type: text/csv' );
    header( 'Content-disposition:attachment; filename="' . PLUGIN_PATH . '/' . $file_name.'"' );
    readfile( PLUGIN_PATH . '/' . $file_name );
*/

    fseek( $file, 0 );
    header( 'Content-Type: application/csv' );
    header( 'Content-Disposition: attachment; filename="'. PLUGIN_PATH . '/' . $file_name.'"' );
    fpassthru( $file );
