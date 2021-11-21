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
     * Short Description. (use period)
     *
     * Long Description.
     *
     * @since    1.0.0
     */

	public static function activate() {
        $product_query = new WP_Query( array(
            'post_type' => 'product',
        ));
        while ($product_query->have_posts()){
            $product_query->the_post();
            self::add_spv_metadata($product_query->post->ID);
        }
	}

    public static function add_spv_metadata($product_id){
        $spv_attributes = array(
            'spv_views',
            'spv_sales',
            'spv',
        );
        foreach ($spv_attributes as $key) {
            update_post_meta($product_id, $key, 0);
        }
    }
}
