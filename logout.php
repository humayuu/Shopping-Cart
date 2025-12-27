<?php
session_start();
require './admin/config.php';

$redirect = './index.php';

$database->logout($redirect);