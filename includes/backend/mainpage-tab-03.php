<?php
/**
 * The third tab of the main page.
 *
 * @author  Marco Di Bella
 * @package cm-theme-addon-kartenkontingent
 */


defined( 'ABSPATH' ) or exit;




/**
 * Zeigt Tab-03 mit der Verwaltung des E-Mail-Templates an (QUICK AND DIRTY)
 *
 * @since 1.0.0
 * @todo Verschiedene E-Mails für unterschiedliche Events?
 * @todo Keine leeren Felder zulassen
 * @todo Reset auf Standard-Text
 * @todo E-Mail-Adresse für Benachrichtigung
 */

function cmkk_show_tab_03()
{
    /* Formular bearbeiten, wenn bereits abgesendet */

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

    /* Ausgabe des Tabs */
    ?>
<?php

    // Anzeige des Formulars
?>
<div class="form-wrap">
<h2><?php echo __( 'E-Mail-Vorlage für Teilnehmer', 'cmkk'); ?></h2>
<form id="cmkk-mail-template-form" method="post" class="validate">

    <input type="hidden" name="event_id" value="<?php echo EVENT_ID; ?>">

    <table class="form-table">
        <tr>
            <th><?php echo __( 'Betreffzeile', 'cmkk' ); ?></th>
            <td>
                <input type="text" name="subject" id="subject" type="text" value="<?php echo $subject; ?>" size="40" aria-required="true">
            </td>
        </tr>

        <tr>
            <th><?php echo __( 'Nachricht', 'cmkk' ); ?></th>
            <td>
                <textarea name="message" id="message" type="text" row="20" cols"40" aria-required="true"><?php echo $message; ?></textarea>
            </td>
        </tr>

        <tr>
            <th></th>
            <td>
                <button type="submit" name="action" class="button button-primary" value="update-template"><?php echo __( 'Aktualisieren', 'cmkk' ); ?></button>
            </td>
        </tr>
    </table>

</form>
<?php
}
