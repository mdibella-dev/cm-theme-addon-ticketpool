<?php
/**
 * Tab-03 der Hauptseite
 *
 * @since   1.0.0
 * @author  Marco Di Bella <mdb@marcodibella.de>
 */


defined( 'ABSPATH' ) OR exit;




/**
 * Zeigt Tab-03 mit der Verwaltung des E-Mail-Templates an
 *
 * @since   1.0.0
 * @todo    - Verschiedene E-Mails für unterschiedliche Events?
 */

function cmkk_show_tab_03()
{
    /* Formular bearbeiten, wenn bereits abgesendet */

    if( isset( $_POST['action'] ) ) :

        switch( $_POST['action'] ) :

            case 'update-template' :
                update_option( OPTION_MAIL_SUBJECT, $_POST['subject'] );
                update_option( OPTION_MAIL_MESSAGE, $_POST['message'] );
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

    <div class="form-field form-required subject-wrap">
    	<label for="subject"><?php echo __( 'Betreffzeile', 'cmkk' ); ?></label>
    	<input type="text" name="subject" id="subject" type="text" value="<?php echo $subject; ?>" size="40" aria-required="true">
    </div>

    <div class="form-field form-required message-wrap">
    	<label for="message"><?php echo __( 'Nachricht', 'cmkk' ); ?></label>
    	<textarea name="message" id="message" type="text" row="20" cols"40" aria-required="true"><?php echo $message; ?></textarea>
    </div>

    <p class="submit">
        <button type="submit" name="action" class="button button-primary" value="update-template"><?php echo __( 'Aktualisieren', 'cmkk' ); ?></button>
    </p>

</form>
<?php
}
