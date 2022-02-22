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
$wpdb->query(
    $wpdb->prepare(
        "INSERT INTO `wp_smart-sorting_views_table` (product_id, user_id, view_date, view_time) VALUES (%d, %d, CURRENT_DATE, CURRENT_TIME)",
        $pid,
        $uid
    )
);
?>

