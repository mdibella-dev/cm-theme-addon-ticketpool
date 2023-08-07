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

function admin_enqueue_scripts() {

    $screen = get_current_screen();

    if( ( 'toplevel_page_cmkk_mainpage' === $screen->id ) ):
        wp_enqueue_style(
            'cm-ticketpool-backend-style',
            PLUGIN_URL . '/assets/build/css/backend.min.css',
            [],
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

function admin_notices() {
    
    if( isset( $_GET['notice'] ) ) :

        $notice  = $_GET['notice'];
        $notices = [
            NOTICE_EMAIL_TEMPLATE_UPDATED => [
                'type'    => 'notice-success is-dismissible',
                'message' => __( 'Email template has been updated.' , 'cm-theme-addon-ticketpool' )
            ],
            NOTICE_EMAIL_TEMPLATE_RESET => [
                'type'    => 'notice-success is-dismissible',
                'message' => __( 'Email template has been reset.' , 'cm-theme-addon-ticketpool' )
            ],
            NOTICE_TICKET_CONTINGENT_ADDED => [
                'type'    => 'notice-success is-dismissible',
                'message' => __( 'The ticket contingent has been expanded.' , 'cm-theme-addon-ticketpool' )
            ],
            NOTICE_TABLE_RESET => [
                'type'    => 'notice-success is-dismissible',
                'message' => __( 'Tables have been reset.' , 'cm-theme-addon-ticketpool' )
            ],
            NOTICE_EMPTY_FIELDS => [
                'type'    => 'notice-error',
                'message' => __( 'One or more fields are not filled in.' , 'cm-theme-addon-ticketpool' )
            ],
        ];

        if( array_key_exists( $notice, $notices ) ) :
        ?>
        <div class="notice <?php echo $notices[$notice]['type']; ?>">
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

function display_admin_notice( $notice )
{
    $_GET['notice'] = $notice;
    do_action( 'admin_notices' );
}
