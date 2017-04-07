<?php
define("hostname","localhost");
define("username","username");
define("password", "password");
define("database","dbname");
session_start();

//error_reporting(E_ALL);

include 'functions.php';
// Create connection


$mysqli = new DB(hostname,username,password,database);


?>
