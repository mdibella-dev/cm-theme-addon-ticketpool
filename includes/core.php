<?php
/**
 * Core functions.
 *
 * @author  Marco Di Bella
 * @package cm-theme-addon-ticketpool
 */

namespace cm_theme_addon_ticketpool;


/** Prevent direct access */

defined( 'ABSPATH' ) or exit;



/**
 * Exports the list of participants as CSV.
 *
 * @since 1.0.0
 *
 * @param int $event_id The ID of the event.
 *
 * @return bool  In case of error: false.
 * @return array In case of success Information about the export file.
 *
 * @todo Use of $event-id within the SQL query.
 */

function create_user_export_file( $event_id = 0 )
{
    // Prepare variables
    $uploads    = wp_upload_dir();

    $export_dir = $uploads['basedir'] . '/' . EXPORT_FOLDER;
    $export_url = $uploads['baseurl'] . '/' . EXPORT_FOLDER;

    $file_name  = 'export-' . date( "Y-m-d" ) . '.csv';
    $file_info  = [
        'name' => $file_name,
        'path' => $export_dir . '/' . $file_name,
        'url'  => $export_url . '/' . $file_name,
    ];


    // (Re-)Create export folder if necessary
    if( ! file_exists( $export_dir ) ) :
        wp_mkdir_p( $export_dir );
    endif;


    // Open file
    $file = fopen( $file_info['path'], 'w' );

    if( false === $file) :
        return null;
    endif;


    // Write header into file
    $row = [
        __( 'Last name','cm-theme-addon-ticketpool' ),
        __( 'First name','cm-theme-addon-ticketpool' ),
        __( 'Email','cm-theme-addon-ticketpool' ),
        __( 'Registration date','cm-theme-addon-ticketpool' )
    ];
    fputcsv( $file, $row);


    // Retrieve data and write to file
    global $wpdb;

    $table_name = $wpdb->prefix . TABLE_USER;
    $sql        = "SELECT user_lastname, user_forename, user_email, user_registered FROM $table_name";
    $table_data = $wpdb->get_results( $sql, 'ARRAY_A' );

    foreach( $table_data as $row ) :
        fputcsv( $file, $row );
    endforeach;


    // Close file
    fclose( $file );

    return $file_info;
}



/**
 * Returns the total number of tickets available.
 *
 * @since 1.0.0
 *
 * @param int $event_id The ID of the event.
 *
 * @return int The total number of tickets.
 */

function get_total_amount( $event_id )
{
    global $wpdb;
           $amount = 0;

    $table_name = $wpdb->prefix . TABLE_POOL;
    $sql        = "SELECT contingent_size FROM $table_name WHERE event_id=$event_id";
    $table_data = $wpdb->get_results( $sql, 'ARRAY_N' );

    if( null != $table_data ) :
        foreach( $table_data as $size ) :
            $amount += $size[0];
        endforeach;
    endif;

    return $amount;
}



/**
 * Determines the number of tickets already used from the total quota.
 *
 * @since 1.0.0
 *
 * @param int $event_id The ID of the event.
 *
 * @return int The tickets used.
 */

function get_used_amount( $event_id )
{
    global $wpdb;

    $table_name = $wpdb->prefix . TABLE_USER;
    $sql        = "SELECT COUNT(*) FROM $table_name WHERE event_id=$event_id";
    $table_data = $wpdb->get_results( $sql, 'ARRAY_N' );

    if( null != $table_data ) :
        return $table_data[0][0];
    else :
        return 0;
    endif;
}



/**
 * Determines the number of tickets still available from the total quota.
 *
 * @since 1.0.0
 *
 * @param int $event_id The ID of the event.
 *
 * @return int The tickets that are still free (in doubt 0).
 */

function get_free_amount( $event_id )
{
    global $wpdb;

    $total = get_total_amount( $event_id );
    $used  = get_used_amount( $event_id );

    return max( 0, $total - $used );
}



/**
 * Expands the ticket pool by adding a ticket contingent.
 *
 * @since 1.0.0
 *
 * @param int    $event_id            The ID of the event.
 * @param int    $contingent_size     The number of tickets in the ticket contingent.
 * @param string $contingent_provider Name of the sponsor of the ticket quota.
 *
 * @return bool true/false depending on the outcome.
 */

function add_contingent( $event_id, $contingent_size, $contingent_provider )
{
    if( ( $contingent_size > 0 ) and ! empty( $contingent_provider) ) :
        global $wpdb;

        $table_name = $wpdb->prefix . TABLE_POOL;
        $table_data = [
            'event_id'            => $event_id,
            'contingent_size'     => $contingent_size,
            'contingent_provider' => $contingent_provider,
        ];

        if( 1 == $wpdb->insert( $table_name, $table_data ) ) :
            return true;
        endif;
    endif;

    return false;
}



