<?php
session_start(); // Start the session to access session variables

// Check if the user is logged in
if (!isset($_SESSION['user_email'])) {
    // If not logged in, redirect to login page
    header("Location: index.php");
    exit();
}

// Include the database connection
include 'connect.php';

// Initialize variables for storing student details
$studentDetails = [];
$message = '';

// Fetch user email from session
$userEmail = htmlspecialchars($_SESSION['user_email']);

// Debugging output
echo "<!-- Debugging output: User email is: $userEmail -->";

// Fetch student details from the database using the user's email
try {
    $stmt = $pdo->prepare("SELECT email, name, ID, dob, gender,class FROM student WHERE email = :email");
    $stmt->execute([':email' => $userEmail]);

    // Fetch the details
    $studentDetails = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$studentDetails) {
        $message = "No student found with the email: " . htmlspecialchars($userEmail);
    }
} catch (PDOException $e) {
    $message = "Error: " . htmlspecialchars($e->getMessage());
}

// Close the database connection
$pdo = null;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-image: url('main.jpg'); /* Set main.jpg as background */
            background-size: cover;
            margin: 0;
            padding: 20px;
        }
        header {
            color:white;
            text-align: center;
            margin-bottom: 20px;
        }
        .content-box {
            width: 475px; /* Adjusted width */
            padding: 20px;
            background-color: #fff;
            border: 1px solid #ccc;
            border-radius: 5px;
            backdrop-filter: blur(10px);
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            margin: auto;
        }
        .message {
            color: #d32f2f; /* Red color for error messages */
            margin-bottom: 20px;
        }
        .detail {
            margin: 10px 0;
            font-size: 18px;
        }
        .back-btn {
            position: absolute;
            top: 20px;
            left: 20px;
            padding: 10px 20px;
            background-color: #ff4d4d;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .back-btn:hover {
            background-color: #e60000;
        }
    </style>
</head>
<body>
<button class="back-btn" onclick="window.location.href='index.php'">Back</button>
    <header>
        <h1>Profile</h1>
        <p>Email: <?php echo $userEmail; ?></p> <!-- Display the user email -->
    </header>
    
    <main>
        <div class="content-box"> <!-- Content box for the student details -->
            <?php if ($message): ?>
                <div class="message"><?php echo htmlspecialchars($message); ?></div>
            <?php else: ?>
                <div class="detail"><strong>Name:</strong> <?php echo htmlspecialchars($studentDetails['name']); ?></div>
                <div class="detail"><strong>ID:</strong> <?php echo htmlspecialchars($studentDetails['ID']); ?></div>
                <div class="detail"><strong>Date of Birth:</strong> <?php echo htmlspecialchars($studentDetails['dob']); ?></div>
                <div class="detail"><strong>Gender:</strong> <?php echo htmlspecialchars($studentDetails['gender']); ?></div>
                <div class="detail"><strong>Class:</strong> <?php echo htmlspecialchars($studentDetails['class']); ?></div>
            <?php endif; ?>
        </div> <!-- End of content box -->
    </main>
</body>
</html>
