<?php
/**
 * Functions to activate, initiate and deactivate the plugin.
 *
 * @author  Marco Di Bella
 * @package cm-theme-addon-ticketpool
 */

namespace cm_theme_addon_ticketpool;


/** Prevent direct access */

defined( 'ABSPATH' ) or exit;



/**
 * The init function for the plugin.
 *
 * @since 1.0.0
 */

function plugin_init()
{
    // Load text domain, use relative path to the plugin's language folder
    $x = load_plugin_textdomain( 'cmta_ticketpool', false, plugin_basename( PLUGIN_DIR ) . '/languages' );

    error_log( 'Result: ' . ($x? 'ok' : 'null') .' at path: ' . plugin_basename( PLUGIN_DIR ) . '/languages' . ' vs ' . dirname( plugin_basename( __FILE__ ) ) . '/languages');
}

add_action( 'init', __NAMESPACE__ . '\plugin_init' );



/**
 * The activation function for the plugin.
 *
 * @since 1.0.0
 */

function plugin_activation()
{
    // Set up tables if they do not exist
    global $wpdb;

    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

    $table_charset_collate = $wpdb->get_charset_collate();
    $table_name            = $wpdb->prefix . TABLE_POOL;

    if( $table_name != $wpdb->get_var( "SHOW TABLES LIKE '$table_name'" ) ) :

        $sql = "CREATE TABLE $table_name (
            event_id            INT UNSIGNED NOT NULL,
            contingent_id       INT UNSIGNED NOT NULL AUTO_INCREMENT,
            contingent_size     INT UNSIGNED DEFAULT 0,
            contingent_provider VARCHAR(255) DEFAULT '' NOT NULL,
            contingent_provided DATETIME DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (contingent_id)
            )
            COLLATE $table_charset_collate;";

        dbDelta( $sql );

    endif;

    $table_name = $wpdb->prefix . TABLE_USER;

    if( $table_name != $wpdb->get_var( "SHOW TABLES LIKE '$table_name'" ) ) :

        $sql = "CREATE TABLE $table_name (
            event_id            INT UNSIGNED NOT NULL,
            user_id             INT UNSIGNED NOT NULL AUTO_INCREMENT,
            user_forename       VARCHAR(255) DEFAULT '' NOT NULL,
            user_lastname       VARCHAR(255) DEFAULT '' NOT NULL,
            user_email          VARCHAR(255) DEFAULT '' NOT NULL,
            user_registered     DATETIME DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (user_id)
            )
            COLLATE $table_charset_collate;";

        dbDelta( $sql );
    endif;


    /// Set up options if not present
    if( false == get_option( OPTION_MAIL_SUBJECT ) ) :
        add_option( OPTION_MAIL_SUBJECT, __( 'Thank you for participating', 'cmta_ticketpool' ) );
    endif;

    if( false == get_option( OPTION_MAIL_MESSAGE ) ) :
        add_option( OPTION_MAIL_MESSAGE,
            __( 'Your participation in the event was registered. In the coming days you will receive another email with additional information.', 'cmta_ticketpool' ) .
            '<br><br>' .
            __( 'Best regards', 'cmta_ticketpool' ) .
            '<br><br>' .
            __( 'Attention: This email was generated automatically, please do not reply.', 'cmta_ticketpool' )
        );
    endif;


    // Create path for export files
    $upload_dir = wp_upload_dir();

    wp_mkdir_p( $upload_dir['basedir'] . '/' . EXPORT_FOLDER );
}

register_activation_hook( __FILE__, __NAMESPACE__ . '\plugin_activation' );



/**
 * The deactivation function for the plugin.
 *
 * @since 1.0.0
 */

function plugin_deactivation()
{
    // Do something!
}

register_deactivation_hook( __FILE__, __NAMESPACE__ . '\plugin_deactivation' );



/**
 * The uninstall function for the plugin.
 *
 * @since 1.0.0
 *
 * @todo remove export files and folder
 */

function plugin_uninstall()
{
    // Remove tables if present
    global $wpdb;

    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

    $table_name = $wpdb->prefix . TABLE_POOL;

    if( $table_name == $wpdb->get_var( "SHOW TABLES LIKE '$table_name'" ) ) :
        $sql = "DROP TABLE $table_name;";

        dbDelta( $sql );
    endif;

    $table_name = $wpdb->prefix . TABLE_USER;

    if( $table_name == $wpdb->get_var( "SHOW TABLES LIKE '$table_name'" ) ) :
        $sql = "DROP TABLE $table_name;";

        dbDelta( $sql );
    endif;



    // Remove options if present
    if( true == get_option( OPTION_MAIL_SUBJECT ) ) :
        delete_option( OPTION_MAIL_SUBJECT );
    endif;

    if( true == get_option( OPTION_MAIL_MESSAGE ) ) :
        delete_option( OPTION_MAIL_MESSAGE );
    endif;
}

register_uninstall_hook( __FILE__, __NAMESPACE__ . '\plugin_uninstall' );



/**
 * Load the frontend scripts and styles.
 *
 * @since 1.0.0
 */

function plugin_enqueue_scripts()
{
    wp_enqueue_script(
        'cm-ticketpool-frontend-script',
        PLUGIN_URL . '/assets/build/js/frontend.min.js',
        array( 'jquery' ),
        PLUGIN_VERSION,
        true
    );
}

add_action( 'wp_enqueue_scripts', __NAMESPACE__ . '\plugin_enqueue_scripts', 9990 );
