<?php
/**
 * Shortcode [cm-kk-form]
 *
 * Erzeugt ein Formular, mit denen sich Personen als Teilnehmer eintragen können
 *
 * Folgende Parameter können verwendet werden:
 * @param   event-id
 *
 * @author  Marco Di Bella <mdb@marcodibella.de>
 */

function cm_kk_shortcode_form( $atts, $content = null )
{
    /* Übergebene Parameter ermitteln */

    $default_atts = array(
        'event_id' => '',
    );

    extract( shortcode_atts( $default_atts, $atts ) );

    if( !empty( $event_id ) ) :

        if( isset( $_POST[ 'action' ] ) ) :

            $nachname = $_POST[ 'nachname' ];
            $vorname  = $_POST[ 'vorname' ];
            $email    = $_POST[ 'email' ];

            if ( true == cm_kk_add_beneficiary( $event_id, $nachname, $vorname, $email ) ) :
            endif;
        else :

            /* Ausgabenpufferung starten */

            ob_start();
?>
<form method="post" action="">
    <div class="form-row">
        <label for="vorname">Vorname</label>
        <input id="vorname" name="vorname" type="text">
    </div>
    <div class="form-row">
        <label for="vorname">Nachname</label>
        <input id="nachname" name="nachname" type="text">
    </div>
    <div class="form-row">
        <label for="vorname">E-Mail</label>
        <input id="email" name="email" type="email">
    </div>
    <div class="form-row">
        <input id="event-id" name="event-id" type="hidden" value="<?php echo $event_id; ?>">
        <button type="submit" name="action" class="button button-primary" value="add">Absenden</button>
    </div>
</form>

<?php
            /* Ausgabenpufferung beenden und Puffer ausgeben */
            $output_buffer = ob_get_contents();
            ob_end_clean();
            return $output_buffer;
        endif;
    endif;

    return null;
}

add_shortcode( 'cm-kk-form', 'cm_kk_shortcode_form' );
