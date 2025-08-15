<?php
// Include the database connection file
include 'connect.php';

// Hardcoded user and password
$hardcoded_user = $_POST['username'];
$hardcoded_pass = $_POST['password'];

// Prepare the SQL statement to check the user
$sql = "SELECT * FROM admin WHERE user = :user AND password = :password";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(':user', $hardcoded_user);
$stmt->bindParam(':password', $hardcoded_pass);

// Execute the statement
$stmt->execute();

// Check if any row is returned
if ($stmt->rowCount() > 0) {
    // User found - redirect to control.php
    header("Location: control.php");
    exit(); // Always call exit after a redirect
} else {
    // User not found - redirect back with error message
    $error_message = "Error: Incorrect username or password.";
    header("Location: admin.html?error=" . urlencode($error_message));
    exit(); 
}

// Close the connection (optional, PDO closes automatically when script ends)
//$pdo = null;
?>