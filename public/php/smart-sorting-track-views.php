<?php
$pid = $_POST['product_id'];
include __DIR__ . "/../../../../../wp-load.php";
global $wpdb;
if (is_user_logged_in()) {
    $uid=get_current_user_id();
}
else {
    $uid=-1;
}
$str = $wpdb->query(
    $wpdb->prepare(
        "UPDATE `wp_smart-sorting_views_table` SET view_num = view_num + 1
                WHERE product_id = %d AND user_id = %d AND view_date = CURRENT_DATE",
        $pid, $uid
    )
);
if (! $str) {
    $wpdb->query(
        $wpdb->prepare(
            "INSERT INTO `wp_smart-sorting_views_table` VALUES (%d, %d, CURRENT_DATE, 1)",
            $pid, $uid
        )
    );
}
?>

