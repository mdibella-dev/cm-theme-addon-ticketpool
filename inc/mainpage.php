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
 * @todo    - Automatisierung der Link-Generierung
 * @source  http://qnimate.com/add-tabs-using-wordpress-settings-api/
 */

function cmkk_show_mainpage()
{
?>
<div class="wrap">
<h1 class="wp-heading-inline"><?php echo __( 'Kartenkontingente', 'cmkk' )?></h1>
<?php
    $tabs = array(
        'tab-01' => "cmkk_show_tab_01",
        'tab-02' => "cmkk_show_tab_02",
        'tab-03' => "cmkk_show_tab_03",
    );

    if( isset( $_GET['tab'] ) and array_key_exists( $_GET['tab'], $tabs ) ) :
        $tab_active = $_GET['tab'];
    else :
        $tab_active = 'tab-01';
    endif;
?>
<h2 class="nav-tab-wrapper">
<a href="?page=cmkk_mainpage&tab=tab-01" class="nav-tab <?php if( $tab_active == 'tab-01'): echo 'nav-tab-active'; endif; ?>"><?php echo __( 'Übersicht', 'cmkk'); ?></a>
<a href="?page=cmkk_mainpage&tab=tab-02" class="nav-tab <?php if( $tab_active == 'tab-02'): echo 'nav-tab-active'; endif; ?>"><?php echo __( 'Einzelne Kartenkontigente', 'cmkk'); ?></a>
<a href="?page=cmkk_mainpage&tab=tab-03" class="nav-tab <?php if( $tab_active == 'tab-03'): echo 'nav-tab-active'; endif; ?>"><?php echo __( 'E-Mail-Benachrichtigung', 'cmkk'); ?></a>
</h2>
<?php
    call_user_func( (string) $tabs[ $tab_active ] );
?>
</div>
<?php
}
