<?php
$host = "localhost";
$user = "root";
$pass = "";
$db = "traffic_system";

$conn = new mysqli($host, $user, $pass, $db);

if($conn->connect_error){
    die("Database Connection Failed!!");
}

?>