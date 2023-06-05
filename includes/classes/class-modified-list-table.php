<?php
/**
 * Class MDB_Modified_List_Table
 *
 * @author  Marco Di Bella
 * @package cm-theme-addon-ticketpool
 */

namespace cm_theme_addon_ticketpool;


/** Prevent direct access */

defined( 'ABSPATH' ) or exit;



/**
 * Table with modified output of TableNav.
 *
 * @since 1.0.0
 */

class MDB_Modified_List_Table extends \WP_List_Table
{
    function display_tablenav( $which )
    {
        if( 'top' === $which ) :
            wp_nonce_field( 'bulk-' . $this->_args['plural'] );
        endif;

        /* Modification */
        if( empty( $this->_pagination_args ) ) :
            return;
        endif;

        ?>
        <div class="tablenav <?php echo esc_attr( $which ); ?>">
            <?php if( $this->has_items() ) : ?>

            <div class="alignleft actions bulkactions">
                <?php $this->bulk_actions( $which ); ?>
            </div>
            <?php
            endif;
            $this->extra_tablenav( $which );
            $this->pagination( $which );
            ?>

            <br class="clear">
        </div>
        <?php
    }
}
