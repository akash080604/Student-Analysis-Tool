<?php
session_start(); // Start the session to access session variables

// Include the database connection and the update notification
include 'connect.php';
include 'update_notification.php'; // Include the file for sending update notifications

// Initialize variables for storing feedback messages
$message = '';

// Get the student ID, email, and term from the URL
$studentID = isset($_GET['id']) ? intval($_GET['id']) : 0;
$studentEmail = isset($_GET['student_email']) ? $_GET['student_email'] : ''; // Get student email from the URL
$term = isset($_GET['term']) ? $_GET['term'] : '';

// Fetch existing marks for the student and term
$existingMarks = null;
try {
    $stmt = $pdo->prepare("SELECT * FROM marks WHERE ID = :id AND email = :email AND term = :term");
    $stmt->execute([':id' => $studentID, ':email' => $studentEmail, ':term' => $term]);
    $existingMarks = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$existingMarks) {
        $message = "No marks found for the specified term.";
    }
} catch (PDOException $e) {
    $message = "Error: " . htmlspecialchars($e->getMessage());
}

// Handle form submission for updating marks
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get the marks from the form submission
    $english = trim($_POST['english']);
    $hindi = trim($_POST['hindi']);
    $sst = trim($_POST['sst']);
    $science = trim($_POST['science']);
    $maths = trim($_POST['maths']);

    // Validate inputs
    if (is_numeric($english) && is_numeric($hindi) && is_numeric($sst) && is_numeric($science) && is_numeric($maths)) {
        try {
            // Calculate total marks and percentage
            $totalMarks = $english + $hindi + $sst + $science + $maths;
            $percentage = ($totalMarks / 500) * 100; // Out of 500 total marks

            // Update marks in the database
            $updateStmt = $pdo->prepare("UPDATE marks SET english = :english, hindi = :hindi, sst = :sst, science = :science, maths = :maths, percent = :percent WHERE ID = :id AND email = :email AND term = :term");
            $updateStmt->execute([
                ':id' => $studentID,
                ':email' => $studentEmail,
                ':term' => $term,
                ':english' => htmlspecialchars($english),
                ':hindi' => htmlspecialchars($hindi),
                ':sst' => htmlspecialchars($sst),
                ':science' => htmlspecialchars($science),
                ':maths' => htmlspecialchars($maths),
                ':percent' => number_format($percentage, 2)
            ]);

            // Send notification email after successfully updating the marks
            $notificationMessage = sendUpdateNotification($studentEmail, $studentID, $term, $percentage);
            
            $message = "Marks updated successfully for Term: " . htmlspecialchars($term) . ". Percentage: " . number_format($percentage, 2) . "%. " . htmlspecialchars($notificationMessage);
        } catch (PDOException $e) {
            $message = "Error: " . htmlspecialchars($e->getMessage());
        }
    } else {
        $message = "Error: Please fill all fields correctly.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Marks</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        body {
            background-image: url('login.jpg');
            background-size: cover;
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
        }
        header {
            text-align: center;
            margin-bottom: 20px;
        }
        .content-box {
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
        }
        label {
            margin-top: 10px;
            display: block;
            font-weight: bold;
        }
        input[type="number"] {
            width: 100%;
            padding: 10px;
            margin: 5px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        button {
            background-color: #00695c;
            color: white;
            border: none;
            padding: 10px;
            border-radius: 5px;
            cursor: pointer;
            width: 100%;
        }
        button:hover {
            background-color: #004d45;
        }
        .message {
            color: #d32f2f; /* Red color for error messages */
            margin-top: 10px;
        }
    </style>
</head>
<body>
<header>
    <a href="add_marks.php" style="text-decoration: none;position:absolute;left:2%; color: #333; font-size: 24px;">&#8592; Back</a> <!-- Back arrow -->
    <h1>Update Marks for Student ID: <?php echo htmlspecialchars($studentID); ?></h1>
    <p>Email: <?php echo htmlspecialchars($studentEmail); ?></p>
    <p>Term: <?php echo htmlspecialchars($term); ?></p>
</header>

<main>
    <?php if ($message): ?>
        <div class="message"><?php echo htmlspecialchars($message); ?></div>
    <?php endif; ?>

    <?php if ($existingMarks): ?>
        <div class="content-box">
            <form method="post" action="">
                <label for="english">English Marks</label>
                <input type="number" name="english" placeholder="Enter English Marks" required max="100" value="<?php echo htmlspecialchars($existingMarks['english']); ?>">
                <label for="hindi">Hindi Marks</label>
                <input type="number" name="hindi" placeholder="Enter Hindi Marks" required max="100" value="<?php echo htmlspecialchars($existingMarks['hindi']); ?>">
                <label for="sst">SST Marks</label>
                <input type="number" name="sst" placeholder="Enter SST Marks" required max="100" value="<?php echo htmlspecialchars($existingMarks['sst']); ?>">
                <label for="science">Science Marks</label>
                <input type="number" name="science" placeholder="Enter Science Marks" required max="100" value="<?php echo htmlspecialchars($existingMarks['science']); ?>">
                <label for="maths">Maths Marks</label>
                <input type="number" name="maths" placeholder="Enter Maths Marks" required max="100" value="<?php echo htmlspecialchars($existingMarks['maths']); ?>">
                <button type="submit">Update Marks</button>
            </form>
        </div>
    <?php endif; ?>
</main>
</body>
</html>
