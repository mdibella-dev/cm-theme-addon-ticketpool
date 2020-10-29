<?php
/**
 * Klasse MDB_Pool_List_Table
 *
 * @since   1.0.0
 * @author  Marco Di Bella <mdb@marcodibella.de>
 */


defined( 'ABSPATH' ) OR exit;



/* Funktionsbibliothek einbinden */

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
            'col_groesse'   => __( 'Kontingentgröße', 'cc_kk' ),
            'col_anbieter'  => __( 'Bereitgestellt von', 'cc_kk' ),
            'col_zeitpunkt' => __( 'Bereitgestellt am', 'cc_kk' ),
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

        $pool_table_name  = $wpdb->prefix . TABLE_POOL;
        $this->items = $wpdb->get_results(
            "SELECT * FROM $pool_table_name",
            'ARRAY_A'
        );
    }


    function column_default( $item, $column_name )
    {
        switch( $column_name ) :
            case 'event_id':
                return $item['event_id'];
            break;

            case 'col_groesse':
                return $item['contingent_size'];
            break;

            case 'col_anbieter':
                return $item['$contingent_provider'];
            break;

            case 'col_zeitpunkz':
                return $item['contingent_provided'];
            break;

            default:
                return print_r( $item, TRUE );
        endswitch;
    }
}
