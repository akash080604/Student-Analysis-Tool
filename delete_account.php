<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_email'])) {
    header("Location: login.html");
    exit();
}

// Database connection
require 'connect.php';

// Get the user's email from the session
$userEmail = $_SESSION['user_email'];

try {
    // Prepare and execute the deletion query
    $stmt = $pdo->prepare("DELETE FROM user WHERE email = :email");
    $stmt->bindParam(':email', $userEmail, PDO::PARAM_STR);
    
    if ($stmt->execute()) {
        // Successfully deleted, unset session and redirect to login with message
        session_destroy();
        echo "<script>
                alert('Account deleted successfully');
                window.location.href = 'login.html';
              </script>";
    } else {
        // Failed to delete, redirect back with an error message
        echo "<script>
                alert('Failed to delete account. Please try again.');
                window.location.href = 'profile.php';
              </script>";
    }
} catch (PDOException $e) {
    // Handle any errors in the query
    echo "<script>
            alert('Error: " . $e->getMessage() . "');
            window.location.href = 'profile.php';
          </script>";
}
?>
