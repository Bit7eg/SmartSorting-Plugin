<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    SmartSorting
 * @subpackage SmartSorting/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    SmartSorting
 * @subpackage SmartSorting/admin
 * @author     Your Name <email@example.com>
 */
class Smart_Sorting_Admin
{

    /**
     * The ID of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string $smart_sorting The ID of this plugin.
     */
    private $smart_sorting;

    /**
     * The version of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string $version The current version of this plugin.
     */
    private $version;

    /**
     * Initialize the class and set its properties.
     *
     * @param string $smart_sorting The name of this plugin.
     * @param string $version The version of this plugin.
     * @since    1.0.0
     */
    public function __construct($smart_sorting, $version)
    {

        $this->smart_sorting = $smart_sorting;
        $this->version = $version;

    }

    /**
     * Register the stylesheets for the admin area.
     *
     * @since    1.0.0
     */
    public function enqueue_styles($hook)
    {

        /**
         * This function is provided for demonstration purposes only.
         *
         * An instance of this class should be passed to the run() function
         * defined in Plugin_Name_Loader as all of the hooks are defined
         * in that particular class.
         *
         * The Plugin_Name_Loader will then create the relationship
         * between the defined hooks and the functions defined in this
         * class.
         */
        if ($hook != 'toplevel_page_smartsoring-config') {
            return;
        }
        wp_enqueue_style($this->smart_sorting . '_admin_styles', plugin_dir_url(__FILE__) . 'css/smart-sorting-admin.css', array(), false, 'all');

    }

    /**
     * Register the JavaScript for the admin area.
     *
     * @since    1.0.0
     */
    public function enqueue_scripts($hook)
    {

        /**
         * This function is provided for demonstration purposes only.
         *
         * An instance of this class should be passed to the run() function
         * defined in Plugin_Name_Loader as all of the hooks are defined
         * in that particular class.
         *
         * The Plugin_Name_Loader will then create the relationship
         * between the defined hooks and the functions defined in this
         * class.
         */
        if ($hook != 'toplevel_page_smartsoring-config') {
            return;
        }
        wp_enqueue_script($this->smart_sorting . '_admin_scripts', plugin_dir_url(__FILE__) . 'js/smart-sorting-admin.js', array('jquery'), false, false);

    }

    public function create_admin_menu()
    {
        add_options_page(
            __('Smart-sorting settings', 'textdomain'),
            __('Smart-sorting settings', 'textdomain'),
            'manage_options',
            'smart-sorting_settings',
            array($this, 'load_menu_content')
        );
    }

    public function add_spv_metadata($product_id)
    {
        $spv_attributes = array(
            'spv_views',
            'spv_sales',
            'spv',
        );
        foreach ($spv_attributes as $key) {
            $value = get_post_meta($product_id, $key, true);
            if ($value == '') {
                $value = 0;
            }
            update_post_meta($product_id, $key, $value);
        }
    }

    public function load_menu_content()
    {
        include plugin_dir_path(__FILE__) . 'partials/smart-sorting-admin-display.php';
        show_smart_sorting_options();
    }

    public function smart_sorting_settings_fields()
    {
        register_setting(
            'smart-sorting_settings',
            'ss_views_delay',
            'absint'
        );

        add_settings_section(
            'section',
            '',
            '',
            'smart-sorting_settings'
        );

        add_settings_field(
            'ss_views_delay',
            'Задержка просмотров',
            array($this, 'display_views_delay_field'),
            'smart-sorting_settings',
            'section',
            array(
                'name' => 'ss_views_delay'
            )
        );
    }

    public function display_views_delay_field($args)
    {
        $option = get_option($args['name']);

        printf(
            '<input type="number" min="1" id="%s" name="%s" value="%d" />',
            esc_attr($args['name']),
            esc_attr($args['name']),
            absint($option)
        );
    }
}
