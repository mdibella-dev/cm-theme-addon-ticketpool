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
    $file_name = 'kartenkontingent-export-' . date( "Y-m-d" ) . '.csv';
    $file      = fopen( PLUGIN_PATH . '/' . $file_name, 'w' );


    // Kopfzeile in Datei schreiben
    $row = array( 'Nachname', 'Vorname', 'E-Mail', 'Anmeldezeitpunkt' );
    fputcsv( $file, $row);


    // Daten abrufen und in Datei schreiben
    global $wpdb;

    $table_name = $wpdb->prefix . TABLE_USER;
    $sql        = "SELECT user_lastname, user_forename, user_mail, user_registered FROM $table_name";
    $table_data = $wpdb->get_results( $sql, 'ARRAY_A' );

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
    $sql        = "SELECT contingent_size FROM $table_name WHERE event_id=$event_id";
    $table_data = $wpdb->get_results( $sql, 'ARRAY_N' );

    if( NULL != $table_data ) :
        foreach( $table_data as $size ) :
            $amount += $size[0];
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

    $table_name = $wpdb->prefix . TABLE_USER;
    $sql        = "SELECT COUNT(*) FROM $table_name WHERE event_id=$event_id";
    $table_data = $wpdb->get_results( $sql, 'ARRAY_N' );

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
 * @param   int     $contingent_size
 * @param   string  $contingent_provider
 */

function cmkk_add_contingent( $event_id, $contingent_size, $contingent_provider )
{
    if( ( $contingent_size > 0 ) and ! empty( $contingent_provider) ) :
        global $wpdb;

        $table_name = $wpdb->prefix . TABLE_POOL;
        $table_data = array(
            'event_id'            => $event_id,
            'contingent_size'     => $contingent_size,
            'contingent_provider' => $contingent_provider,
        );

        if( 1 == $wpdb->insert( $table_name, $table_data ) ) :
            return TRUE;
        endif;
    endif;

    return FALSE;
}



/**
 * Prüft, ob die angegebene $user_email für ein bestimmtes Event ($event_id) bereits genutzt wurde
 *
 * @since   1.0.0
 *
 * @param   int     $event_id
 * @param   string  $user_email
 * @return  bool    TRUE wenn die E-Mail-Adresse bereits im Gebrauch ist, ansonsten FALSE
 */

function cmkk_is_email_in_use( $event_id, $user_email )
{
    global $wpdb;

    $table_name = $wpdb->prefix . TABLE_USER;
    $sql        = "SELECT * FROM $table_name WHERE event_id='$event_id' AND user_email='$user_email'";
    $table_data = $wpdb->get_results( $sql, 'ARRAY_A' );

    return (bool) ( NULL != $table_data );
}



/**
 * Erweitert den Kartenpool durch Hinzufügen eines Kartenkontingents
 *
 * @since   1.0.0
 * @todo    - Validation der Übergabewerte
 *          - Versendung einer Informationsmail an den Benutzer
 *
 * @param   int     $event_id
 * @param   string  $user_lastname
 * @param   string  $user_forename
 * @param   string  $user_email
 */

function cmkk_add_user( $event_id, $user_lastname, $user_forename, $user_email )
{
    // Ist noch ein Platz frei?
    if( 0 == cmkk_get_free_amount( $event_id ) ) :
        return FALSE;
    endif;


    // Ist die E-Mail des Users bereits im Gebrauch?
    if( TRUE == cmkk_is_email_in_use( $event_id, $user_email ) ) :
        return FALSE;
    endif;


    // User eintragen
    global  $wpdb;
            $table_name = $wpdb->prefix . TABLE_USER;
            $table_data = array(
                'event_id'      => $event_id,
                'user_lastname' => $user_lastname,
                'user_forename' => $user_forename,
                'user_email'    => $user_email,
            );


    // Usereintragung erfolgreich?
    if( 0 === $wpdb->insert( $table_name, $table_data ) ) :
        return FALSE;
    endif;

    $mail_to      = $user_email;
    $mail_subject = get_option( OPTION_MAIL_SUBJECT );
    $mail_message = get_option( OPTION_MAIL_MESSAGE );
    $result       = wp_mail( $mail_to, $mail_subject, $mail_message ); //, $mail_headers );

    return TRUE;
}
