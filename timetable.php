<?php
include 'connect.php';  // Ensure this file contains the code to connect to your database

session_start(); // Start session to access session variables

// Check if the user is logged in and has an email in the session
if (!isset($_SESSION['user_email'])) {
    die('Error: User is not logged in.');
}

$userEmail = $_SESSION['user_email']; // Get user email from session

// Query to get the student ID based on the user's email
$studentQuery = "SELECT ID as student_id FROM student WHERE email = :email"; // Assuming ID is the primary key
$studentStmt = $pdo->prepare($studentQuery);
$studentStmt->execute([':email' => $userEmail]);

$student = $studentStmt->fetch(PDO::FETCH_ASSOC);
if (!$student) {
    die('Error: Student ID not found for this user.');
}

$student_id = $student['student_id'];

// Prepare and execute the query to fetch the timetable for the student
$query = "SELECT timetable.day, timetable.subject, timetable.start_time, timetable.end_time
          FROM timetable
          JOIN student ON student.ID = timetable.Student_id
          WHERE timetable.Student_id = :student_id
          ORDER BY FIELD(timetable.day, 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday')";
$stmt = $pdo->prepare($query);
$stmt->execute([':student_id' => $student_id]);

// Fetch all results as an associative array
$timetable = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Organize timetable data by day and time
$timetable_data = [];
foreach ($timetable as $entry) {
    $day = $entry['day'];
    // Ensure time format is consistent (convert to 24-hour format)
    $start_time = date('H:i', strtotime($entry['start_time'])); // Convert to 24-hour format
    $timetable_data[$start_time][$day] = [
        'subject' => $entry['subject']
    ];
}

// Define the days and sample times (adjust based on your actual timetable)
$days_of_week = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'];
$time_slots = ['09:00', '10:00', '11:00', '12:00', '13:00', '14:00']; // 24-hour format
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Timetable</title>
    <style>
        body {
            background-image: url('main.jpg');
            background-size: cover;
            font-family: Arial, sans-serif;
            background-color: #f9f9f9;
            color: #333;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
        }
        .container {
            max-width: 900px;
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
        }
        
        h1 {
            text-align: center;
            color: #444;
        }

        .student-info {
            text-align: center;
            font-size: 1.1em;
            color: #555;
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin: 0 auto;
            text-align: center;
        }
        th, td {
            padding: 10px;
            border: 1px solid #ddd;
        }
        th {
            background-color: #4CAF50;
            color: white;
        }
        tr:nth-child(even) {
            background-color: #f2f2f2;
        }
        .holiday {
            background-color: #f0ad4e;
            color: white;
            font-weight: bold;
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

<div class="container">
    <h1>Student Timetable</h1>
    
    <div class="student-info">
        <p><strong>Student ID:</strong> <?php echo htmlspecialchars($student_id); ?></p>
    </div>
    
    <table>
        <thead>
            <tr>
                <th>Time</th>
                <?php foreach ($days_of_week as $day): ?>
                    <th><?php echo $day; ?></th>
                <?php endforeach; ?>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($time_slots as $time): ?>
                <tr>
                    <td><?php echo $time; ?></td>
                    <?php foreach ($days_of_week as $day): ?>
                        <td>
                            <?php 
                            // Check if there's a timetable entry for this time slot and day
                            if ($time == '12:00') {
                                // Set break time for 12:00 to 13:00
                                echo "<span class='holiday'>Break</span>";
                            } elseif (isset($timetable_data[$time][$day])) {
                                $entry = $timetable_data[$time][$day];
                                echo htmlspecialchars($entry['subject']);
                            } else {
                                echo "-";
                            }
                            ?>
                        </td>
                    <?php endforeach; ?>
                </tr>
            <?php endforeach; ?>
            <!-- Add row for Saturday and Sunday holidays -->
            <tr>
                <td colspan="6" class="holiday">Holiday on Saturday and Sunday</td>
            </tr>
        </tbody>
    </table>
</div>

</body>
</html>
