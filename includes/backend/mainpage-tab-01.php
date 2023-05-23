<?php
/**
 * The first tab of the main page.
 *
 * @author  Marco Di Bella <mdb@marcodibella.de>
 */


defined( 'ABSPATH' ) or exit;




/**
 * Displays the tab.
 *
 * @since 1.0.0
 */

function cmkk_show_tab_01()
{
    $file_info = cmkk_create_user_export_file( EVENT_ID );

    if( FALSE === $file_info ) :
        // error message?;
    endif;


    /** Output the tab */

    $user_table = new MDB_User_List_Table();
    $user_table->prepare_items();
    $user_table->display();

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

    // Show possibility to download the export file
    if( false !== $file_info ) :
    ?>
        <a class="button button-primary" href="<?php echo $file_info['url']; ?>" download><?php echo __( 'Daten als CSV exportieren', 'cmkk'); ?></a>
    <?php
    endif;
}