/**
 * Checks if the given $user_email has already been used for a given event ($event_id).
 *
 * @since  1.0.0
 *
 * @param  int    $event_id   The ID of the event.
 * @param  string $user_email The specified email.
 *
 * @return bool The check result
 *              - true:  the email is already in use.
 *              - false: any other case.
 */

function is_email_in_use( $event_id, $user_email )
{
    global $wpdb;

    $table_name = $wpdb->prefix . TABLE_USER;
    $sql        = "SELECT * FROM $table_name WHERE event_id='$event_id' AND user_email='$user_email'";
    $table_data = $wpdb->get_results( $sql, 'ARRAY_A' );

    return (bool) ( null != $table_data );
}



/**
 * Adds a user.
 *
 * @since 1.0.0
 *
 * @param int    $event_id      The ID of the event.
 * @param string $user_lastname The given last name.
 * @param string $user_forename The given fore name.
 * @param string $user_email    The given email.
 *
 * @return int A status code.
 */

function add_user( $event_id, $user_lastname, $user_forename, $user_email )
{
    // Is there still a ticket available?
    if( 0 === get_free_amount( $event_id ) ) :
        return STATUS_NOTHING_FREE;
    endif;


    // Pass empty fields?
    if( empty( $user_forename ) or empty( $user_lastname ) or empty( $user_email ) ):
        return STATUS_USER_FIELDS_EMPTY;
    endif;


    // Is the format of the email valid?
    if( ! filter_var( $user_email, FILTER_VALIDATE_EMAIL ) ) :
        return STATUS_USER_EMAIL_MALFORMED;
    endif;


    // Is the email already in use?
    if( true === is_email_in_use( $event_id, $user_email ) ) :
        return STATUS_USER_EMAIL_IN_USE;
    endif;


    // Register user
    global $wpdb;

    $table_name = $wpdb->prefix . TABLE_USER;
    $table_data = [
        'event_id'      => $event_id,
        'user_lastname' => $user_lastname,
        'user_forename' => $user_forename,
        'user_email'    => $user_email,
    ];


    // Was the user's registration successful?
    if( 0 !== $wpdb->insert( $table_name, $table_data ) ) :

        // Send confirmation mail to user
        $mail_to      = $user_email;
        $mail_subject = get_option( OPTION_MAIL_SUBJECT );
        $mail_message = get_option( OPTION_MAIL_MESSAGE );
        $mail_headers = [
            'bcc:m.dibella@rechtsdepesche.de'
        ];

        $result = wp_mail( $mail_to, $mail_subject, $mail_message, $mail_headers );

        return STATUS_USER_ADDED;

    endif;

    return STATUS_CANT_STORE_USER;
}



/**
 * Outputs a message matching the respective status code.
 *
 * @since 1.0.0
 *
 * @param int The status code.
 */

function display_user_notice( $code )
{
    $status = [
        STATUS_USER_ADDED => [
            'notice' => __( 'Your registration was successful!', 'cm-theme-addon-ticketpool' ),
            'style'  => 'cmkk-notice-sucess',
        ],
        STATUS_NOTHING_FREE => [
            'notice' => __( 'Unfortunately, there is currently no free ticket available in the ticket contingent!<br><br>Please try again at a later date.', 'cm-theme-addon-ticketpool' ),
            'style'  => 'cmkk-notice-info',
        ],
        STATUS_USER_FIELDS_EMPTY => [
            'notice' => __( 'One or more fields are not filled in.', 'cm-theme-addon-ticketpool' ),
            'style'  => 'cmkk-notice-warning',
        ],
        STATUS_USER_EMAIL_MALFORMED => [
            'notice' => __( 'Please enter a correct email address.', 'cm-theme-addon-ticketpool' ),
            'style'  => 'cmkk-notice-warning',
        ],
        STATUS_USER_EMAIL_IN_USE => [
            'notice' => __( 'Your email address has already been used. It cannot be used again.', 'cm-theme-addon-ticketpool' ),
            'style'  => 'cmkk-notice-warning',
        ],
        STATUS_CANT_STORE_USER => [
            'notice' => __( 'A technical error has occurred.', 'cm-theme-addon-ticketpool' ),
            'style'  => 'cmkk-notice-error',
        ],
    );

    if( array_key_exists( $code, $status ) ) :
    ?>
    <div class="cmkk-notice <?php echo $status[$code]['style']; ?>">
        <p><?php echo $status[$code]['notice']; ?></p>
    </div>
    <?php
    endif;
}
