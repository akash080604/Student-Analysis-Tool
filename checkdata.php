<?php
// Database connection parameters
$servername = "127.0.0.1"; // Change if your database server is on a different host
$username = "root"; // Replace with your database username
$password = ""; // Replace with your database password (leave empty for default XAMPP)
$dbname = "analysis"; // Replace with your database name

try {
    // Create a PDO instance
    $pdo = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Retrieve email and password from POST request
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    // Prepare SQL statement to prevent SQL injection
    $stmt = $pdo->prepare("SELECT password, auth FROM user WHERE email = :email");
    $stmt->bindParam(':email', $email);
    
    // Execute the statement
    $stmt->execute();
    
    // Fetch the result
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($result && $password === $result['password']) {
        // Authentication successful, redirect to a.html
        header("Location: a.html");
        exit(); // Ensure no further code is executed after redirection
    } else {
        // Authentication failed
        echo "<script>alert('Invalid email or password.'); window.history.back();</script>";
        exit();
    }
} catch (PDOException $e) {
    // Handle database connection errors
    echo "Connection failed: " . $e->getMessage();
}
?>
