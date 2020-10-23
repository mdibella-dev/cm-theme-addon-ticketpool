<?php
/*
Plugin Name:     Marco Di Bella - Kartenkontingent
Author:          Marco Di Bella
Author URI:      https://www.marcodibella.de
Description:     Addon zu Congressomat
Version:         0.0.1
*/


// Check & Quit
defined( 'ABSPATH' ) OR exit;


/** Konstanten **/

define( 'TABLE_KONTINGENT', 'cm_kartenkontingent_kontingent' );
define( 'TABLE_TEILNEHMER', 'cm_kartenkontingent_teilnehmer' );
define( 'EVENT_ID', '1' );              // todo: automatisieren
define( 'PLUGIN_PATH', plugin_dir_path( __FILE__ ) );


/** Funktionsbibliothek einbinden **/

require_once( PLUGIN_PATH . 'inc/class-kontingent-list-table.php' );
require_once( PLUGIN_PATH . 'inc/class-teilnehmer-list-table.php' );
require_once( PLUGIN_PATH . 'inc/core.php' );
require_once( PLUGIN_PATH . 'inc/mainpage.php' );



/**
 * Zentrale Aktivierungsfunktion für das Plugin
 *
 * @since   0.0.1
 */

function cm_kk_plugin_activation()
{
    global $wpdb;

    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );


    /** Wichtige Variablen setzen **/

    $table_charset_collate  = $wpdb->get_charset_collate();
    $table_name__kontingent = $wpdb->prefix . TABLE_KONTINGENT;
    $table_name__teilnehmer = $wpdb->prefix . TABLE_TEILNEHMER;


    /** Tabelle für Kontingente einrichten **/

    if( $table_name__kontingent != $wpdb->get_var( "SHOW TABLES LIKE '$table_name__kontingent'" ) ) :

        $sql = "CREATE TABLE $table_name__kontingent (
            event_id            INT UNSIGNED NOT NULL,
            id                  INT UNSIGNED NOT NULL AUTO_INCREMENT,
            groesse             INT UNSIGNED DEFAULT 0,
            bereitgestellt_von  VARCHAR(255) DEFAULT '' NOT NULL,
            bereitgestellt_am   DATETIME DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (id)
            )
            COLLATE $table_charset_collate;";

        dbDelta( $sql );

    endif;


    /** Tabelle für Kartenkontingent-Teilnehmer einrichten **/

    if( $table_name__teilnehmer != $wpdb->get_var( "SHOW TABLES LIKE '$table_name__teilnehmer'" ) ) :

        $sql = "CREATE TABLE $table_name__teilnehmer (
            event_id    INT UNSIGNED NOT NULL,
            id          INT UNSIGNED NOT NULL AUTO_INCREMENT,
            nachname    VARCHAR(255) DEFAULT '' NOT NULL,
            vorname     VARCHAR(255) DEFAULT '' NOT NULL,
            email       VARCHAR(255) DEFAULT '' NOT NULL,
            zeitpunkt   DATETIME DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (id)
            )
            COLLATE $table_charset_collate;";

        dbDelta( $sql );
    endif;
}

register_activation_hook( __FILE__, 'cm_kk_plugin_activation' );



/**
 * Lädt Plugin-Scripts
 *
 * @since 0.0.1
 */

function cm_kk_admin_enqueue_scripts()
{
    $screen = get_current_screen();

    if( 'toplevel_page_cm_kk_mainpage' === $screen->id ) :
        wp_enqueue_style( 'cm_kartenkontingent', plugins_url( 'assets/css/admin.min.css', __FILE__ ) );
    endif;
}

add_action( 'admin_enqueue_scripts', 'cm_kk_admin_enqueue_scripts' );
