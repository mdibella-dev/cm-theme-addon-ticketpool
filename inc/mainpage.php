<?php
/**
 * Hauptseite des Plugins
 *
 * @author Marco Di Bella <mdb@marcodibella.de>
 */


// Check & Quit
defined( 'ABSPATH' ) OR exit;



/**
 * Erzeugt einen Menüpunkt im Backend
 *
 * @since   0.0.1
 * @todo    In das Congressomat-Menü verschieben (setzt Umbau von Congressomat voraus)
 */

function cm_kk_add_mainpage_to_admin_menu()
{
    add_menu_page(
        __( 'Kartenkontingente', 'cm_kk' ),
        __( 'Kartenkontingente', 'cm_kk' ),
        'manage_options',
        'cm_kk_mainpage',
        'cm_kk_show_mainpage',
		'dashicons-tickets',
		20,
    );
}

add_action( 'admin_menu', 'cm_kk_add_mainpage_to_admin_menu' );



/**
 * Anzeige der Hauptseite
 *
 * @since 0.0.1
 * @source http://qnimate.com/add-tabs-using-wordpress-settings-api/
 */

function cm_kk_show_mainpage()
{
    if( isset( $_POST[ 'action' ] ) ) :
        switch( $_POST[ 'action' ] ) :
            case 'export' :
                cm_kk_export_teilnehmer();
            break;
        endswitch;
    endif;
?>
<div class="wrap">
<h1 class="wp-heading-inline"><?php _e( 'Kartenkontingente', 'cm_kk' )?></h1>
<?php
    $select_tab = isset( $_GET[ 'tab' ] )? $_GET[ 'tab' ] : 'tab-01';

    switch( $select_tab ) :
        case 'tab-02' :
            $active_tab = 'tab-02';
        break;

        default:
        case 'tab-01' :
            $active_tab = 'tab-01';
        break;
    endswitch;
?>
<h2 class="nav-tab-wrapper">
<a href="?page=cm_kk_mainpage&tab=tab-01" class="nav-tab <?php if( $active_tab == 'tab-01'){ echo 'nav-tab-active'; } ?>"><?php _e( 'Übersicht', 'cm_kk'); ?></a>
<a href="?page=cm_kk_mainpage&tab=tab-02" class="nav-tab <?php if( $active_tab == 'tab-02'){ echo 'nav-tab-active'; } ?>"><?php _e( 'Einzelne Kartenkontigente', 'cm_kk'); ?></a>
</h2>
<?php
    switch( $active_tab ) :
        case 'tab-01' :
            cm_kk_show_tab_01();
        break;

        case 'tab-02' :
            cm_kk_show_tab_02();
        break;
    endswitch;
?>
</div>
<?php
}



/**
 * Anzeigefunktion für tab-01: Übersicht
 *
 * @since 0.0.1
 */

function cm_kk_show_tab_01()
{
?>
<p><?php
/*    $free  = cm_kk_get_free_amount();
    $total = cm_kk_get_total_amount();
    $used  = cm_kk_get_used_amount();

    if( 0 == $total ) :
        _e( 'Keine Kontingente verfügbar', 'cm_kk');
    else :
        if( 0 == $free ) :
            _e( 'Alle Kontingentplätze vergeben', 'cm_kk');
        else :
            echo sprintf(
                __( '%1$s von %2$s Kontingentplätze bereits vergeben, noch %3$s Plätze frei', 'cm_kk'),
                $used,
                $total,
                $free,
            );
        endif;
    endif;*/
?></p>
<?php
    $teilnehmer_table = new MDB_Teilnehmer_List_Table();
    $teilnehmer_table->prepare_items();
    $teilnehmer_table->display();
?>
<form method="post">
    <button type="submit" name="action" class="button button-primary" value="export"><?php _e( 'Daten als CSV exportieren', 'cm_kk'); ?></button>
</form>
<?php
}



/**
 * Anzeigefunktion für tab-02: Kartenkontingent
 *
 * @since 0.0.1
 */

function cm_kk_show_tab_02()
{
    $kontingent_table = new MDB_Kontingent_List_Table();
    $kontingent_table->prepare_items();
    $kontingent_table->display();
}
