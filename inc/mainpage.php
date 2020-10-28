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
                cm_kk_export_teilnehmer( EVENT_ID );
            break;

        endswitch;

    endif;
?>
<div class="wrap">
<h1 class="wp-heading-inline"><?php _e( 'Kartenkontingente', 'cm_kk' )?></h1>
<?php
    $tabs        = array( 'tab-01', 'tab-02' );
    $tab_default = $tabs[ 0 ];
    $tab_select  = isset( $_GET[ 'tab' ] )? $_GET[ 'tab' ] : $tab_default;
    $tab_active  = ( in_array( $tab_select, $tabs ) )? $tab_select : $tab_default;
?>
<h2 class="nav-tab-wrapper">
<a href="?page=cm_kk_mainpage&tab=tab-01" class="nav-tab <?php if( $tab_active == 'tab-01'): echo 'nav-tab-active'; endif; ?>"><?php _e( 'Übersicht', 'cm_kk'); ?></a>
<a href="?page=cm_kk_mainpage&tab=tab-02" class="nav-tab <?php if( $tab_active == 'tab-02'): echo 'nav-tab-active'; endif; ?>"><?php _e( 'Einzelne Kartenkontigente', 'cm_kk'); ?></a>
</h2>
<?php
    switch( $tab_active ) :
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
    $teilnehmer_table = new MDB_Teilnehmer_List_Table();
    $teilnehmer_table->prepare_items();
    $teilnehmer_table->display();
?>
<p class="cm-kk-amount-info"><?php
    $total_amount = cm_kk_get_total_amount( EVENT_ID ); // $_POST[ 'event_id' ]
    $free_amount  = cm_kk_get_free_amount( EVENT_ID );

    if( 0 == $total_amount ) :
        _e( 'Derzeit stehen keine Plätze zur Verfügung.', 'cm_kk' );
    else :
        echo sprintf( __( 'Insgesamt stehen %1$s Plätze zur Verfügung, davon sind Plätze %2$s unbesetzt.', 'cm_kk' ),
            $total_amount,
            $free_amount,
        );
    endif;
?></p>
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
    if( isset( $_POST[ 'action' ] ) ) :

        switch( $_POST[ 'action' ] ) :

            case 'add-contingent' :
                cm_kk_add_contingent( $_POST[ 'event_id'], $_POST[ 'groesse'], $_POST[ 'anbieter'] );
            break;

        endswitch;

    endif;

    $kontingent_table = new MDB_Kontingent_List_Table();
    $kontingent_table->prepare_items();
    $kontingent_table->display();
?>
<p class="cm-kk-amount-info"><?php
    $total_amount = cm_kk_get_total_amount( EVENT_ID ); // $_POST[ 'event_id' ]

    if( 0 == $total_amount) :
        _e( 'Derzeit stehen keine Plätze zur Verfügung.', 'cm_kk' );
    else :
        echo sprintf( __( 'Insgesamt stehen %1$s Plätze zur Verfügung.', 'cm_kk' ), $total_amount );
    endif;
?></p>
<div class="form-wrap">
<h2><?php _e( 'Neues Kontingent hinzufügen', 'cm_kk'); ?></h2>
<form id="cm-kk-add-contingent-form" method="post" class="validate">
    <input type="hidden" name="action" value="add">
    <input type="hidden" name="event_id" value="<?php echo EVENT_ID; ?>">

    <div class="form-field form-required groesse-wrap">
    	<label for="groesse"><?php _e( 'Anzahl Plätze', 'cm_kk'); ?></label>
    	<input type="number" min="1" name="groesse" id="groesse" type="text" value="1" size="3" aria-required="true">
    </div>

    <div class="form-field form-required bereitgestellt-von-wrap">
    	<label for="anbieter"><?php _e( 'Plätze werden bereitgestellt von', 'cm_kk'); ?></label>
    	<input type="text" name="anbieter" id="anbieter" type="text" value="" size="40" aria-required="true">
    </div>

    <p class="submit">
        <button type="submit" name="action" class="button button-primary" value="add-contingent"><?php _e( 'Kontingent hinzufügen', 'cm_kk'); ?></button>
    </p>
</form>
<?php
}
