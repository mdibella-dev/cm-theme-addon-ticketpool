<?php
/**
 * The second tab of the main page.
 *
 * @author  Marco Di Bella
 * @package cm-theme-addon-ticketpool
 */

namespace cm_theme_addon_ticketpool;


/** Prevent direct access */

defined( 'ABSPATH' ) or exit;



/**
 * Displays the tab.
 *
 * @since 1.0.0
 */

function show_mainpage_tab_02()
{
    /** Process form if already submitted */

    if( isset( $_POST['action'] ) ) :

        switch( $_POST['action'] ) :

            case 'add-contingent' :
                cmkk_add_contingent( $_POST['event_id'], $_POST['groesse'], $_POST['anbieter'] );
                $_GET['cmkk-status'] = '3';
                do_action( 'admin_notices' );
            break;

        endswitch;

    endif;


    /** Output the tab */

    $pool_table = new MDB_Pool_List_Table();
    $pool_table->prepare_items();
    $pool_table->display();

    ?>
    <p class="cmkk-amount-info"><?php
        $total_amount = cmkk_get_total_amount( EVENT_ID );

        if( 0 == $total_amount) :
            echo __( 'There are currently no tickets available.', 'cm-theme-addon-ticketpool' );
        else :
            echo sprintf(
                __( 'A total of %1$s tickets are available.', 'cm-theme-addon-ticketpool' ),
                $total_amount
            );
        endif;
    ?></p>
    <div class="form-wrap">
        <h2><?php echo __( 'Add new contingent', 'cm-theme-addon-ticketpool'); ?></h2>
        <form id="cmkk-add-contingent-form" method="post" class="validate">

            <input type="hidden" name="event_id" value="<?php echo EVENT_ID; ?>">

            <div class="form-field form-required groesse-wrap">
                <label for="groesse"><?php echo __( 'Number of tickets', 'cm-theme-addon-ticketpool' ); ?></label>
                <input type="number" min="1" name="groesse" id="groesse" type="text" value="1" size="3" aria-required="true">
            </div>

            <div class="form-field form-required bereitgestellt-von-wrap">
                <label for="anbieter"><?php echo __( 'Tickets are provided by', 'cm-theme-addon-ticketpool' ); ?></label>
                <input type="text" name="anbieter" id="anbieter" type="text" value="" size="40" aria-required="true">
            </div>

            <p class="submit">
                <button type="submit" name="action" class="button button-primary" value="add-contingent"><?php echo __( 'Add contingent', 'cm-theme-addon-ticketpool' ); ?></button>
            </p>

        </form>
    </div>
    <?php
}
