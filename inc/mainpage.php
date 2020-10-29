<?php
/**
 * Hauptseite des Plugins
 *
 * @since   1.0.0
 * @author  Marco Di Bella <mdb@marcodibella.de>
 */


defined( 'ABSPATH' ) OR exit;



/**
 * Erzeugt einen Menüpunkt im Backend
 *
 * @since   1.0.0
 * @todo    In das Congressomat-Menü verschieben (setzt Umbau von Congressomat voraus)
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
    $tabs        = array(
        'tab-01' => "cmkk_show_tab_01",
        'tab-02' => "cmkk_show_tab_02",
    );

    if( isset( $_GET['tab'] ) and array_key_exists( $_GET['tab'], $tabs ) ) :
        $tab_active = $_GET['tab'];
    endif; :
        $tab_active = 'tab-01';
    endif;
?>
<h2 class="nav-tab-wrapper">
<a href="?page=cmkk_mainpage&tab=tab-01" class="nav-tab <?php if( $tab_active == 'tab-01'): echo 'nav-tab-active'; endif; ?>"><?php echo __( 'Übersicht', 'cmkk'); ?></a>
<a href="?page=cmkk_mainpage&tab=tab-02" class="nav-tab <?php if( $tab_active == 'tab-02'): echo 'nav-tab-active'; endif; ?>"><?php echo __( 'Einzelne Kartenkontigente', 'cmkk'); ?></a>
</h2>
<?php
    call_user_func( (string) $tabs[ $tab_active ] );
?>
</div>
<?php
}



/**
 * Anzeigefunktion für tab-01: Übersicht
 *
 * @since   1.0.0
 */

function cmkk_show_tab_01()
{
    /*
     * Formular bearbeiten, wenn bereits abgesendet
     */

    if( isset( $_POST['action'] ) ) :

        switch( $_POST['action'] ) :
            case 'export' :
                cmkk_export_teilnehmer( EVENT_ID );
            break;

        endswitch;

    endif;


    /*
     * Ausgabe des Tabs
     */


    // Tabelle anzeigen
    $user_table = new MDB_User_List_Table();
    $user_table->prepare_items();
    $user_table->display();


    // Poolgröße und bereits belegte Plätze anzeigen
?>
<p class="cm-kk-amount-info"><?php
    $total_amount = cmkk_get_total_amount( EVENT_ID ); // $_POST[ 'event_id' ]
    $free_amount  = cmkk_get_free_amount( EVENT_ID );

    if( 0 == $total_amount ) :
        echo __( 'Derzeit stehen keine Plätze zur Verfügung.', 'cmkk' );
    else :
        echo sprintf(
            __( 'Insgesamt stehen %1$s Plätze zur Verfügung, davon sind Plätze %2$s unbesetzt.', 'cmkk' ),
            $total_amount,
            $free_amount,
        );
    endif;
?></p>
<?php

    // Formular, um den Download der Teilnehmerliste zu initiieren, anzeigen
?>
<form method="post">
    <button type="submit" name="action" class="button button-primary" value="export"><?php echo __( 'Daten als CSV exportieren', 'cmkk'); ?></button>
</form>
<?php
}



/**
 * Anzeigefunktion für tab-02: Kartenkontingent
 *
 * @since   1.0.0
 */

function cmkk_show_tab_02()
{
    /*
     * Formular bearbeiten, wenn bereits abgesendet
     */

    if( isset( $_POST['action'] ) ) :

        switch( $_POST['action'] ) :

            case 'add-contingent' :
                cmkk_add_contingent( $_POST['event_id'], $_POST['groesse'], $_POST['anbieter'] );
            break;

        endswitch;

    endif;


    /*
     * Ausgabe des Tabs
     */


    // Tabelle anzeigen
    $pool_table = new MDB_Pool_List_Table();
    $pool_table->prepare_items();
    $pool_table->display();


    // Poolgröße anzeigen
?>
<p class="cmkk-amount-info"><?php
    $total_amount = cmkk_get_total_amount( EVENT_ID ); // $_POST['event_id']

    if( 0 == $total_amount) :
        echo __( 'Derzeit stehen keine Plätze zur Verfügung.', 'cmkk' );
    else :
        echo sprintf(
            __( 'Insgesamt stehen %1$s Plätze zur Verfügung.', 'cmkk' ),
            $total_amount
        );
    endif;
?></p>
<?php

    // Anzeige des Formulars
?>
<div class="form-wrap">
<h2><?php echo __( 'Neues Kontingent hinzufügen', 'cmkk'); ?></h2>
<form id="cm-kk-add-contingent-form" method="post" class="validate">

    <input type="hidden" name="event_id" value="<?php echo EVENT_ID; ?>">

    <div class="form-field form-required groesse-wrap">
    	<label for="groesse"><?php echo __( 'Anzahl Plätze', 'cmkk' ); ?></label>
    	<input type="number" min="1" name="groesse" id="groesse" type="text" value="1" size="3" aria-required="true">
    </div>

    <div class="form-field form-required bereitgestellt-von-wrap">
    	<label for="anbieter"><?php echo __( 'Plätze werden bereitgestellt von', 'cmkk' ); ?></label>
    	<input type="text" name="anbieter" id="anbieter" type="text" value="" size="40" aria-required="true">
    </div>

    <p class="submit">
        <button type="submit" name="action" class="button button-primary" value="add-contingent"><?php echo __( 'Kontingent hinzufügen', 'cmkk' ); ?></button>
    </p>

</form>
<?php
}
