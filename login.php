<?php
session_start(); // Start the session
include 'connect.php'; // Ensure this file has correct database credentials
include 'store_captcha.php'; // Include the captcha storage logic

// Function to show alert message and redirect to login.html
function showAlertAndRedirect($message) {
    echo "<script>
            alert('$message');
            window.location.href = 'login.html';
          </script>";
    exit();
}

try {
    $pdo = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $captcha = $_POST['captcha'];

    $sql = "SELECT password, auth FROM user WHERE email = :email";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':email', $email);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $dbPassword = $row['password'];
        $auth = $row['auth'];

        // Compare plaintext password directly
        if ($_POST['password'] === $dbPassword) {
            // Validate captcha
            if (isset($_SESSION['captcha']) && $_SESSION['captcha'] === $captcha) {
                $_SESSION['user_email'] = $email;
                session_regenerate_id(true); // Regenerate session ID

                if ($auth == 1) {
                    header("Location: save.php");
                    exit();
                } else {
                    header("Location: index.php");
                    exit();
                }
            } else {
                showAlertAndRedirect("Captcha does not match.");
            }
        } else {
            showAlertAndRedirect("Invalid password.");
        }
    } else {
        showAlertAndRedirect("Invalid email.");
    }
} catch (PDOException $e) {
    showAlertAndRedirect("Connection failed: " . $e->getMessage());
}
?>
