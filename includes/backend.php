<?php
/**
 * Functions to handle the backend.
 *
 * @author  Marco Di Bella
 * @package cm-theme-addon-kartenkontingent
 */

namespace cm_theme_addon_kartenkontingent;


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

add_action( 'admin_enqueue_scripts','cm_theme_addon_kartenkontingent\admin_enqueue_scripts' );
