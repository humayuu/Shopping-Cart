<?php
require '../config.php';

$redirectToLogin = '../index.php';
$role = 'admin';
$database->checkUser($redirectToLogin, $role);
