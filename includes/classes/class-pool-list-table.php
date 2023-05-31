<?php
/**
 * Class MDB_Pool_List_Table
 *
 * @author  Marco Di Bella
 * @package cm-addon-ticketpool
 */

namespace cm_addon_ticketpool;


/** Prevent direct access */

defined( 'ABSPATH' ) or exit;



/**
 * Displays the card contingents that make up the card pool.
 *
 * @since 1.0.0
 *
 * @see http://wpengineer.com/2426/wp_list_table-a-step-by-step-guide/
 * @see https://wp.smashingmagazine.com/2011/11/native-admin-tables-wordpress/
 */

class MDB_Pool_List_Table extends MDB_Modified_List_Table
{

    function get_columns()
    {
        $columns = array(
            'col_groesse'   => __( 'Contingent size', 'cm-addon-ticketpool' ),
            'col_anbieter'  => __( 'Provided by', 'cm-addon-ticketpool' ),
            'col_zeitpunkt' => __( 'Provided on', 'cm-addon-ticketpool' ),
        );

        return $columns;
    }


    function prepare_items()
    {
        $this->_column_headers = array(
            $this->get_columns(),    // columns
            array(),                 // hidden,
            array(),                 // sortable
        );

        global $wpdb;

        $table_name  = $wpdb->prefix . TABLE_POOL;
        $sql         = "SELECT * FROM $table_name";
        $this->items = $wpdb->get_results( $sql, 'ARRAY_A' );
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
                return $item['contingent_provider'];
            break;

            case 'col_zeitpunkt':
                return $item['contingent_provided'];
            break;

            default:
                return print_r( $item, true );
        endswitch;
    }

}
