<?php
/**
 * Hauptseite des Plugins
 *
 * @author Marco Di Bella <mdb@marcodibella.de>
 */


// Check & Quit
defined( 'ABSPATH' ) OR exit;



/**
 * Integriert die Hauptseite in das 'Congressomat'-Menü
 *
 * @since   0.0.1
 */

function cm_kk_add_mainpage_to_admin_menu()
{
    $admin_menu_slug = 'edit.php?post_type=session'; // // von Congressomat

    add_submenu_page(
        $admin_menu_slug,
        __( 'Kartenkontingente', 'cm_kk' ),
        __( 'Kartenkontingente', 'cm_kk' ),
        'manage_options',
        'cm_kk_main',
        'cm_kk_show_mainpage',
        0,
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
?>
<div class="wrap">
<h1 class="wp-heading-inline"><?php _e( 'Kartenkontingente', 'cm_kk' )?></h1>
<?php

    $active_tab = 'tab-01';

    if( isset( $_GET[ 'tab' ] ) ) :
        switch( $active_tab ) :
            case 'tab-01' :
                $active_tab = 'tab-01';
            break;

            case 'tab-02' :
                $active_tab = 'tab-02';
            break;
        endswitch;
    endif;
?>
<h2 class="nav-tab-wrapper">
<a href="?page=cm_kk_main&tab=tab-01" class="nav-tab <?php if( $active_tab == 'tab-01'){ echo 'nav-tab-active'; } ?>"><?php _e( 'Übersicht', 'cm_kk'); ?></a>
<a href="?page=cm_kk_main&tab=tab-02" class="nav-tab <?php if( $active_tab == 'tab-02'){ echo 'nav-tab-active'; } ?>"><?php _e( 'Kartenkontigent', 'cm_kk'); ?></a>
</h2>
<?php
    switch( $active_tab ) :
        case 'tab-01' :
            echo cm_kk_get_used_amount() . ' of ' . cm_kk_get_total_amount() . 'used';
        break;

        case 'tab-02' :

        break;
    endswitch
?>
</div>
<?php
}



/*



function mdb_lv_show_licenses_tab()
{
?>
<h2><?php echo __( 'Verfügbare Lizenzen', 'mdb_lv' )?></h2>
<?php
    $maintable = new MDB_main_table();
    $maintable->prepare_items();
    $maintable->display();
}




function mdb_lv_show_indexing_tab()
{
?>
<h2><?php echo __( 'Indizierung der Medien', 'mdb_lv' )?></h2>
<?php
    global $wpdb;
           $total = $wpdb->get_var( "SELECT COUNT(ID) FROM $wpdb->posts WHERE (post_type='attachment') AND (post_mime_type LIKE '%%image%%')" );

    if( $total == 0 ) :
        echo '<p>';
        echo __( 'In der Mediathek befinden sich derzeit keine Bilder.', 'mdb_lv' );
        echo '</p>';
    else :
        echo '<p>';
        echo sprintf( __( 'In der Mediathek befinden sich derzeit %1$s Bilder.', 'mdb_lv' ), $total );
        echo '<br>';
        echo __( 'Bevor Sie mit der Erfassung der Bildlizenzen fortfahren, führen Sie bitte einmalig den Indizierungsvorgang durch.', 'mdb_lv' );
        echo '</p>';
        echo '<form action="" method="post">';
        echo '<input name="mdb_lv_index" type="hidden" value="go">';
        echo '<input type="submit" class="button" value="Starten">';
        echo '</form>';
    endif;

}

*/
