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

    if( ( 'toplevel_page_cmkk_mainpage' === $screen->id ) ):
        wp_enqueue_style(
            'cm-ticketpool-backend-style',
            PLUGIN_URL . '/assets/build/css/backend.min.css',
            array(),
            PLUGIN_VERSION
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
    if( isset( $_GET['notice'] ) ) :

        $notice  = $_GET['notice'];
        $notices = array(
            NOTICE_NEW_PARTICIPANT => array(
                'type'    = 'notice-information',
                'message' = __( 'A new participant has registered via the ticket contingent.' , 'cm-theme-addon-ticketpool' )
            ),
            NOTICE_EMAIL_TEMPLATE_UPDATED => array(
                'type'    = 'notice-success',
                'message' = __( 'A new participant has registered via the ticket contingent.' , 'cm-theme-addon-ticketpool' )
            ),
            NOTICE_EMAIL_TEMPLATE_RESET => array(
                'type'    = 'notice-success',
                'message' = __( 'Email template has been reset.' , 'cm-theme-addon-ticketpool' )
            ),
            NOTICE_NEW_TICKET_CONTINGENT => array(
                'type'    = 'notice-success',
                'message' = __( 'The ticket contingent has been expanded.' , 'cm-theme-addon-ticketpool' )
            ),
        );

        if( array_key_exists( $notice, $notices ) ) :
        ?>
            <div class="notice <?php echo $notices[$notice]['type']; ?> is-dismissible">
                <p><?php echo $notices[$notice]['message']; ?></p>
            </div>
        <?php
        endif;

    endif;
}

add_action( 'admin_notices', __NAMESPACE__ . '\admin_notices' );



/**
 * Initiate the display of an administrative notice.
 *
 * @since 2.0.0
 *
 * @param int $notice Code of the notice to be displayed.
 */

function do_admin_notice( $notice )
{
    $_GET['notice'] = $notice;
    do_action( 'admin_notices' );
}
