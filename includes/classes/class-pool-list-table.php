<?php
/**
 * Class MDB_Pool_List_Table
 *
 * @author  Marco Di Bella
 * @package cm-theme-addon-ticketpool
 */

namespace cm_theme_addon_ticketpool;


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

class MDB_Pool_List_Table extends MDB_Modified_List_Table {

    function get_columns() {
        $columns = [
            'col_size'     => __( 'Contingent size', 'cm-theme-addon-ticketpool' ),
            'col_provider' => __( 'Provided by', 'cm-theme-addon-ticketpool' ),
            'col_provided' => __( 'Provided on', 'cm-theme-addon-ticketpool' ),
        ];

        return $columns;
    }



    function prepare_items() {
        $this->_column_headers = [
            $this->get_columns(),   // columns
            [],                     // hidden
            [],                     // sortable
        ];

        global $wpdb;

        $table_name  = $wpdb->prefix . TABLE_POOL;
        $sql         = "SELECT * FROM $table_name";
        $this->items = $wpdb->get_results( $sql, 'ARRAY_A' );
    }



    function column_default( $item, $column_name ) {

        switch ( $column_name ) {

            case 'event_id':
                return $item['event_id'];

            case 'col_size':
                return $item['contingent_size'];

            case 'col_provider':
                return $item['contingent_provider'];

            case 'col_provided':
                return date(
                    __( 'Y-m-d H:i', 'cm-theme-addon-ticketpool' ),
                    strtotime( $item['contingent_provided'] )
                );

            default:
                return print_r( $item, true );
        }
    }

}
