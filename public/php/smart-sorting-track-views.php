<?php
$id = $_POST['product_id'];
include __DIR__ . "/../../../../../wp-load.php";
global $wpdb;
$wpdb->query(
    $wpdb->prepare(
        "UPDATE {$wpdb->postmeta} SET meta_value = meta_value + 1 WHERE post_id = %d AND meta_key='spv_views'",
        $id
    )
);
?>

