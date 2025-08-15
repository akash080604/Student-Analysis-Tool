<?php
// Require the database connection
require 'connect.php'; // Ensure the path is correct

// Get form data
$email = isset($_POST['email']) ? $_POST['email'] : '';
$newPassword = isset($_POST['new-password']) ? $_POST['new-password'] : '';
$confirmPassword = isset($_POST['confirm-password']) ? $_POST['confirm-password'] : '';

// Function to display an alert message and redirect to forget.html
function showAlertAndRedirect($message) {
    echo "<script>
            alert('$message');
            window.location.href = 'forget.html';
          </script>";
    exit();
}

try {
    // Check if passwords match
    if ($newPassword !== $confirmPassword) {
        showAlertAndRedirect("Passwords do not match!");
    }

    // Check if email exists in the database
    $stmt = $pdo->prepare("SELECT * FROM user WHERE email = ?");
    $stmt->execute([$email]);

    if ($stmt->rowCount() > 0) {
        // Email found, update the password (non-hashed)
        $updateSql = "UPDATE user SET password = ? WHERE email = ?";
        $updateStmt = $pdo->prepare($updateSql);

        if ($updateStmt->execute([$newPassword, $email])) {
            // Redirect to login.html after successful password update
            echo "<script>
                    alert('Password updated successfully!');
                    window.location.href = 'login.html';
                  </script>";
            exit();
        } else {
            showAlertAndRedirect("Error updating password.");
        }
    } else {
        showAlertAndRedirect("Email not found.");
    }
} catch (PDOException $e) {
    showAlertAndRedirect("Database error: " . $e->getMessage());
}

// Close the database connection
$pdo = null; // Optional, as PDO will automatically close the connection when the script ends
?>
