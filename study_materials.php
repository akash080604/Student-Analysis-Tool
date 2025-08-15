<?php
// Include database connection
include 'connect.php'; // This establishes $pdo

// Start the session to access session variables
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_email'])) {
    // If not logged in, redirect to login page
    header("Location: index.html");
    exit();
}

// Store the user's email in a variable
$userEmail = $_SESSION['user_email'];

// Fetch the student's details from the student table
try {
    $stmt = $pdo->prepare("SELECT email, name, ID, dob, gender, class FROM student WHERE email = :email");
    $stmt->execute([':email' => $userEmail]);
    $student = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($student) {
        // Assign student details to variables
        $studentClass = $student['class'];
    } else {
        echo "<p class='message'>Student not found.</p>";
        exit();
    }
} catch (PDOException $e) {
    echo "<p class='message'>Error fetching student details: " . htmlspecialchars($e->getMessage()) . "</p>";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Study Materials</title>
    <style>
        body {
            background-image: url('main.jpg');
            background-size: cover;
            background-position: center;
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }
        .container {
            width: 80%;
            margin: 50px auto;
            padding: 20px;
            background: #fff;
            color: #000;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
        }
        h1, h2 {
            text-align: center;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        th, td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background: #f5f5f5;
            color: #333;
        }
        tr:hover {
            background: #f9f9f9;
        }
        a.download-btn {
            color: #fff;
            text-decoration: none;
            background: #28a745;
            padding: 5px 10px;
            border-radius: 5px;
        }
        a.download-btn:hover {
            background: #218838;
        }
        .back-btn {
            position: absolute;
            top: 20px;
            left: 20px;
            padding: 10px 20px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .back-btn:hover {
            background-color: #0056b3;
        }
        .message {
            text-align: center;
            margin: 10px 0;
            color: #ff0000;
        }
    </style>
</head>
<body>
    <button class="back-btn" onclick="window.location.href='index.php'">Back</button>
    <div class="container">
        <h1>Available Study Materials</h1>

        <?php
        try {
            // Fetch materials grouped by subject
            $sql = "SELECT subject, id, class, file_name, file_path, uploaded_at 
                    FROM study_materials 
                    WHERE class = :class 
                    ORDER BY subject, uploaded_at DESC";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([':class' => $studentClass]);
            $materials = $stmt->fetchAll(PDO::FETCH_GROUP|PDO::FETCH_ASSOC);

            if ($materials) {
                echo "<h2>Study Materials for Class: $studentClass</h2>";
                foreach ($materials as $subject => $rows) {
                    echo "<h2>Subject: " . htmlspecialchars($subject) . "</h2>";
                    echo "<table>";
                    echo "<thead>";
                    echo "<tr>";
                    echo "<th>File Name</th>";
                    echo "<th>Uploaded At</th>";
                    echo "<th>Action</th>";
                    echo "</tr>";
                    echo "</thead>";
                    echo "<tbody>";
                    foreach ($rows as $row) {
                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($row['file_name']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['uploaded_at']) . "</td>";
                        echo "<td><a class='download-btn' href='" . htmlspecialchars($row['file_path']) . "' download>Download</a></td>";
                        echo "</tr>";
                    }
                    echo "</tbody>";
                    echo "</table>";
                }
            } else {
                echo "<p class='message'>No materials found for Class $studentClass.</p>";
            }
        } catch (PDOException $e) {
            echo "<p class='message'>Error fetching study materials: " . htmlspecialchars($e->getMessage()) . "</p>";
        }
        ?>
    </div>
</body>
</html>
