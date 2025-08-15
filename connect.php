<?php
// Database connection details
$servername = "127.0.0.1";
$username = "root";
$password = ""; // Replace with your database password
$dbname = "analysis"; // Replace with your database name

try {
    // Establish the connection
    $pdo = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    // Handle connection failure
    die("Connection failed: " . $e->getMessage());
}
?>
