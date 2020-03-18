<?php
$host = "127.0.0.1";
$port = 3308;
$username = "root";
$password = "";
$database = "hotel";
/*$host = "localhost";
$port = 3306;
$username = "u_99_user";
$password = "iragliva10";
$database = "db_99_db";*/

$db = new PDO("mysql:host=$host;port=$port", $username, $password);
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$db->exec("use `$database`");
?>