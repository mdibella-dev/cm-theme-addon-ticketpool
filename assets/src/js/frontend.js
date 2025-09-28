jQuery(function($) {

    $("#gdpr").change(function(e) {
        if( $("#gdpr").prop('checked') ) {
            $("#cmkk-submit").prop('disabled', false );
        }
        else {
            $("#cmkk-submit").prop('disabled', true );
        }
    } );
} );
