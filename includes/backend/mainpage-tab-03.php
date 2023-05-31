<?php
/**
 * The third tab of the main page.
 *
 * @author  Marco Di Bella
 * @package cm-addon-ticketpool
 */

namespace cm_addon_ticketpool;


/** Prevent direct access */

defined( 'ABSPATH' ) or exit;




/**
 * Displays the tab.
 *
 * @since 1.0.0
 *
 * @todo Different emails for different events?
 * @todo Do not allow empty fields
 * @todo Reset to default text
 * @todo E-mail address for notification
 */

function show_mainpage_tab_03()
{
    /** Process form if already submitted */

    if( isset( $_POST['action'] ) ) :

        switch( $_POST['action'] ) :

            case 'update-template' :
                update_option( OPTION_MAIL_SUBJECT, $_POST['subject'] );
                update_option( OPTION_MAIL_MESSAGE, $_POST['message'] );
                $_GET['cmkk-status'] = '2';
                do_action( 'admin_notices' );
            break;

        endswitch;

    endif;

    $subject = get_option( OPTION_MAIL_SUBJECT );
    $message = get_option( OPTION_MAIL_MESSAGE );


    /** Output the tab */

    ?>
    <div class="form-wrap">
        <h2><?php echo __( 'Email template for participants', 'cm-addon-ticketpool'); ?></h2>
        <form id="cmkk-mail-template-form" method="post" class="validate">

            <input type="hidden" name="event_id" value="<?php echo EVENT_ID; ?>">

            <table class="form-table">
                <tr>
                    <th><?php echo __( 'Subject', 'cm-addon-ticketpool' ); ?></th>
                    <td>
                        <input type="text" name="subject" id="subject" type="text" value="<?php echo $subject; ?>" size="40" aria-required="true">
                    </td>
                </tr>

                <tr>
                    <th><?php echo __( 'Message', 'cm-addon-ticketpool' ); ?></th>
                    <td>
                        <textarea name="message" id="message" type="text" row="20" cols"40" aria-required="true"><?php echo $message; ?></textarea>
                    </td>
                </tr>

                <tr>
                    <th></th>
                    <td>
                        <button type="submit" name="action" class="button button-primary" value="update-template"><?php echo __( 'Update', 'cm-addon-ticketpool' ); ?></button>
                    </td>
                </tr>
            </table>

        </form>
    </div>
    <?php
}
