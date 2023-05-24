<?php
/**
 * The first tab of the main page.
 *
 * @author  Marco Di Bella <mdb@marcodibella.de>
 */

namespace cm_theme_addon_ticketpool;


/** Prevent direct access */

defined( 'ABSPATH' ) or exit;



/**
 * Displays the tab.
 *
 * @since 1.0.0
 */

function show_mainpage_tab_01()
{
    $file_info = cmkk_create_user_export_file( EVENT_ID );

    if( false === $file_info ) :
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
            echo __( 'There are currently no tickets available.', 'cm-tp' );
        else :
            echo sprintf(
                __( 'A total of %1$s tickets are available, of which %2$s tickets are unused.', 'cm-tp' ),
                $total_amount,
                $free_amount,
            );
        endif;
    ?></p>
    <?php

    // Show possibility to download the export file
    if( false !== $file_info ) :
    ?>
        <a class="button button-primary" href="<?php echo $file_info['url']; ?>" download><?php echo __( 'Export data as CSV', 'cm-tp'); ?></a>
    <?php
    endif;
}
