<?php

require './guard.php';



$id = htmlspecialchars($_GET['id']);
$table = 'product_tbl';
$rows = '*';
$join = null;
$order = null;
$limit  = null;
$where = "id =  $id";
$redirect = 'index.php';

// Fetch Record for specific product
$product = $database->select($table, $rows, $join, $where, $order, $limit);
$image = $product['product_image'];

if (!empty($image) || file_exists($image)) {
    unlink($image);
}

$database->delete($table, $where, $redirect);
