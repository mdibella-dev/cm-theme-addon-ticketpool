<?php
/**
 * Klasse MDB_Kontingent_List_Table
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

class MDB_Kontingent_List_Table extends WP_List_Table
{

    function get_columns()
    {
        $columns = array(
        //    'event_id'                  => __( 'Event', 'cc_kk' ),
            'spalte_kontingentgroesse'  => __( 'Kontingentgröße', 'cc_kk' ),
            'spalte_bereitgestellt_von' => __( 'Bereitgestellt von', 'cc_kk' ),
            'spalte_bereitgestellt_am'  => __( 'Bereitgestellt am', 'cc_kk' ),
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

        $table_name  = $wpdb->prefix . TABLE_KONTINGENT;
        $table_data  = $wpdb->get_results( "SELECT * FROM $table_name", 'ARRAY_A' );
        $this->items = $table_data;
    }


    function column_default( $item, $column_name )
    {
        switch( $column_name ) :
            case 'event_id':
                return $item[ 'event_id' ];
            break;

            case 'spalte_kontingentgroesse':
                return $item[ 'groesse' ];
            break;

            case 'spalte_bereitgestellt_von':
                return $item[ 'bereitgestellt_von' ];
            break;

            case 'spalte_bereitgestellt_am':
                return $item[ 'bereitgestellt_am' ];
            break;

            default:
                return print_r( $item, true ); //Show the whole array for troubleshooting purposes
        endswitch;
    }
}
