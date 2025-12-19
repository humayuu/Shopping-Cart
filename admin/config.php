<?php

require 'Auth.php';
require 'Database.php';

$conn = new Auth('localhost', 'shopping_cart_db', 'root', '');

$database = new Database('localhost', 'shopping_cart_db', 'root', '');
