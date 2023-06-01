<?php
/**
 * The fourth tab of the main page.
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
 * @todo E-mail address for notification
 */

function show_mainpage_tab_04()
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

            case 'reset-tables' :

                do_admin_notice( NOTICE_EMAIL_TEMPLATE_UPDATED );
            break;

            case 'reset-template' :
                update_option( OPTION_MAIL_SUBJECT, $default_subject );     // or delete+add
                update_option( OPTION_MAIL_MESSAGE, $default_message );
                do_admin_notice( NOTICE_EMAIL_TEMPLATE_RESET );
            break;


        endswitch;

    endif;


    /** Output the tab */

    ?>
    <div class="form-wrap">
        <form method="post" class="validate">

            <table class="form-table form-table-tools">

                <tr>
                    <td>
                        <h3><?php echo __( 'Reset Email Template', 'cm-theme-addon-ticketpool'); ?></h3>
                        <p><?php echo __( 'ATTENTION: This resets the email template to the default text.', 'cm-theme-addon-ticketpool'); ?></p>
                    </td>
                    <td class="button-col ">
                        <button type="submit" name="action" class="button button-primary" value="reset-template"><?php echo __( 'Reset', 'cm-theme-addon-ticketpool' ); ?></button>
                    </td>
                </tr>

                <tr>
                    <td colspan="2">
                        <hr>
                    </td>
                </tr>

                <tr>
                    <td>
                        <h3><?php echo __( 'Reset tables', 'cm-theme-addon-ticketpool'); ?></h3>
                        <p><?php echo __( 'ATTENTION: This clears all tables irrevocably.', 'cm-theme-addon-ticketpool'); ?></p>
                    </td>
                    <td class="button-col ">
                        <button type="submit" name="action" class="button button-primary" value="reset-tables"><?php echo __( 'Reset', 'cm-theme-addon-ticketpool' ); ?></button>
                    </td>
                </tr>

            </table>

        </form>
    </div>
    <?php
}
