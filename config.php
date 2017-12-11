<?php
define('DB_SERVER', '');
define('DB_USER', '');
define('DB_PASSWORD', '');
define('DB_NAME', '');

session_start();
require_once 'dbConnect.php';
$mini = false;
$display_navigation_bar = true;
error_reporting(0);
