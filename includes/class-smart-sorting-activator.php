<?php

/**
 * Fired during plugin activation.
 *
 * @license     https://www.gnu.org/licenses/lgpl-3.0.txt  LGPL License 3.0
 * @since       1.0.0-alpha
 *
 * @package     SmartSorting
 * @subpackage  SmartSorting/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @package     SmartSorting
 * @subpackage  SmartSorting/includes
 * @author      SmartSorting Team <smartsprtingofficial@gmail.com>
 */

class Smart_Sorting_Activator {

    /**
     * For all created products, adds metadata that is used for sorting.
     *
     * @since   1.0.0-alpha
     */
    private static function add_spv_field() {

        $posts_meta = array(
            'spv_views',
            'spv_sales',
            'spv',
        );

        $params = array(
            'post_type' => 'product',
            'meta_key'  => 'total_sales',
            'orderby'   => 'meta_value_num',
            'order'     => 'DESC',
        );

        $product_list = (new WP_Query( $params ))->posts;

        $max_sales = (float) get_post_meta(
            $product_list[0]->ID,
            'total_sales',
            true
        );

        foreach ( $product_list as $product) {
            $id    = $product->ID;
            $views = 0;
            $sales = 0;
            foreach ( $posts_meta as $key ) {
                $value = (float) get_post_meta( $id, $key, true );
                if ( 'spv_views' == $key ) {
                    if( '' == $value ) {
                        $value = 100;
                    }
                    $views = $value;
                } elseif ( 'spv_sales' == $key ) {
                    if( '' == $value ) {
                        $value = (float) get_post_meta($id, 'total_sales', true);
                        $value = (int)(((($value) / $max_sales) *
                                0.5 + 0.5) * 100);
                    }
                    $sales = $value;
                } elseif ( 'spv' == $key ) {
                    $value = (float) $sales / (float) $views;
                }
                update_post_meta( $id, $key, $value );
            }
        }
    }

    /**
     * Creates a table that stores all user views.
     *
     * @since   1.0.0-alpha
     */
    private static function add_views_tracking_table() {
        global $wpdb;
        $wpdb->query(
            "CREATE TABLE `wp_smart-sorting_views_table` (
                product_id INT NOT NULL,
                user_id INT NOT NULL,
                view_date DATE NOT NULL,
                view_num INT DEFAULT 0 NOT NULL,
                is_counted BIT DEFAULT 0 NOT NULL)"
        );
    }

    /**
     * Add necessary data to products, add additional tables in database
     * and add plugin options.
     *
     * @since    1.0.0-alpha
     */
	public static function activate() {
        self::add_spv_field();
        self::add_views_tracking_table();
        add_option( 'view_delay', 7 );
	}
}
