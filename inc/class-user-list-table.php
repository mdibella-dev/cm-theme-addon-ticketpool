<?php
/**
 * Klasse MDB_User_List_Table
 *
 * @author  Marco Di Bella <mdb@marcodibella.de>
 */


defined( 'ABSPATH' ) OR exit;



/* Funktionsbibliothek einbinden */

if( ! class_exists( 'WP_List_Table' ) ) :
    require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
endif;



/**
 * Zeigt die Teilnehmer an, die über Karten aus dem Pool verfügen
 *
 * @since   1.0.0
 * @see     http://wpengineer.com/2426/wp_list_table-a-step-by-step-guide/
 * @see     https://wp.smashingmagazine.com/2011/11/native-admin-tables-wordpress/
 */

class MDB_User_List_Table extends MDB_Modified_List_Table
{

    function get_columns()
    {
        $columns = array(
            'col_name'      => __( 'Teilnehmer', 'cc_kk' ),
            'col_email'     => __( 'E-Mail', 'cc_kk' ),
            'col_zeitpunkt' => __( 'Anmeldung am', 'cc_kk' ),
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
                return print_r( $item, TRUE );
        endswitch;
    }
}
