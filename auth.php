<?php
// Include the database connection
require 'connect.php';

try {
    // Get email and auth from the URL
    $email = isset($_GET['email']) ? $_GET['email'] : '';
    $auth = isset($_GET['auth']) && $_GET['auth'] === '1' ? 1 : 0; // Check if the auth value is '1' (on) or '0' (off)

    // Check if the email exists in the database
    $checkStmt = $pdo->prepare("SELECT COUNT(*) FROM user WHERE email = :email");
    $checkStmt->execute(['email' => $email]);
    $emailExists = $checkStmt->fetchColumn() > 0;

    if ($emailExists) {
        // If the email exists, update the user's authentication setting
        $stmt = $pdo->prepare("UPDATE user SET auth = :auth WHERE email = :email");
        $stmt->execute(['auth' => $auth, 'email' => $email]);

        // Check if the update was successful
        if ($stmt->rowCount() > 0) {
            echo "Authentication setting updated successfully.";
        } else {
            echo "Failed to update authentication setting.";
        }

        // Redirect to login.html
        header("Location: login.html");
        exit();
    } else {
        // If the email does not exist, show an appropriate message
        echo "Email does not exist in the database.";
    }
} catch (PDOException $e) {
    echo "Database error: " . $e->getMessage();
}

// Close the connection
$pdo = null;
?>
