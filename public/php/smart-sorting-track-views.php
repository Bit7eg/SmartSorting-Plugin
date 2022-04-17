<?php

/**
 * Send the current view to the database.
 *
 * This script is used to save data about each view and
 * write this data to the database.
 *
 * @license     https://www.gnu.org/licenses/lgpl-3.0.txt  LGPL License 3.0
 * @since       1.0.0-alpha
 *
 * @package     SmartSorting
 * @subpackage  SmartSorting/public/partials
 */

$product_id = $_POST['productId'];
include __DIR__ . "/../../../../../wp-load.php";
global $wpdb;
if ( is_user_logged_in() ) {
    $user_id = get_current_user_id();
} else {
    $user_id = -1;
}
$wpdb->query(
    $wpdb->prepare(
        "UPDATE wp_smart_sorting_views_table
            SET view_num = view_num + 1
            WHERE product_id = %d
              AND user_id = %d
              AND view_date = CURRENT_DATE
              AND is_counted = 0",
        $product_id,
        $user_id,
    )
);
if ( 0 == $wpdb->rows_affected ) {
    $wpdb->query(
        $wpdb->prepare(
            "INSERT INTO wp_smart_sorting_views_table
                (product_id, user_id, view_date, view_num)
                VALUES (%d, %d, CURRENT_DATE, 1)",
            $product_id,
            $user_id,
        )
    );
}
?>