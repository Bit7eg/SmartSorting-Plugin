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
	public function __construct( $smart_sorting, $version ) {

		$this->smart_sorting = $smart_sorting;
		$this->version       = $version;

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

		wp_enqueue_style(
            $this->smart_sorting,
            plugin_dir_url( __FILE__ ) . 'css/smart-sorting-public.css',
            array(),
            $this->version,
            'all'
        );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

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

		wp_enqueue_script(
            $this->smart_sorting,
            plugin_dir_url( __FILE__ ) . 'js/smart-sorting-public.js',
            array( 'jquery' ),
            $this->version,
            false
        );

	}

    public function get_smartsorting_ordering_args( $args ) {
        if ( isset( $_GET['orderby'] ) ) {
            if ( 'menu_order' == wc_clean( $_GET['orderby'] ) ) {
                $args['orderby']  = 'meta_value_num';
                $args['order']    = 'DESC';
                $args['meta_key'] = 'spv';
            }
        } else {
            $args['orderby']  = 'meta_value_num';
            $args['order']    = 'DESC';
            $args['meta_key'] = 'spv';
        }
        return $args;
    }

    public static function update_spv_value() {
        $product_query = new WP_Query( array(
            'post_type' => 'product',
        ) );
        while ( $product_query->have_posts() ){
            $product_query->the_post();
            $id    = $product_query->post->ID;
            $views = get_post_meta( $id, 'spv_views', true );
            $sales = get_post_meta( $id, 'spv_sales', true );
            $spv_value = 0;
            if ( 0 != $views ) {
                $spv_value = (float) $sales / (float) $views;
            }
            update_post_meta( $id, 'spv', $spv_value );
        }
    }

    public function track_total_sales( $order_id ){
        global $wpdb;
        $order = wc_get_order( $order_id );
        $view_delay = get_option( 'ss_views_delay' );
        if ( count( $order->get_items() ) > 0 ) {
            foreach ( $order->get_items() as $item ) {
                $cart_product_id = $item->get_product_id();
                if ( $cart_product_id ) {
                    $wpdb->query(
                        $wpdb->prepare(
                            "UPDATE %s
                                SET meta_value = meta_value + %f
                                WHERE post_id = %d
                                  AND meta_key = 'spv_sales'",
                            $wpdb->postmeta,
                            $item->get_quantity(),
                            $cart_product_id
                        )
                    );
                    if ( is_user_logged_in() ) {
                        $user_id = get_current_user_id();
                    } else {
                        $user_id = -1;
                    }
                    if ( 0 != $view_delay ) {
                        $view_nums = $wpdb->get_results(
                            $wpdb->prepare(
                                "SELECT product_id, view_num
                                    FROM `wp_smart-sorting_views_table`
                                    WHERE view_date > DATE_SUB(CURRENT_DATE, INTERVAL %d DAY)
                                      AND user_id = %d
                                      AND is_counted = 0",
                                $view_delay,
                                $user_id
                            )
                        );
                    } else {
                        $view_nums = $wpdb->get_results(
                            $wpdb->prepare(
                                "SELECT product_id, view_num
                                    FROM `wp_smart-sorting_views_table`
                                    WHERE user_id = %d
                                      AND is_counted = 0",
                                $user_id
                            )
                        );
                    }
                    $sale_terms = get_the_terms(
                        $cart_product_id,
                        'product_cat'
                    );
                    $views = array();
                    foreach ( $view_nums as $view_num ) {
                        $views[ $view_num->product_id ] = 0;
                        $view_terms = get_the_terms(
                            $view_num->product_id,
                            'product_cat'
                        );
                        foreach ( $view_terms as $view_term ) {
                            if ( in_array( $view_term, $sale_terms ) ) {
                                $views[ $view_num->product_id ] +=
                                    $view_num->view_num;
                                break;
                            }
                        }
                    }
                    foreach ( $views as $viewed_product_id => $viewed_product_views ) {
                        if ( $viewed_product_views > 0 ) {
                            $wpdb->query(
                                $wpdb->prepare(
                                    "UPDATE %s
                                        SET meta_value = meta_value + %d
                                        WHERE post_id = %d
                                          AND meta_key = 'spv_views'",
                                    $wpdb->postmeta,
                                    $viewed_product_views,
                                    $viewed_product_id
                                )
                            );
                            if ( 0 != $view_delay ) {
                                $wpdb->query(
                                    $wpdb->prepare(
                                        "UPDATE `wp_smart-sorting_views_table`
                                            SET is_counted = 1
                                            WHERE view_date > DATE_SUB(CURRENT_DATE, INTERVAL %d DAY)
                                              AND user_id = %d
                                              AND product_id = %d
                                              AND is_counted = 0",
                                        $view_delay,
                                        $user_id,
                                        $viewed_product_id
                                    )
                                );
                            } else {
                                $wpdb->query(
                                    $wpdb->prepare(
                                        "UPDATE `wp_smart-sorting_views_table`
                                            SET is_counted = 1
                                            WHERE user_id = %d
                                              AND product_id = %d
                                              AND is_counted = 0",
                                        $user_id,
                                        $viewed_product_id
                                    )
                                );
                            }
                        }
                    }
                }
            }
        }
    }
}
