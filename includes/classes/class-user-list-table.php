<?php
/**
 * Class MDB_User_List_Table
 *
 * @author  Marco Di Bella
 * @package cm-addon-ticketpool
 */

namespace cm_addon_ticketpool;


/** Prevent direct access */

defined( 'ABSPATH' ) or exit;



/**
 * Displays the participants who have cards from the card pool.
 *
 * @since 1.0.0
 *
 * @see http://wpengineer.com/2426/wp_list_table-a-step-by-step-guide/
 * @see https://wp.smashingmagazine.com/2011/11/native-admin-tables-wordpress/
 */

class MDB_User_List_Table extends MDB_Modified_List_Table
{

    function get_columns()
    {
        $columns = array(
            'col_name'      => __( 'Participant', 'cm-addon-ticketpool' ),
            'col_email'     => __( 'Email', 'cm-addon-ticketpool' ),
            'col_zeitpunkt' => __( 'Registration date', 'cm-addon-ticketpool' ),
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

        $table_name  = $wpdb->prefix . TABLE_USER;
        $sql         = "SELECT * FROM $table_name";
        $this->items = $wpdb->get_results( $sql, 'ARRAY_A' );
    }


    function column_default( $item, $column_name )
    {
        switch( $column_name ) :
            case 'col_name':
                return sprintf(
                    '%1$s %2$s',
                    $item['user_forename'],
                    $item['user_lastname']
                );
            break;

            case 'col_email':
                return $item['user_email'];
            break;

            case 'col_zeitpunkt':
                return $item['user_registered'];
            break;

            default:
                return print_r( $item, true );
        endswitch;
    }
}
