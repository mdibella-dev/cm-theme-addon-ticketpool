<?php
/**
 * Main page of the plugin.
 *
 * @author  Marco Di Bella
 * @package cm-theme-addon-ticketpool
 */

namespace cm_theme_addon_kartenkontingent;


/** Prevent direct access */

defined( 'ABSPATH' ) or exit;



/**
 * Creates a menu item in the backend for the main page.
 *
 * @since 1.0.0
 */

function add_mainpage()
{
    add_menu_page(
        __( 'Kartenkontingente', 'cmkk' ),
        __( 'Kartenkontingente', 'cmkk' ),
        'manage_options',
        'cmkk_mainpage',
        __NAMESPACE__ . '\show_mainpage',
        'dashicons-tickets',
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

function show_mainpage()
{
    // setup all tabs
    $tabs = array(
        'tab-01' => array(
            'callback' => "cmkk_show_tab_01",
            'title'    => __( 'Übersicht', 'cmkk'),
        ),
        'tab-02' => array(
            'callback' => "cmkk_show_tab_02",
            'title'    => __( 'Einzelne Kartenkontigente', 'cmkk'),
        ),
        'tab-03' => array(
            'callback' => "cmkk_show_tab_03",
            'title'    => __( 'E-Mail-Benachrichtigung', 'cmkk'),
        ),
    );

    // get the current tab
    if( isset( $_GET['tab'] ) and array_key_exists( $_GET['tab'], $tabs ) ) :
        $tab_active = $_GET['tab'];
    else :
        $tab_active = 'tab-01';
    endif;


    /** Output the main page */

    ?>
    <div class="wrap">
        <h1 class="wp-heading-inline"><?php echo __( 'Kartenkontingente', 'cmkk' )?></h1>
        <h2 class="nav-tab-wrapper">
        <?php
        foreach( $tabs as $key => $config ) :

            if( $tab_active == $key ) :
                $nav_tab_class = 'nav-tab-active';
            else :
                $nav_tab_class = '';
            endif;

            echo sprintf(
                '<a href="?page=cmkk_mainpage&tab=%1$s" class="nav-tab %2$s">%3$s</a>',
                $key,
                $nav_tab_class,
                $config['title'],
            );

        endforeach;
        ?>
        </h2>
        <?php
        /** Output the specific tab */

        call_user_func( (string) $tabs[$tab_active]['callback'] );
        ?>
    </div>
    <?php
}
