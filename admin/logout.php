<?php

require 'config.php';

$redirect = './index.php';
$conn->checkUser($redirect);
$conn->logout($redirect);