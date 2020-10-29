<?php
/**
 * Klasse MDB_Pool_List_Table
 *
 * @since   1.0.0
 * @author  Marco Di Bella <mdb@marcodibella.de>
 */


defined( 'ABSPATH' ) OR exit;



/**
 * Funktionsbibliothek einbinden
 */

if( ! class_exists( 'WP_List_Table' ) ) :
    require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
endif;



/**
 * Zeigt die Kontingente an, die den Pool bilden
 *
 * @since   1.0.0
 * @see     http://wpengineer.com/2426/wp_list_table-a-step-by-step-guide/
 * @see     https://wp.smashingmagazine.com/2011/11/native-admin-tables-wordpress/
 */


class MDB_Pool_List_Table extends WP_List_Table
{

    function get_columns()
    {
        $columns = array(
        //    'event_id'                  => __( 'Event', 'cc_kk' ),
            'col_kontingentgroesse'  => __( 'Kontingentgröße', 'cc_kk' ),
            'col_bereitgestellt_von' => __( 'Bereitgestellt von', 'cc_kk' ),
            'col_bereitgestellt_am'  => __( 'Bereitgestellt am', 'cc_kk' ),
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

        $table_name  = $wpdb->prefix . TABLE_POOL;
        $table_data  = $wpdb->get_results( "SELECT * FROM $table_name", 'ARRAY_A' );
        $this->items = $table_data;
    }


    function column_default( $item, $column_name )
    {
        switch( $column_name ) :
            case 'event_id':
                return $item['event_id'];
            break;

            case 'col_kontingentgroesse':
                return $item['groesse'];
            break;

            case 'col_bereitgestellt_von':
                return $item['bereitgestellt_von'];
            break;

            case 'col_bereitgestellt_am':
                return $item['bereitgestellt_am'];
            break;

            default:
                return print_r( $item, true );
        endswitch;
    }
}
