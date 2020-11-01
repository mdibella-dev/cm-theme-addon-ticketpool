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
    $file_info = cmkk_create_user_export_file( EVENT_ID );

    if( FALSE === $file_info ) :
        // Fehlermeldung?;
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

    // Möglichkeit zum Download der Exportdatei anzeigen
    if( FALSE !== $file_info ) :
?>
<a class="button button-primary" href="<?php echo $file_info['url']; ?>" download><?php echo __( 'Daten als CSV exportieren', 'cmkk'); ?></a>
<?php
    endif;
}
