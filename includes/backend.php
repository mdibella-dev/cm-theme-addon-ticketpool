<?php
/**
 * Functions to handle the backend.
 *
 * @author  Marco Di Bella
 * @package cm-theme-addon-ticketpool
 */

namespace cm_theme_addon_ticketpool;


/** Prevent direct access */

defined( 'ABSPATH' ) or exit;



/**
 * Load the backend scripts and styles.
 *
 * @since 1.0.0
 */

function admin_enqueue_scripts()
{
    $screen = get_current_screen();

    if( 'toplevel_page_cmkk_mainpage' === $screen->id ) :
        wp_enqueue_style(
            'cm-kk-style',
            plugins_url( 'assets/build/css/backend.min.css', __FILE__ )
            array( 'jquery' ),
            PLUGIN_VERSION,
            true
        );
    endif;
}

add_action( 'admin_enqueue_scripts', __NAMESPACE__ . '\admin_enqueue_scripts' );



/**
 * Display of the administrative notices.
 *
 * @since 1.0.0
 * @see   https://digwp.com/2016/05/wordpress-admin-notices/
 */

function admin_notices()
{
    if( isset( $_GET['cmkk-status'] ) ) :

        switch( $_GET['cmkk-status'] ) :

            case '1':
            ?>
            <div class="notice notice-information is-dismissible">
                <p><?php echo __( 'Ein neuer Teilnehmer hat sich über das Kartenkontingent angemeldet.' , 'cmkk' ); ?></p>
            </div>
            <?php
            break;

            case '2':
            ?>
            <div class="notice notice-success is-dismissible">
                <p><?php echo __( 'E-Mail-Vorlage wurde aktualisiert.' , 'cmkk' ); ?></p>
            </div>
            <?php
            break;

            case '3':
            ?>
            <div class="notice notice-success is-dismissible">
                <p><?php echo __( 'Das Kartenkontingent wurde erweitert.' , 'cmkk' ); ?></p>
            </div>
            <?php
            break;

        endswitch;

    endif;
}

add_action( 'admin_notices', __NAMESPACE__ . '\admin_notices' );
