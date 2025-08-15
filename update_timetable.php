<?php
include 'connect.php';  // Ensure this file contains the code to connect to your database

// Fetch the student ID and email from the query parameters
$student_id = $_GET['id'];
$student_email = $_GET['student_email'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Handle form submission to update timetable
    $day = $_POST['day'];
    $subject = $_POST['subject'];
    $start_time = $_POST['start_time'];
    $end_time = $_POST['end_time'];

    // Update the timetable for the selected student
    $query = "UPDATE timetable SET day = :day, subject = :subject, start_time = :start_time, end_time = :end_time WHERE student_id = :student_id";
    $stmt = $pdo->prepare($query);
    $stmt->execute([
        ':day' => $day,
        ':subject' => $subject,
        ':start_time' => $start_time,
        ':end_time' => $end_time,
        ':student_id' => $student_id
    ]);

    $message = "<p class='success'>Timetable updated successfully for $student_email!</p>";
}

// Fetch existing timetable data for the student
$query = "SELECT * FROM timetable WHERE student_id = :student_id";
$stmt = $pdo->prepare($query);
$stmt->execute([':student_id' => $student_id]);
$timetable = $stmt->fetch(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Timetable</title>
    <style>
        body {
            background-image: url('login.jpg');
            background-size: cover;
            background-position: center;
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .form-container {
            background-color: rgba(255, 255, 255, 0.8);
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.3);
            max-width: 400px;
            width: 100%;
        }
        .form-container h2 {
            text-align: center;
            color: #333;
            margin-bottom: 20px;
        }
        .form-container label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
            color: #555;
        }
        .form-container input[type="text"],
        .form-container input[type="time"],
        .form-container select,
        .form-container input[type="submit"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 20px;
            border-radius: 5px;
            border: 1px solid #ddd;
            font-size: 16px;
        }
        .form-container input[type="submit"] {
            background-color: #4CAF50;
            color: white;
            cursor: pointer;
        }
        .form-container input[type="submit"]:hover {
            background-color: #45a049;
        }
        .form-container input[type="checkbox"] {
            margin-right: 10px;
        }
        .success {
            color: #4CAF50;
            text-align: center;
            margin-top: 10px;
            background-color: rgba(76, 175, 80, 0.2);
            padding: 10px;
            border-radius: 5px;
            border: 1px solid #4CAF50;
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
<button class="back-btn" onclick="window.location.href='control.php'">Back</button>

<div class="form-container">
    <h2>Update Timetable for Student ID: <?php echo htmlspecialchars($student_email); ?></h2>

    <?php if (isset($message)) echo $message; ?>

    <form method="post" action="">
        <label for="day">Day:</label>
        <select name="day" id="day">
            <option value="Monday" <?php if ($timetable['day'] == 'Monday') echo 'selected'; ?>>Monday</option>
            <option value="Tuesday" <?php if ($timetable['day'] == 'Tuesday') echo 'selected'; ?>>Tuesday</option>
            <option value="Wednesday" <?php if ($timetable['day'] == 'Wednesday') echo 'selected'; ?>>Wednesday</option>
            <option value="Thursday" <?php if ($timetable['day'] == 'Thursday') echo 'selected'; ?>>Thursday</option>
            <option value="Friday" <?php if ($timetable['day'] == 'Friday') echo 'selected'; ?>>Friday</option>
        </select>

        <label for="subject">Subject:</label>
        <input type="text" name="subject" id="subject" value="<?php echo htmlspecialchars($timetable['subject']); ?>">

        <label for="start_time">Start Time:</label>
        <input type="time" name="start_time" id="start_time" value="<?php echo htmlspecialchars($timetable['start_time']); ?>">

        <label for="end_time">End Time:</label>
        <input type="time" name="end_time" id="end_time" value="<?php echo htmlspecialchars($timetable['end_time']); ?>">

    
        <input type="submit" value="Update Timetable">
    </form>
</div>
</body>
</html>
