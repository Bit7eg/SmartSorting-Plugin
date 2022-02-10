<?php
$id = $_POST['product_id'];
include __DIR__ . "/../../../../../wp-load.php";
$meta = 'spv_views';
$value = get_post_meta($id, $meta, true);
$value = $value + 1;
update_post_meta($id, $meta, $value);
?>

