<?php

require '../config.php';


$id = htmlspecialchars($_GET['id']);
$table = 'product_tbl';
$where = "id =  $id";
$redirect = 'index.php';

$database->delete($table, $where, $redirect);
