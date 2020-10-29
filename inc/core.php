<?php
/**
 * Core-Funktionen
 *
 * @since   1.0.0
 * @author  Marco Di Bella <mdb@marcodibella.de>
 */


defined( 'ABSPATH' ) or exit;



/**
 * Exportiert die Teilnehmerliste als CSV
 *
 * @since   1.0.0
 * @todo    - Anwendung von $event-id innerhalb der SQL-Abfrage
 *          - Download der Datei vom Server
 *
 * @param   int     $event_id
 */

function cmkk_export_teilnehmer( $event_id )
{
    // Neue Datei erstellen
    $file_name = 'kartenkontingent-export-' . date("Y-m-d") . '.csv';
    $file      = fopen( PLUGIN_PATH . '/' . $file_name, 'w' );


    // Kopfzeile in Datei schreiben
    $row = array( 'Nachname', 'Vorname', 'E-Mail', 'Anmeldezeitpunkt' );
    fputcsv( $file, $row);


    // Daten abrufen und in Datei schreiben
    global $wpdb;

    $table_name = $wpdb->prefix . TABLE_USER;
    $table_data = $wpdb->get_results( "SELECT nachname, vorname, email, zeitpunkt FROM $table_name", 'ARRAY_A' );

    foreach( $table_data as $row ) :
        fputcsv( $file, $row );
    endforeach;


    // Datei schließen
    fclose( $file );
}



/**
 * Gibt die Gesamtzahl der zur Verfügung stehenden Karten zurück
 *
 * @since   1.0.0
 *
 * @param   int     $event_id
 * @return  int     die Gesamtzahl der Tickets
 */

function cmkk_get_total_amount( $event_id )
{
    global $wpdb;
           $amount = 0;

    $table_name = $wpdb->prefix . TABLE_POOL;
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
 *
 * @param   int     $event_id
 * @return  int     die genutzten Plätze
 */

function cmkk_get_used_amount( $event_id )
{
    global $wpdb;

    $table_name = $wpdb->prefix . TABLE_POOL;
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
 * @todo    - Validation der Übergabewerte
 *
 * @param   int     $event_id
 * @return  int     die noch freien Plätze (im Zweifel 0)
 */

function cmkk_get_free_amount( $event_id )
{
    global $wpdb;

    $total = cmkk_get_total_amount( $event_id );
    $used  = cmkk_get_used_amount( $event_id );

    return max( 0, $total - $used );
}



/**
 * Erweitert den Kartenpool durch Hinzufügen eines Kartenkontingents
 *
 * @since   1.0.0
 * @todo    - Validation der Übergabewerte
 *          - Rückgabe auf true/false begrenzen?
 *
 * @param   int     $event_id
 * @param   int     $groesse
 * @param   string  $anbieter
 */

function cmkk_add_contingent( $event_id, $groesse, $anbieter )
{
    global $wpdb;

    $table_name = $wpdb->prefix . TABLE_POOL;
    $result     = $wpdb->insert( $table_name, array(
        'event_id'           => $event_id,
        'groesse'            => $groesse,
        'bereitgestellt_von' => $anbieter,
    ) );

    return $result;
}


/**
 * Erweitert den Kartenpool durch Hinzufügen eines Kartenkontingents
 *
 * @since   1.0.0
 * @todo    - Validation der Übergabewerte
 *          - Versendung einer Informationsmail an den Benutzer
 *
 * @param   int     $event_id
 * @param   string  $nachname
 * @param   string  $vorname
 * @param   string  $email
 */

function cmkk_add_user( $event_id, $nachname, $vorname, $email )
{
    global $wpdb;

    if( 0 != cmkk_get_free_amount( $event_id ) ) :
        $table_name = $wpdb->prefix . TABLE_POOL;
        $result     = $wpdb->insert( $table_name, array(
            'event_id' => $event_id,
            'nachname' => $nachname,
            'vorname'  => $vorname,
            'email'    => $email,
        ) );

        if( 1 == $result ) :

            // Versand der E-Mail

            return true;
        endif;
    endif;

    return false;
}
