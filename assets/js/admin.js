jQuery(function($) {
/*
    @todo: Wiederherstellung der Funktionalität für 'normale' Attachments

    function mdb_lv_setFormFields() {
        val = $( '#mdb-lv-media-state' ).val();

        $( '.compat-field-mdb-lv-media-state' ).css( 'display', 'none' );

        $( '.compat-field-mdb-lv-by-link' ).hide();
        $( '.compat-field-mdb-lv-by-name' ).hide();
        $( '.compat-field-mdb-lv-license-guid' ).hide();
        $( '.compat-field-mdb-lv-media-link' ).hide();

        if( val > 0 ) {
            $( '.compat-field-mdb-lv-media-link' ).show();
        }

        if( val > 1 ) {
            $( '.compat-field-mdb-lv-by-link' ).show();
            $( '.compat-field-mdb-lv-by-name' ).show();
        }

        if( val > 2 ) {
            $( '.compat-field-mdb-lv-license-guid' ).show();
        }
    }
*/
    $(document).ready( function() {
    //    mdb_lv_setFormFields();
    } );




    /**
     * Für MediaGrid
     */

    var lv_AttachmentCompat = wp.media.view.AttachmentCompat;

	wp.media.view.AttachmentCompat = wp.media.view.AttachmentCompat.extend( {

        events: {
            'click select#mdb-lv-media-state': 'do_action',
            'change input':    'save',
            'change select':   'save',
            'change textarea': 'save'
        },

        render: function() {
            lv_AttachmentCompat.prototype.render.call( this );
            this.do_action();
        },

        do_action: function() {
            val = this.$( '#mdb-lv-media-state' ).val();

            this.$( '.compat-field-mdb-lv-by-link' ).hide();
            this.$( '.compat-field-mdb-lv-by-name' ).hide();
            this.$( '.compat-field-mdb-lv-license-guid' ).hide();
            this.$( '.compat-field-mdb-lv-media-link' ).hide();

            if( val > 0 ) {
                this.$( '.compat-field-mdb-lv-media-link' ).show();
            }

            if( val > 1 ) {
                this.$( '.compat-field-mdb-lv-by-link' ).show();
                this.$( '.compat-field-mdb-lv-by-name' ).show();
            }

            if( val > 2 ) {
                this.$( '.compat-field-mdb-lv-license-guid' ).show();
            }
        }
    } );

} );
