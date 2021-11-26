<?php

$id = $_POST['product_id'];
$meta = 'spv_views';
$value = get_post_meta($id, $meta, true);
$value++;
update_post_meta($id, $meta, $value);

