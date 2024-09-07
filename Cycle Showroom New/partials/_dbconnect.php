<?php
$server = "localhost";
$username = "root";
$password = "";
$database = "cycle_showroom_new";

$conn = mysqli_connect($server, $username, $password, $database);
if(!$conn){
    die("Error: " . mysqli_connect_error());
}
?>
