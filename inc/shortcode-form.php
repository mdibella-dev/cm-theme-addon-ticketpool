<?php
/**
 * Shortcode [cmkk-form]
 *
 * @since   1.0.0
 * @author  Marco Di Bella <mdb@marcodibella.de>
 */


defined( 'ABSPATH' ) OR exit;



/**
 * Shortcode zum Erzeugen eines Formulars, mit denen sich Personen als Teilnehmer eintragen können
 *
 * @since   1.0.0
 * @todo    - Validierung der Eingabe
 *          - Korrekte Reaktion auf bereits abgesendete Formular ergänzen
 *
 * @param   array   $atts   die Attribute (Parameter) des Shorcodes
 * @return  string          die vom Shortcode erzeugte Ausgabe
 */

function cmkk_shortcode_form( $atts, $content = null )
{
    $default_atts = array(
        'event_id' => '',
    );

    extract( shortcode_atts( $default_atts, $atts ) );


    if( empty( $event_id ) ) :
        return '';
    endif;


    /* Formular bearbeiten, wenn bereits abgesendet */

    if( isset( $_POST['action'] ) ) :

        $nachname = $_POST['nachname'];
        $vorname  = $_POST['vorname'];
        $email    = $_POST['email'];

        if ( TRUE == cmkk_add_user( $event_id, $nachname, $vorname, $email ) ) :

        endif;

    endif;


    /* Ausgabe des Shortcodes */

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
    $output_buffer = ob_get_contents();
    ob_end_clean();
    return $output_buffer;
}

add_shortcode( 'cmkk-form', 'cmkk_shortcode_form' );
