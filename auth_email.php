<?php
// Database configuration
$servername = "127.0.0.1";
$username = "root";
$password = ""; // Replace with your database password
$dbname = "analysis"; // Replace with your database name

// Create a connection
$conn = new mysqli($host, $username, $password, $database);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Prepare and execute the SQL query
$sql = "SELECT email FROM user"; // Query to get email from user table
$result = $conn->query($sql);

// Check if there are results and fetch data
if ($result->num_rows > 0) {
    // Output data for each row
    while ($row = $result->fetch_assoc()) {
        echo "Email: " . $row["email"] . "<br>"; // Display the email
    }
} else {
    echo "No results found.";
}

// Close the connection
$conn->close();
?>
