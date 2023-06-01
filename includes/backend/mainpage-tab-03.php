<?php
/**
 * The third tab of the main page.
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
 *
 * @todo Different emails for different events?
 * @todo Do not allow empty fields
 * @todo Reset to default text
 * @todo E-mail address for notification
 */

function show_mainpage_tab_03()
{
    /** Process form if already submitted */

    // Set up default values
    $default_subject = __( 'Thank you for participating', 'cm-theme-addon-ticketpool' );
    $default_message = __( 'Your participation in the event was registered. In the coming days you will receive another email with additional information.', 'cm-theme-addon-ticketpool' )
                       . '\n\n'
                       . __( 'Best regards', 'cm-theme-addon-ticketpool' )
                       . '\n\n'
                       . __( 'Attention: This email was generated automatically, please do not reply.', 'cm-theme-addon-ticketpool' );


    if( isset( $_POST['action'] ) ) :

        switch( $_POST['action'] ) :

            case 'update-template' :
                update_option( OPTION_MAIL_SUBJECT, $_POST['subject'] );
                update_option( OPTION_MAIL_MESSAGE, $_POST['message'] );
                do_admin_notice( NOTICE_EMAIL_TEMPLATE_UPDATED );
            break;

            case 'reset-template' :
                update_option( OPTION_MAIL_SUBJECT, $default_subject );     // or delete+add
                update_option( OPTION_MAIL_MESSAGE, $default_message );
                do_admin_notice( NOTICE_EMAIL_TEMPLATE_RESET );
            break;


        endswitch;

    endif;

    // Get options
    $subject = get_option( OPTION_MAIL_SUBJECT, $default_subject );
    $message = get_option( OPTION_MAIL_MESSAGE, $default_message );


    /** Output the tab */

    ?>
    <div class="form-wrap">
        <h2><?php echo __( 'Email template for participants', 'cm-theme-addon-ticketpool'); ?></h2>
        <form id="cmkk-mail-template-form" method="post" class="validate">

            <input type="hidden" name="event_id" value="<?php echo EVENT_ID; ?>">

            <table class="form-table">

                <tr>
                    <th><?php echo __( 'Subject', 'cm-theme-addon-ticketpool' ); ?></th>
                    <td>
                        <input type="text" name="subject" id="subject" type="text" value="<?php echo $subject; ?>" size="40" aria-required="true">
                    </td>
                </tr>

                <tr>
                    <th><?php echo __( 'Message', 'cm-theme-addon-ticketpool' ); ?></th>
                    <td>
                        <textarea name="message" id="message" type="text" row="20" cols"40" aria-required="true"><?php echo $message; ?></textarea>
                    </td>
                </tr>

                <tr>
                    <th></th>
                    <td>
                        <button type="submit" name="action" class="button button-primary" value="update-template"><?php echo __( 'Update', 'cm-theme-addon-ticketpool' ); ?></button>
                        <button type="submit" name="action" class="button button-primary" value="reset-template"><?php echo __( 'Reset', 'cm-theme-addon-ticketpool' ); ?></button>
                    </td>
                </tr>
            </table>

        </form>
    </div>
    <?php
}
