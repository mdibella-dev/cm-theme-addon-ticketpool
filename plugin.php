<?php
/**
 * Plugin Name:         CM &mdash; Kartenkontngent (addon to CM theme)
 * Plugin URI:          https://github.com/mdibella-dev/cm-theme-addon-faq
 * Description:         A system to participate from a pool of sponsored tickets.
 * Author:              Marco Di Bella
 * Author URI:          https://www.marcodibella.de
 * License:             MIT License
 * Requires at least:   5
 * Tested up to:        6.2
 * Requires PHP:        7
 * Version:             1.0.0
 * Text Domain:         cm-kk
 * Domain Path:         /languages
 *
 * @author  Marco Di Bella
 * @package cm-theme-addon-ticketpool
 */

namespace cm_theme_addon_kartenkontingent;


/** Prevent direct access */

defined( 'ABSPATH' ) or exit;



/** Variables and definitions */

define( __NAMESPACE__ . '\PLUGIN_VERSION', '1.0.0' );
define( __NAMESPACE__ . '\PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( __NAMESPACE__ . '\PLUGIN_URL', plugin_dir_url( __FILE__ ) );

define( 'TABLE_POOL', 'cmkk_contingent_pool' );
define( 'TABLE_USER', 'cmkk_contingent_user' );

define( 'OPTION_MAIL_SUBJECT', 'cmkk_plugin_mail_subject' );
define( 'OPTION_MAIL_MESSAGE', 'cmkk_plugin_mail_message' );

define( 'STATUS_USER_ADDED',           100 );
define( 'STATUS_NOTHING_FREE',         200 );
define( 'STATUS_USER_FIELDS_EMPTY',    201 );
define( 'STATUS_USER_EMAIL_MALFORMED', 202 );
define( 'STATUS_USER_EMAIL_IN_USE',    203 );
define( 'STATUS_CANT_STORE_USER',      204 );

define( 'EXPORT_FOLDER', 'cm-kk' );


// Workaround: currently support only one event

define( 'EVENT_ID', '1' );



/** Include files */

require_once( PLUGIN_DIR . 'includes/shortcodes/shortcode-form.php' );
require_once( PLUGIN_DIR . 'includes/classes/index.php' );
require_once( PLUGIN_DIR . 'includes/backend/index.php' );
require_once( PLUGIN_DIR . 'includes/core.php' );
require_once( PLUGIN_DIR . 'includes/backend.php' );
require_once( PLUGIN_DIR . 'includes/setup.php' );
