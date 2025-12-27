<?php

require 'config.php';

$redirect = './index.php';
$role = 'admin';
$database->checkUser($redirect, $role);
$database->logout($redirect, $role);