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

function show_mainpage_tab_02() {
    /** Process form if already submitted */

    if ( isset( $_POST['action'] ) ) {

        switch ( $_POST['action'] ) {
            case 'add-contingent':
                $size     = 0;
                $provider = '';

                if ( true === isset( $_POST['size'] ) ) {
                    $size = (int) trim( $_POST['size'] );
                }

                if ( true === isset( $_POST['provider'] ) ) {
                    $provider = trim( $_POST['provider'] );
                }

                if ( ! empty( $size ) and ! empty( $provider ) ) {
                    add_contingent( $_POST['event_id'], $_POST['size'], $_POST['provider'] );
                    display_admin_notice( NOTICE_TICKET_CONTINGENT_ADDED );
                } else {
                    display_admin_notice( NOTICE_EMPTY_FIELDS );
                }
                break;
        }
    }



    /** Output the tab */

    $pool_table = new MDB_Pool_List_Table();
    $pool_table->prepare_items();
    $pool_table->display();

    ?>
    <p class="cmkk-amount-info"><?php
        $total_amount = get_total_amount( EVENT_ID );

        if ( 0 == $total_amount) {
            echo __( 'There are currently no tickets available.', 'cm-theme-addon-ticketpool' );
        } else {
            echo sprintf(
                __( 'A total of %1$s tickets are available.', 'cm-theme-addon-ticketpool' ),
                $total_amount
            );
        }
    ?></p>
    <div class="form-wrap">
        <h2><?php echo __( 'Add new contingent', 'cm-theme-addon-ticketpool'); ?></h2>
        <form method="post" class="validate">

            <input type="hidden" name="event_id" value="<?php echo EVENT_ID; ?>">

            <table class="form-table form-table-add-contingent">

                <tr>
                    <th>
                        <label for="groesse"><?php echo __( 'Number of tickets', 'cm-theme-addon-ticketpool' ); ?></label>
                    </th>
                    <td>
                        <input type="number" min="1" name="size" id="size" type="text" value="1" size="3" aria-required="true">
                    </td>
                </tr>

                <tr>
                    <th>
                        <label for="anbieter"><?php echo __( 'Tickets are provided by', 'cm-theme-addon-ticketpool' ); ?></label>
                    </th>
                    <td>
                        <input type="text" name="provider" id="provider" type="text" value="" size="40" aria-required="true">
                    </td>
                </tr>

                <tr>
                    <th></th>
                    <td>
                        <p class="submit">
                            <button type="submit" name="action" class="button button-primary" value="add-contingent"><?php echo __( 'Add contingent', 'cm-theme-addon-ticketpool' ); ?></button>
                        </p>
                    </td>
                </tr>

            </table>

        </form>
    </div>
    <?php
}
