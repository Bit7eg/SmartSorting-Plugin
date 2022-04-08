<?php

/**
 * Fired during plugin activation
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    Plugin_Name
 * @subpackage Plugin_Name/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    SmartSorting
 * @subpackage SmartSorting/includes
 * @author     Your Name <email@example.com>
 */

class Smart_Sorting_Activator {

    /**
     *
     */

    private static function add_spv_field() {
        $posts_meta = array(
            'spv_views',
            'spv_sales',
            'spv',
        );

        $product_query = new WP_Query( array(
            'post_type' => 'product',
        ));
        while ( $product_query->have_posts() ) {
            $product_query->the_post();
            $id = $product_query->post->ID;
            foreach ( $posts_meta as $key ) {
                $value = get_post_meta( $id, $key, true );
                if( $value == '' ) {
                    $value = 0;
                }
                update_post_meta($id, $key, $value);
            }
        }
    }

    /**
     *
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
     * Short Description. (use period)
     * Long Description.
     *
     * @since    1.0.0
     */

	public static function activate() {
        self::add_spv_field();
        self::add_views_tracking_table();
        add_option( 'view_delay', 7 );
	}
}
