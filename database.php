<?php
$hostName   = "localhost";
$dbUser     = "root";
$dbPassword = "";
$dbName     = "social";
$port = 3307;
$conn = mysqli_connect($hostName, $dbUser, $dbPassword, $dbName, $port);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
} else {
    echo "Database connected successfully!";
}
?>
