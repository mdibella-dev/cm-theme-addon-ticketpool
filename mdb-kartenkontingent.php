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


/** Funktionsbibliothek einbinden **/

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
            event_id            int UNSIGNED NOT NULL,
            kontingent_groesse  int UNSIGNED DEFAULT 0,
            kontingent_anbieter mediumtext DEFAULT '' NOT NULL,
            PRIMARY KEY (event_id)
            )
            COLLATE $table_charset_collate;";

        dbDelta( $sql );

    endif;


    // Tabelle für Kartenkontingent-Teilnehmer einrichten

    if( $table_name__teilnehmer != $wpdb->get_var( "SHOW TABLES LIKE '$table_name__teilnehmer'" ) ) :

        $sql = "CREATE TABLE $table_name__teilnehmer (
            event_id            int UNSIGNED NOT NULL,
            teilnehmer_id       int UNSIGNED NOT NULL,
            teilnehmer_nachname varchar(255) DEFAULT '' NOT NULL,
            teilnehmer_vorname  varchar(255) DEFAULT '' NOT NULL,
            teilnehmer_email    varchar(255) DEFAULT '' NOT NULL,
            PRIMARY KEY (teilnehmer_id),
            FOREIGN KEY (event_id) REFERENCES $table_name__kontingent(event_id)
            )
            COLLATE $table_charset_collate;";

        dbDelta( $sql );
    endif;
}

register_activation_hook( __FILE__, 'cm_kk_plugin_activation' );
