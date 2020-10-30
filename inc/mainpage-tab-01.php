<?php
/**
 * Tab-01 der Hauptseite
 *
 * @since   1.0.0
 * @author  Marco Di Bella <mdb@marcodibella.de>
 */


defined( 'ABSPATH' ) OR exit;




/**
 * Zeigt Tab-01 mit der Übersicht der Teilnehmer sowie der Download-Funktionalität an
 *
 * @since   1.0.0
 */

function cmkk_show_tab_01()
{
    /* Formular bearbeiten, wenn bereits abgesendet */

    if( isset( $_POST['action'] ) ) :

        switch( $_POST['action'] ) :
            case 'export' :
                cmkk_export_teilnehmer( EVENT_ID );
            break;

        endswitch;

    endif;


    /* Ausgabe des Tabs */


    // Tabelle anzeigen
    $user_table = new MDB_User_List_Table();
    $user_table->prepare_items();
    $user_table->display();


    // Poolgröße und bereits belegte Plätze anzeigen
?>
<p class="cmkk-amount-info"><?php
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
