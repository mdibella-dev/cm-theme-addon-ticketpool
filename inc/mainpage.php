<?php
/**
 * Hauptseite des Plugins
 *
 * @since   1.0.0
 * @author  Marco Di Bella <mdb@marcodibella.de>
 */


defined( 'ABSPATH' ) OR exit;



/* Funktionsbibliothek einbinden */

require_once( PLUGIN_PATH . 'inc/mainpage-tab-01.php' );
require_once( PLUGIN_PATH . 'inc/mainpage-tab-02.php' );
require_once( PLUGIN_PATH . 'inc/mainpage-tab-03.php' );



/**
 * Erzeugt einen Menüpunkt im Backend
 *
 * @since   1.0.0
 * @todo    - In das Congressomat-Menü verschieben (setzt Umbau von Congressomat voraus)
 */

function cmkk_add_mainpage_to_admin_menu()
{
    add_menu_page(
        __( 'Kartenkontingente', 'cmkk' ),
        __( 'Kartenkontingente', 'cmkk' ),
        'manage_options',
        'cmkk_mainpage',
        'cmkk_show_mainpage',
		'dashicons-tickets',
		20,
    );
}

add_action( 'admin_menu', 'cmkk_add_mainpage_to_admin_menu' );



/**
 * Anzeige der Hauptseite
 *
 * @since   1.0.0
 * @source  http://qnimate.com/add-tabs-using-wordpress-settings-api/
 */

function cmkk_show_mainpage()
{
?>
<div class="wrap">
<h1 class="wp-heading-inline"><?php echo __( 'Kartenkontingente', 'cmkk' )?></h1>
<?php
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

    if( isset( $_GET['tab'] ) and array_key_exists( $_GET['tab'], $tabs ) ) :
        $tab_active = $_GET['tab'];
    else :
        $tab_active = 'tab-01';
    endif;
?>
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
    call_user_func( (string) $tabs[ $tab_active ]['callback'] );
?>
</div>
<?php
}



/**
 * Anzeige der administrativen Hinweise
 *
 * @since   1.0.0
 * @see     https://digwp.com/2016/05/wordpress-admin-notices/
 */

function cmkk_admin_notice()
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

add_action( 'admin_notices', 'cmkk_admin_notice' );
