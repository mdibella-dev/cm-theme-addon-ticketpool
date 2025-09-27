<?php
/**
 * Plugin Name:         CM Theme &mdash; Addon: Ticketpool
 * Plugin URI:          https://github.com/mdibella-dev/cm-theme-addon-ticketpool
 * Description:         A system to participate from a pool of sponsored tickets.
 * Author:              Marco Di Bella
 * Author URI:          https://www.marcodibella.de
 * License:             MIT License
 * Requires at least:   5
 * Tested up to:        6.3
 * Requires PHP:        7
 * Version:             2.0.2
 * Text Domain:         cm-theme-addon-ticketpool
 *
 *
 * @author  Marco Di Bella
 * @package cm-theme-addon-ticketpool
 */

namespace cm_theme_addon_ticketpool;


/** Prevent direct access */

defined( 'ABSPATH' ) or exit;



/** Variables and definitions */

define( __NAMESPACE__ . '\PLUGIN_VERSION', '2.0.2' );
define( __NAMESPACE__ . '\PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( __NAMESPACE__ . '\PLUGIN_URL', plugin_dir_url( __FILE__ ) );

// Database table names
define( __NAMESPACE__ . '\TABLE_POOL', 'cmkk_contingent_pool' );
define( __NAMESPACE__ . '\TABLE_USER', 'cmkk_contingent_user' );

// WordPress options
define( 'OPTION_MAIL_SUBJECT', 'cm_theme_addon_ticketpool_mail_subject' );
define( 'OPTION_MAIL_MESSAGE', 'cm_theme_addon_ticketpool_mail_message' );

// Status codes
define( __NAMESPACE__ . '\STATUS_USER_ADDED',             100 );
define( __NAMESPACE__ . '\STATUS_NOTHING_FREE',           200 );
define( __NAMESPACE__ . '\STATUS_USER_FIELDS_EMPTY',      201 );
define( __NAMESPACE__ . '\STATUS_USER_EMAIL_MALFORMED',   202 );
define( __NAMESPACE__ . '\STATUS_USER_EMAIL_IN_USE',      203 );
define( __NAMESPACE__ . '\STATUS_CANT_STORE_USER',        204 );

// Notice codes
define( __NAMESPACE__ . '\NOTICE_EMAIL_TEMPLATE_UPDATED',  100 );
define( __NAMESPACE__ . '\NOTICE_EMAIL_TEMPLATE_RESET',    101 );
define( __NAMESPACE__ . '\NOTICE_TICKET_CONTINGENT_ADDED', 102 );
define( __NAMESPACE__ . '\NOTICE_TABLE_RESET',             103 );
define( __NAMESPACE__ . '\NOTICE_EMPTY_FIELDS',            104 );

// Folder to store export files
define( __NAMESPACE__ . '\EXPORT_FOLDER', 'cm-kk' );

// Workaround: currently the plugin supports only the CM event with the number 1 (Q&D)
define(  __NAMESPACE__ . '\EVENT_ID', '1' );

// Workaround
define(  __NAMESPACE__ . '\TARGET_MAIL', 'kongress@pwg-seminare.de' );



/** Include files */

require_once PLUGIN_DIR . 'includes/shortcodes/shortcode-form.php';
require_once PLUGIN_DIR . 'includes/classes/index.php';
require_once PLUGIN_DIR . 'includes/backend/index.php';
require_once PLUGIN_DIR . 'includes/core.php';
require_once PLUGIN_DIR . 'includes/backend.php';
require_once PLUGIN_DIR . 'includes/setup.php';



/** Add hooks */

register_activation_hook( __FILE__, __NAMESPACE__ . '\plugin_activation' );
register_deactivation_hook( __FILE__, __NAMESPACE__ . '\plugin_deactivation' );
register_uninstall_hook( __FILE__, __NAMESPACE__ . '\plugin_uninstall' );
