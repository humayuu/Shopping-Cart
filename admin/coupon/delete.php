<?php

require '../guard.php';



$id = htmlspecialchars($_GET['id']);
$table = 'coupon_tbl';
$rows = '*';
$join = null;
$order = null;
$limit  = null;
$where = "id =  $id";
$redirect = 'index.php';


$database->delete($table, $where, $redirect);
