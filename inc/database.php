<?php
$hostname = "localhost:3306";
$username = "root";
$password = "rootpassword";
$dbname = "books" ;
$conndb = mysqli_connect($hostname, $username, $password, $dbname) or trigger_error(mysqli_error());

?>