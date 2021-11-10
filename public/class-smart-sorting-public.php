<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    SmartSorting
 * @subpackage SmartSorting/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    SmartSorting
 * @subpackage SmartSorting/public
 * @author     Your Name <email@example.com>
 */
class Smart_Sorting_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $smart_sorting    The ID of this plugin.
	 */
	private $smart_sorting;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $smart_sorting       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct($smart_sorting, $version ) {

		$this->smart_sorting = $smart_sorting;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Smart_Sorting_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Smart_Sorting_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->smart_sorting, plugin_dir_url( __FILE__ ) . 'css/smart-sorting-public.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts($hook) {

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

		wp_enqueue_script( $this->smart_sorting, plugin_dir_url( __FILE__ ) . 'js/smart-sorting-public.js', array( 'jquery' ), $this->version, false );

	}

    public function smartsorting_catalog_orderby($sortby) {
        $sortby['smart-sorting'] = 'Improved sorting';
        return $sortby;
    }

    public function get_smartsorting_ordering_args($args){
        $orderby_value = isset( $_GET['orderby'] ) ? wc_clean( $_GET['orderby'] ) : apply_filters( 'woocommerce_default_catalog_orderby', get_option( 'woocommerce_default_catalog_orderby' ) );
        if ( 'smart-sorting' == $orderby_value ) {
            $args['orderby'] = 'meta_value_num';
            $args['order'] = 'DESC';
            $args['meta_key'] = 'spv';
        }
        return $args;
    }

    public function track_total_sales($order_id){
        $order = wc_get_order( $order_id );
        if ( count( $order->get_items() ) > 0 ) {
            foreach ( $order->get_items() as $item ) {
                $product_id = $item->get_product_id();

                if ( $product_id ) {
                    $item->get_quantity(); //Здесь надо отправить собранные данные по покупкам и просмотрам на сервак
                }
            }
        }
    }
}
