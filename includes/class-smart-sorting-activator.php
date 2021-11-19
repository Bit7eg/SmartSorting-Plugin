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

    /**
     *
     * @param string name
     *
     * @return WC_Product_Attribute/bool
     *
     */

    static function make_attribute($name){
        global $wpdb;

        $insert = $wpdb->insert(
            $wpdb->prefix . 'woocommerce_attribute_taxonomies',
            array(
                'attribute_label' => $name,
                'attribute_name' => $name,
                'attribute_type' => 'select',
                'attribute_orderby' => 'menu_order',
                'attribute_public' => 1
            ),
            array( '%s', '%s', '%s', '%s', '%d')
        );

        delete_transient('wc_attribute_taxonomies');
    }

    static function add_spv_attribute($name) {
        global $wc_product_attributes;

        if (!isset($wc_product_attributes[$name])) {
            self::make_attribute($name);
        }

        $attr = new WC_Product_Attribute();
        $attr->set_id(1);
        $attr->set_name($name);
        $attr->set_visible(true);
        $attr->set_variation(false);
        $attr->set_value(0);
        return $attr;
    }

    public static function add_def_attributes($product, $attributes) {
        $spv_attributes = array(
            'spv_views',
            'spv_sales',
            'spv',
        );

        foreach ($spv_attributes as $key) {
            if (!isset($attributes[$key])){
                $attr = self::add_spv_attribute($key);
                if ($attr) {
                    $attributes[$key] = $attr;
                }
            }
        }
        $product->set_attributes($attributes);
        $product->save();
    }

	public static function activate() {
        $product_query = new WP_Query( array(
           'post_type' => 'product',
        ));
        if($product_query->have_posts()){
            while ($product_query->have_posts()){
                $post = $product_query->the_post();
                $product = WC_Product($post->ID);
                $attributes = $product->get_attributes();
                self::add_def_attributes($product, $attributes);
            }
        }
	}
}
