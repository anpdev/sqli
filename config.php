<?php
define("hostname","localhost");
define("username","phpmyadmin");
define("password", "am204");
define("database","app_admin");
session_start();

//error_reporting(E_ALL);

include 'functions.php';
// Create connection


$conn = new DB(hostname,username,password,database);


?>