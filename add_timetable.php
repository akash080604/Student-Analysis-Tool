<?php
// Get student ID from URL
$student_id = isset($_GET['id']) ? $_GET['id'] : null;
$student_email = isset($_GET['student_email']) ? $_GET['student_email'] : null;

// Ensure student ID is provided
if (!$student_id) {
    echo "<p class='error'>No student ID provided!</p>";
    exit;
}

// Include the database connection (using PDO)
include('connect.php');

$success_message = "";
$error_message = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get the form data
    $day = $_POST['day'];
    $subject = $_POST['subject'];
    $start_time = $_POST['start_time'];
    $end_time = $_POST['end_time'];

    try {
        // Prepare the query for inserting data into the timetable
        $query = "INSERT INTO timetable (student_id, day, subject, start_time, end_time) 
                  VALUES (:student_id, :day, :subject, :start_time, :end_time)";
        
        // Prepare the PDO statement
        $stmt = $pdo->prepare($query);

        // Bind the parameters to the query
        $stmt->bindParam(':student_id', $student_id, PDO::PARAM_INT);
        $stmt->bindParam(':day', $day, PDO::PARAM_STR);
        $stmt->bindParam(':subject', $subject, PDO::PARAM_STR);
        $stmt->bindParam(':start_time', $start_time, PDO::PARAM_STR);
        $stmt->bindParam(':end_time', $end_time, PDO::PARAM_STR);

        // Execute the query
        if ($stmt->execute()) {
            $success_message = "Timetable entry added successfully!";
        } else {
            $error_message = "Error adding timetable entry.";
        }
    } catch (PDOException $e) {
        $error_message = "Error: " . htmlspecialchars($e->getMessage());
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Timetable Entry</title>
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
        .error {
            color: #e74c3c;
            text-align: center;
            margin-top: 10px;
        }
        .success {
            color: #4CAF50;
            text-align: center;
            margin-top: 10px;
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
    <h2>Add Timetable Entry for Student ID: <?php echo htmlspecialchars($student_id); ?></h2>

    <!-- Display success or error messages inside the form -->
    <?php if ($success_message): ?>
        <p class="success"><?php echo htmlspecialchars($success_message); ?></p>
    <?php elseif ($error_message): ?>
        <p class="error"><?php echo htmlspecialchars($error_message); ?></p>
    <?php endif; ?>

    <form method="post" action="add_timetable.php?id=<?php echo urlencode($student_id); ?>&student_email=<?php echo urlencode($student_email); ?>">
        <!-- Day selection -->
        <label for="day">Day:</label>
        <select name="day" id="day" required>
            <option value="Monday">Monday</option>
            <option value="Tuesday">Tuesday</option>
            <option value="Wednesday">Wednesday</option>
            <option value="Thursday">Thursday</option>
            <option value="Friday">Friday</option>
        </select>

        <!-- Subject selection -->
        <label for="subject">Subject:</label>
        <select name="subject" id="subject" required>
            <option value="Hindi">Hindi</option>
            <option value="English">English</option>
            <option value="SST">SST</option>
            <option value="Science">Science</option>
            <option value="Maths">Maths</option>
        </select>

        <!-- Start time input -->
        <label for="start_time">Start Time:</label>
        <input type="time" name="start_time" id="start_time" required>

        <!-- End time input -->
        <label for="end_time">End Time:</label>
        <input type="time" name="end_time" id="end_time" required>

        <!-- Submit button -->
        <input type="submit" value="Add Timetable Entry">
    </form>
</div>

</body>
</html>
