<?php
/**
 * Klasse MDB_Teilnehmer_List_Table
 *
 * @author Marco Di Bella <mdb@marcodibella.de>
 */



// Check & Quit
defined( 'ABSPATH' ) OR exit;



if( ! class_exists( 'WP_List_Table' ) ) :
    require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
endif;



/**
 * @see http://wpengineer.com/2426/wp_list_table-a-step-by-step-guide/
 * @see https://wp.smashingmagazine.com/2011/11/native-admin-tables-wordpress/
 */

class MDB_Teilnehmer_List_Table extends WP_List_Table
{

    function get_columns()
    {
        $columns = array(
            'spalte_name'      => __( 'Teilnehmer', 'cc_kk' ),
            'spalte_email'     => __( 'E-Mail', 'cc_kk' ),
            'spalte_zeitpunkt' => __( 'Anmeldung am', 'cc_kk' ),
        );

        return $columns;
    }


    function prepare_items()
    {
        $columns  = $this->get_columns();
        $hidden   = array();
        $sortable = array(); //$this->get_sortable_columns();

        $this->_column_headers = array( $columns, $hidden, $sortable );


        global $wpdb;

        $table_name  = $wpdb->prefix . TABLE_TEILNEHMER;
        $table_data  = $wpdb->get_results( "SELECT * FROM $table_name", 'ARRAY_A' );
        $this->items = $table_data;
    }


    function column_default( $item, $column_name )
    {
        switch( $column_name ) :
            case 'spalte_name':
                return sprintf(
                    '%1$s %2$s',
                    $item[ 'vorname' ],
                    $item[ 'nachname' ]
                );
            break;

            case 'spalte_email':
                return $item[ 'email' ];
            break;

            case 'spalte_zeitpunkt':
                return $item[ 'zeitpunkt' ];
            break;

            default:
                return print_r( $item, true ); //Show the whole array for troubleshooting purposes
        endswitch;
    }
}
