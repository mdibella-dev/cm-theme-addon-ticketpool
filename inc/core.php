<?php
/**
 * Core-Funktionen
 *
 * @author Marco Di Bella <mdb@marcodibella.de>
 **/


// Check & Quit
defined( 'ABSPATH' ) OR exit;



/**
 * Exportiert die Teilnehmerliste als CSV
 *
 * @since   1.0.0
 * @param   int     $event_id
 * @todo    nach Event filtern
 */

function cm_kk_export_teilnehmer( $event_id )
{
    /** Neue Datei erstellen **/

    $file_name = 'kartenkontingent-export-' . date("Y-m-d") . '.csv';
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


    /** Datei schließen **/

    /*//fclose( $file );
    header( 'Content-type: text/csv' );
    header( 'Content-disposition:attachment; filename="' . PLUGIN_PATH . '/' . $file_name.'"' );
    readfile( PLUGIN_PATH . '/' . $file_name );
*/
/*
    fseek( $file, 0 );
    header( 'Content-Type: application/csv' );
    header( 'Content-Disposition: attachment; filename="'. PLUGIN_PATH . '/' . $file_name.'"' );
    fpassthru( $file );
*/
    fclose( $file );
}



/**
 * Gibt die Gesamtzahl der zur Verfügung stehenden Karten zurück
 *
 * @since   1.0.0
 * @param   int     $event_id
 * @return  int     die Gesamtzahl der Tickets
 */

function cm_kk_get_total_amount( $event_id )
{
    global $wpdb;
           $amount = 0;

    $table_name = $wpdb->prefix . TABLE_KONTINGENT;
    $query      = "SELECT groesse FROM $table_name WHERE event_id=$event_id";
    $table_data = $wpdb->get_results( $query, 'ARRAY_N' );

    if( NULL != $table_data ) :
        foreach( $table_data as $value ) :
            $amount += $value[0];
        endforeach;
    endif;

    return $amount;
}



/**
 * Ermittelt die Anzahl der vom Gesamtkontingent bereits genutzten Plätze
 *
 * @since   1.0.0
 * @param   int     $event_id
 * @return  int     die genutzten Plätze
 */

function cm_kk_get_used_amount( $event_id )
{
    global $wpdb;

    $table_name = $wpdb->prefix . TABLE_TEILNEHMER;
    $query      = "SELECT COUNT(*) FROM $table_name WHERE event_id=$event_id";
    $table_data = $wpdb->get_results( $query, 'ARRAY_N' );

    if( NULL != $table_data ) :
        return $table_data[ 0 ][ 0 ];
    else :
        return 0;
    endif;
}


/**
 * Ermittelt die Anzahl der vom Gesamtkontingent noch zur Verfügung stehenden Plätze
 *
 * @since   1.0.0
 * @param   int     $event_id
 * @return  int     die noch freien Plätze (im Zweifel 0)
 */

function cm_kk_get_free_amount( $event_id )
{
    global $wpdb;

    $total = cm_kk_get_total_amount( $event_id );
    $used  = cm_kk_get_used_amount( $event_id );

    return max( 0, $total - $used );
}



/**
 * Erweitert den Kartenpool durch Hinzufügen eines Kartenkontingents
 *
 * @since   1.0.0
 * @param   int     $event_id
 * @param   int     $groesse
 * @param   string  $anbieter
 */

function cm_kk_add_contingent( $event_id, $groesse, $anbieter )
{
    global $wpdb;
/* add checks ?? */
    $table_name = $wpdb->prefix . TABLE_KONTINGENT;
    $result     = $wpdb->insert( $table_name, array(
        'event_id'           => $event_id,
        'groesse'            => $groesse,
        'bereitgestellt_von' => $anbieter,
    ) );

    return $result;  // true or false?
}


/**
 * Erweitert den Kartenpool durch Hinzufügen eines Kartenkontingents
 *
 * @since   1.0.0
 * @param   int     $event_id
 * @param   string  $nachname
 * @param   string  $vorname
 * @param   string  $email
 */

function cm_kk_add_beneficiary( $event_id, $nachname, $vorname, $email )
{
    global $wpdb;
//echo $event_id .' '. $nachname .' '. $vorname .' '. $email;

    if( 0 != cm_kk_get_free_amount( $event_id ) ) :

        /* add checks ?? */

        $table_name = $wpdb->prefix . TABLE_TEILNEHMER;
        $result     = $wpdb->insert( $table_name, array(
            'event_id' => $event_id,
            'nachname' => $nachname,
            'vorname'  => $vorname,
            'email'    => $email,
        ) );

        if( 1 == $result ) :
            /* sendmail */
            return true;
        endif;
    endif;

    return false;
}
