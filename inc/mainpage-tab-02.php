<?php
/**
 * Tab-02 der Hauptseite
 *
 * @author  Marco Di Bella <mdb@marcodibella.de>
 */


defined( 'ABSPATH' ) OR exit;




/**
 * Zeigt Tab-02 mit der Verwaltung der Kartenkontingente an
 *
 * @since   1.0.0
 */

function cmkk_show_tab_02()
{
    /* Formular bearbeiten, wenn bereits abgesendet */

    if( isset( $_POST['action'] ) ) :

        switch( $_POST['action'] ) :

            case 'add-contingent' :
                cmkk_add_contingent( $_POST['event_id'], $_POST['groesse'], $_POST['anbieter'] );
                $_GET['cmkk-status'] = '3';
                do_action( 'admin_notices' );
            break;

        endswitch;

    endif;


    /* Ausgabe des Tabs */


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
<form id="cmkk-add-contingent-form" method="post" class="validate">

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
