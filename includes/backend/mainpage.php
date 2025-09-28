<?php
/**
 * Main page of the plugin.
 *
 * @author  Marco Di Bella
 * @package cm-theme-addon-ticketpool
 */

namespace cm_theme_addon_ticketpool;


/** Prevent direct access */

defined( 'ABSPATH' ) or exit;



/**
 * Creates a menu item in the backend for the main page.
 *
 * @since 1.0.0
 */

function add_mainpage() {
    add_menu_page(
        __( 'Ticket contingents', 'cm-theme-addon-ticketpool' ),
        __( 'Ticket contingents', 'cm-theme-addon-ticketpool' ),
        'manage_options',
        'cmkk_mainpage',
        __NAMESPACE__ . '\show_mainpage',
        'dashicons-tickets-alt',
        20,
    );
}

add_action( 'admin_menu', __NAMESPACE__ . '\add_mainpage' );



/**
 * Displays the main page.
 *
 * @since  1.0.0
 * @source http://qnimate.com/add-tabs-using-wordpress-settings-api/
 */

function show_mainpage() {
    // setup all tabs
    $tabs = [
        'tab-01' => [
            'callback' => __NAMESPACE__ . '\show_mainpage_tab_01',
            'title'    => __( 'Participants', 'cm-theme-addon-ticketpool'),
        ],
        'tab-02' => [
            'callback' => __NAMESPACE__ . '\show_mainpage_tab_02',
            'title'    => __( 'Ticket contingents', 'cm-theme-addon-ticketpool'),
        ],
        'tab-03' => [
            'callback' => __NAMESPACE__ . '\show_mainpage_tab_03',
            'title'    => __( 'Email notification', 'cm-theme-addon-ticketpool'),
        ],
        'tab-04' => [
            'callback' => __NAMESPACE__ . '\show_mainpage_tab_04',
            'title'    => __( 'Tools', 'cm-theme-addon-ticketpool'),
        ]
    ];

    // get the current tab
    if ( isset( $_GET['tab'] ) and array_key_exists( $_GET['tab'], $tabs ) ) {
        $tab_active = $_GET['tab'];
    } else {
        $tab_active = 'tab-01';
    }


    /** Output the main page */

    ?>
    <div class="wrap">
        <h1 class="wp-heading-inline"><?php echo __( 'Ticket contingents', 'cm-theme-addon-ticketpool' )?></h1>
        <h2 class="nav-tab-wrapper">
        <?php
        foreach ( $tabs as $key => $config ) {

            if ( $tab_active == $key ) {
                $nav_tab_class = 'nav-tab-active';
            } else {
                $nav_tab_class = '';
            }

            echo sprintf(
                '<a href="?page=cmkk_mainpage&tab=%1$s" class="nav-tab %2$s">%3$s</a>',
                $key,
                $nav_tab_class,
                $config['title'],
            );
        }
        ?>
        </h2>
        <?php
        /** Output the specific tab */

        call_user_func( (string) $tabs[$tab_active]['callback'] );
        ?>
    </div>
    <?php
}
