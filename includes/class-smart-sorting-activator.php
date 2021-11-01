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
        global $wp_query;
        if($wp_query->have_posts()){
            global $post;
            while ($wp_query->have_posts()){
                $wp_query->the_post();
                $product = new WC_Product($post->ID);
                $val = rand(1, 100);
                $product->set_attributes(array(
                    'id' => 0,
                    'name' => 'spv',
                    'options' => $val,
                    'position' => $val,
                    'visible' => true,
                    'variation' => true
                ));
                $product->save();
            }

        }

	}

}
