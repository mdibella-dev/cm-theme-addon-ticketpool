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
define( 'PLUGIN_PATH', __DIR__ );


/** Funktionsbibliothek einbinden **/

require_once( __DIR__ . '/inc/class-kontingent-list-table.php' );
require_once( __DIR__ . '/inc/class-teilnehmer-list-table.php' );
require_once( __DIR__ . '/inc/core.php' );
require_once( __DIR__ . '/inc/mainpage.php' );



/**
 * Zentrale Aktivierungsfunktion für das Plugin
 *
 * @since   0.0.1
 */

function cm_kk_plugin_activation()
{
    global $wpdb;

    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );


    // Wichtige Variablen setzen

    $table_charset_collate  = $wpdb->get_charset_collate();
    $table_name__kontingent = $wpdb->prefix . TABLE_KONTINGENT;
    $table_name__teilnehmer = $wpdb->prefix . TABLE_TEILNEHMER;



    // Tabelle für Kontingente einrichten

    if( $table_name__kontingent != $wpdb->get_var( "SHOW TABLES LIKE '$table_name__kontingent'" ) ) :

        $sql = "CREATE TABLE $table_name__kontingent (
            event_id            INT UNSIGNED NOT NULL,
            id                  INT UNSIGNED NOT NULL,
            groesse             INT UNSIGNED DEFAULT 0,
            bereitgestellt_von  VARCHAR(255) DEFAULT '' NOT NULL,
            bereitgestellt_am   DATETIME DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (id)
            )
            COLLATE $table_charset_collate;";

        dbDelta( $sql );

    endif;


    // Tabelle für Kartenkontingent-Teilnehmer einrichten

    if( $table_name__teilnehmer != $wpdb->get_var( "SHOW TABLES LIKE '$table_name__teilnehmer'" ) ) :

        $sql = "CREATE TABLE $table_name__teilnehmer (
            event_id    INT UNSIGNED NOT NULL,
            id          INT UNSIGNED NOT NULL,
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
