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
 *
 * @param   int     $event_id
 * @return  bool    FALSE im Fehlerfall
 * @return  array   Informationen zur Export-Datei im Erfolgsfall
 */

function cmkk_create_user_export_file( $event_id )
{
    $uploads   = wp_upload_dir();
    $file_name = 'kartenkontingent-export-' . date( "Y-m-d" ) . '.csv';
    $file_info = array(
        'name' => $file_name,
        'path' => $uploads['basedir'] . '/' . EXPORT_FOLDER . '/' . $file_name,
        'url'  => $uploads['baseurl'] . '/' . EXPORT_FOLDER . '/' . $file_name,
    );

    // Datei öffnen
    $file = fopen( $file_info['path'], 'w' );

    if( FALSE === $file) :
        return NULL;
    endif;

    // Kopfzeile in Datei schreiben
    $row = array( 'Nachname', 'Vorname', 'E-Mail', 'Anmeldezeitpunkt' );
    fputcsv( $file, $row);

    // Daten abrufen und in Datei schreiben
    global $wpdb;

    $table_name = $wpdb->prefix . TABLE_USER;
    $sql        = "SELECT user_lastname, user_forename, user_email, user_registered FROM $table_name";
    $table_data = $wpdb->get_results( $sql, 'ARRAY_A' );

    foreach( $table_data as $row ) :
        fputcsv( $file, $row );
    endforeach;

    // Datei schließen
    fclose( $file );

    return $file_info;
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
 *          - Early Return notwendig
 *          - Yoda-Condition
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
 * Fügt einen Benutzer hinzu
 *
 * @since   1.0.0
 * @todo    - BCC-Adresse im Backend einrichten
 *
 * @param   int     $event_id
 * @param   string  $user_lastname
 * @param   string  $user_forename
 * @param   string  $user_email
 * @return  int     ein Statuscode
 */

function cmkk_add_user( $event_id, $user_lastname, $user_forename, $user_email )
{
    // Ist noch ein Platz frei?
    if( 0 === cmkk_get_free_amount( $event_id ) ) :
        return STATUS_NOTHING_FREE;
    endif;


    // Leere Felder übergeben?
    if( empty( $user_forename ) or empty( $user_lastname ) or empty( $user_email ) ):
        return STATUS_USER_FIELDS_EMPTY;
    endif;


    // Ist das Format der E-Mail gültig?
    if( !filter_var( $user_email, FILTER_VALIDATE_EMAIL ) ) :
        return STATUS_USER_EMAIL_MALFORMED;
    endif;


    // Ist die E-Mail des Users bereits im Gebrauch?
    if( TRUE === cmkk_is_email_in_use( $event_id, $user_email ) ) :
        return STATUS_USER_EMAIL_IN_USE;
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


    // War die Eintragung des Users erfolgreich?
    if( 0 === $wpdb->insert( $table_name, $table_data ) ) :
        return STATUS_CANT_STORE_USER;
    endif;


    // E-Mail an User senden
    $mail_to      = $user_email;
    $mail_subject = get_option( OPTION_MAIL_SUBJECT );
    $mail_message = get_option( OPTION_MAIL_MESSAGE );
    $mail_headers = array( 'bcc:r.keller@pwg-seminare.de' );
    $result       = wp_mail( $mail_to, $mail_subject, $mail_message, $mail_headers );

    return STATUS_USER_ADDED;
}



/**
 * Gibt einen Hinweis passend zum jeweiligen Statuscode aus
 *
 * @since   1.0.0
 *
 * @param   int     $code    der Statuscode
 */

function cmkk_display_notice( $code )
{
    $status = array(
        STATUS_USER_ADDED           => array(
            'notice' => __( 'Ihre Anmeldung war erfolgreich!', 'cmkk' ),
            'style'  => 'cmkk-notice-sucess',
        ),
        STATUS_NOTHING_FREE         => array(
            'notice' => __( 'Leider ist derzeit kein freier Platz im Kartenkontingent verfügbar!<br><br>Bitte versuchen Sie es zu einem späteren Zeitpunkt erneut.', 'cmkk' ),
            'style'  => 'cmkk-notice-info',
        ),
        STATUS_USER_FIELDS_EMPTY    => array(
            'notice' => __( 'Ein oder mehrere Felder sind nicht ausgefüllt.', 'cmkk' ),
            'style'  => 'cmkk-notice-warning',
        ),
        STATUS_USER_EMAIL_MALFORMED => array(
            'notice' => __( 'Bitte geben Sie eine korrekte E-Mail-Adresse ein.', 'cm_kk' ),
            'style'  => 'cmkk-notice-warning',
        ),
        STATUS_USER_EMAIL_IN_USE    => array(
            'notice' => __( 'Ihre E-Mail-Adresse wurde bereits verwendet. Sie kann nicht ein weiteres mal verwendet werden.', 'cm_kk' ),
            'style'  => 'cmkk-notice-warning',
        ),
        STATUS_CANT_STORE_USER      => array(
            'notice' => __( 'Ein technischer Fehler ist aufgetreten.', 'cmkk' ),
            'style'  => 'cmkk-notice-error',
        ),
    );

    if( array_key_exists( $code, $status ) ) :
?>
<div class="cmkk-notice <?php echo $status[ $code ]['style']; ?>">
    <p><?php echo $status[ $code ]['notice']; ?></p>
</div>
<?php
    endif;

}
