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

class smart_sorting_setting {
    public $setting_name = "";
    public $value = 0;
    public $slug = "";

    public function __construct($setting_name, $value, $slug) {
        $this->setting_name = $setting_name;
        $this->value = $value;
        $this->slug = $slug;
    }
}

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
        while ($product_query->have_posts()){
            $product_query->the_post();
            $id = $product_query->post->ID;
            foreach ($posts_meta as $key) {
                $value = get_post_meta($id, $key, true);
                if($value == '') {
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
            "CREATE TABLE `wp_smart-sorting_views_table` (product_id INT NOT NULL, user_id INT NOT NULL, view_date DATE NOT NULL, view_num INT DEFAULT 0 NOT NULL, PRIMARY KEY(product_id, user_id, view_date))"
        );
    }

    private static function add_plugin_settings_table() {
        global $wpdb;
        $wpdb->query(
            "CREATE TABLE `wp_smart-sorting_settings_table`
                            (setting_name varchar(100) not null primary key, 
                             setting_value int not null,
                             slug varchar(100) not null)"
        );
    }

    private static function add_plugin_settings() {
        $settings = array(
          new smart_sorting_setting('view_delay', 7, 'View delay')
        );
        foreach ($settings as $setting) {
            self::add_plugin_setting($setting->setting_name, $setting->value, $setting->slug);
        }
    }

    private static function add_plugin_setting($setting_name, $value, $slug) {
        global $wpdb;
        $wpdb->query(
            $wpdb->prepare(
                "INSERT INTO `wp_smart-sorting_settings_table` VALUES (%s, %d, %s)",
                $setting_name, $value, $slug
            )
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
        //self::add_plugin_settings_table();
        //self::add_plugin_settings();
	}
}
