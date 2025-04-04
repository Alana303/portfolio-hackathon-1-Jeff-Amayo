<?php
$host = "localhost";  // Change if your database is hosted elsewhere
$user = "root";       // Change if you have a different database username
$password = "";       // Change if you have set a database password
$dbname = "portfolio_auth";

// Create connection
$conn = new mysqli($host, $user, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

?>
