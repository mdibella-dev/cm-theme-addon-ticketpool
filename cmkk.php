<?php
/*
Plugin Name:     Marco Di Bella - Kartenkontingent
Author:          Marco Di Bella
Author URI:      https://www.marcodibella.de
Description:     Addon zu Congressomat
Version:         1.0.0
*/


defined( 'ABSPATH' ) OR exit;



/* Konstanten */

define( 'TABLE_POOL', 'cmkk_contingent_pool' );
define( 'TABLE_USER', 'cmkk_contingent_user' );
define( 'PLUGIN_PATH', plugin_dir_path( __FILE__ ) );
define( 'OPTION_MAIL_SUBJECT', 'cmkk_plugin_mail_subject' );
define( 'OPTION_MAIL_MESSAGE', 'cmkk_plugin_mail_message' );

// Workaround: gegenwärtig Unterstützung nur für ein Event
define( 'EVENT_ID', '1' );



/* Funktionsbibliothek einbinden */

require_once( PLUGIN_PATH . 'inc/class-pool-list-table.php' );
require_once( PLUGIN_PATH . 'inc/class-user-list-table.php' );
require_once( PLUGIN_PATH . 'inc/shortcode-form.php' );
require_once( PLUGIN_PATH . 'inc/core.php' );
require_once( PLUGIN_PATH . 'inc/mainpage.php' );



/**
 * Zentrale Aktivierungsfunktion für das Plugin
 *
 * @since   1.0.0
 */

function cmkk_plugin_activation()
{
    global $wpdb;

    /* Funktionsbibliothek einbinden */

    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );


    /* Tabellen einrichten falls nicht vorhanden */

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


    /* Optionen einrichten falls nicht vorhanden */

    if( FALSE == get_option( OPTION_MAIL_SUBJECT ) ) :
        add_option( OPTION_MAIL_SUBJECT, 'Vielen Dank für Ihre Teilnahme' );
    endif;

    if( FALSE == get_option( OPTION_MAIL_MESSAGE ) ) :
        add_option( OPTION_MAIL_MESSAGE, 'Ihre Teilnahme am Interdisziplinären WundCongress wurde registriert.' );
    endif;
}

register_activation_hook( __FILE__, 'cmkk_plugin_activation' );



/**
 * Lädt Plugin-Scripts
 *
 * @since 0.0.1
 */

function cmkk_admin_enqueue_scripts()
{
    $screen = get_current_screen();

    if( 'toplevel_page_cmkk_mainpage' === $screen->id ) :
        wp_enqueue_style( 'cmkk-style', plugins_url( 'assets/css/admin.min.css', __FILE__ ) );
    endif;
}

add_action( 'admin_enqueue_scripts', 'cmkk_admin_enqueue_scripts' );