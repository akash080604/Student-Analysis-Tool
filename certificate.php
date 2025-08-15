<?php
// Start the session to get student details
session_start();

// Check if the student is logged in
if (!isset($_SESSION['user_email'])) {
    // Redirect to login page if not logged in
    header("Location: login.php");
    exit();
}

// Retrieve student email from session
$student_email = $_SESSION['user_email'];

// Include the database connection file
require_once('connect.php');

// Fetch student details from the database based on the email
$query = "SELECT name, id FROM student WHERE email = :email";
$stmt = $pdo->prepare($query);
$stmt->bindParam(':email', $student_email, PDO::PARAM_STR);
$stmt->execute();

// Fetch the result
$student = $stmt->fetch();

// Check if student details were found
if (!$student) {
    die("Student details not found.");
}

$student_name = $student['name'];
$student_id = $student['id'];

// Get the current year
$current_year = date("Y");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Certificate</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            text-align: center;
            margin: 0;
            padding: 0;
        }

        .certificate-container {
            position: relative;
            width: 800px;
            height: 600px;
            border: 2px solid #000;
            margin: 50px auto;
            padding: 20px;
            background-color: #f9f9f9;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
        }

        .logo {
            position: absolute;
            top: 20px;
            left: 20px;
            width: 100px;
            height: auto;
        }

        .stamp {
            position: absolute;
            bottom: 20px;
            right: 20px;
            width: 150px; /* Increased size for the stamp */
            height: auto;
        }

        h1 {
            font-size: 36px;
            margin-top: 100px;
        }

        .certificate-content {
            margin-top: 50px;
        }

        .student-name {
            font-size: 28px;
            font-weight: bold;
        }

        .student-id {
            font-size: 24px;
            margin-top: 10px;
        }

        .footer {
            margin-top: 30px;
            font-size: 18px;
            color: #666;
        }

        .print-btn {
            margin-top: 30px;
            padding: 10px 20px;
            font-size: 18px;
            background-color: #4CAF50;
            color: white;
            border: none;
            cursor: pointer;
        }

        .print-btn:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>

<div class="certificate-container">
    <!-- Institute Logo -->
    <img src="assessment.png" alt="Institute Logo" class="logo">

    <!-- Certificate Content -->
    <h1>Certificate of Achievement</h1>
    <div class="certificate-content">
        <p>This is to certify that</p>
        <p class="student-name"><?php echo $student_name; ?></p>
        <p class="student-id">Student ID: <?php echo $student_id; ?></p>
        <p>has successfully completed the required courses and is hereby awarded this certificate.</p>
    </div>

    <!-- Institute Stamp -->
    <img src="stamp.png" alt="Institute Stamp" class="stamp">

    <!-- Footer -->
    <div class="footer">
        <p>Batch - <?php echo $current_year-1; ?></p>
    </div>

    <!-- Print Button -->
    <button class="print-btn" onclick="window.print();">Print Certificate</button>
</div>

</body>
</html>
