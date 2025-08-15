<?php
// Include the database connection
require 'connect.php';

// Example code to insert a user with a plain text password into the database
try {
    // Example user data
    $email = $_POST['email'];
    $password = $_POST['password']; // This is the plain text password

    // Insert into the database
    $stmt = $pdo->prepare("INSERT INTO user (email, password, auth) VALUES (:email, :password, :auth)");
    $auth = "some_auth_value"; // Replace with your actual auth value
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':password', $password); // Use plain text password
    $stmt->bindParam(':auth', $auth);
    
    $stmt->execute();
    echo "User inserted successfully.";
    header("Location: auth.html?email=" . urlencode($email));
    exit();
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>
