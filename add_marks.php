<?php
// Include the database connection
include 'connect.php';

// Include PHPMailer for sending notifications
include 'notification.php'; // Ensure this file contains the sendNotification function

// Initialize variables for storing feedback messages
$message = '';
$percentage = null; // Initialize percentage variable

// Get the student ID and email from the URL
$studentID = isset($_GET['id']) ? intval($_GET['id']) : 0;
$studentEmail = isset($_GET['student_email']) ? $_GET['student_email'] : ''; // Fetch the email from the URL

// Handle form submission for adding marks
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get the term and marks from the form submission
    $term = isset($_POST['term']) ? trim($_POST['term']) : '';
    $english = trim($_POST['english']);
    $hindi = trim($_POST['hindi']);
    $sst = trim($_POST['sst']);
    $science = trim($_POST['science']);
    $maths = trim($_POST['maths']);

    // Validate inputs
    if ($term && is_numeric($english) && is_numeric($hindi) && is_numeric($sst) && is_numeric($science) && is_numeric($maths)) {
        try {
            // Check if marks already exist for the student for the given term
            $checkStmt = $pdo->prepare("SELECT COUNT(*) FROM marks WHERE ID = :id AND email = :email AND term = :term");
            $checkStmt->execute([':id' => $studentID, ':email' => $studentEmail, ':term' => $term]);
            $marksExists = $checkStmt->fetchColumn() > 0;

            // Calculate total marks and percentage
            $totalMarks = $english + $hindi + $sst + $science + $maths;
            $percentage = ($totalMarks / 500) * 100; // Out of 500 total marks

            if (!$marksExists) {
                // Insert marks into the database
                $stmt = $pdo->prepare("INSERT INTO marks (ID, email, term, english, hindi, sst, science, maths, percent) VALUES (:id, :email, :term, :english, :hindi, :sst, :science, :maths, :percent)");
                $stmt->execute([
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

                // Message for successful addition of marks
                $message = "Marks added successfully for Term: " . htmlspecialchars($term) . ". Percentage: " . number_format($percentage, 2) . "%";

                // Call the function to send the notification email
                $notificationMessage = sendNotification($studentEmail, $studentID, $term, $percentage);
                $message .= "<br>" . htmlspecialchars($notificationMessage); // Append notification message to the main message
            } else {
                $message = "Marks already exist for Term: " . htmlspecialchars($term) . ". Use the update option.";
            }
        } catch (PDOException $e) {
            $message = "Error: " . htmlspecialchars($e->getMessage());
        }
    } else {
        $message = "Error: Please fill all fields correctly.";
    }
}

// HTML and form code follows...
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add or Update Marks</title>
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
            position: relative;
        }
        .back-arrow {
            position: absolute;
            left: 20px;
            top: 20px;
            font-size: 24px;
            text-decoration: none;
            color: #333;
            background-color: #e0e0e0;
            padding: 5px 10px;
            border-radius: 5px;
        }
        .back-arrow:hover {
            background-color: #bdbdbd;
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
        .update-btn {
            background-color: #3f51b5;
            margin-top: 10px;
        }
        .update-btn:hover {
            background-color: #303f9f;
        }
        .message {
            color: #d32f2f; /* Red color for error messages */
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
    <header>
    <a href="control.php" style="text-decoration: none;position:absolute;left:2%; color: #333; font-size: 24px;">&#8592; Back</a> <!-- Back arrow -->
        <h1>Add Marks for Student ID: <?php echo htmlspecialchars($studentID); ?></h1>
        <p>Email: <?php echo htmlspecialchars($studentEmail); ?></p>
    </header>
    
    <main>
        <?php if ($message): ?>
            <div class="message"><?php echo htmlspecialchars($message); ?></div>
        <?php endif; ?>

        <?php for ($i = 1; $i <= 4; $i++): ?>
            <div class="content-box">
                <h2>Term <?php echo $i; ?></h2>
                <form method="post" action="">
                    <input type="hidden" name="term" value="Term <?php echo $i; ?>">
                    <label for="english">English Marks</label>
                    <input type="number" name="english" placeholder="Enter English Marks" required max="100">
                    <label for="hindi">Hindi Marks</label>
                    <input type="number" name="hindi" placeholder="Enter Hindi Marks" required max="100">
                    <label for="sst">SST Marks</label>
                    <input type="number" name="sst" placeholder="Enter SST Marks" required max="100">
                    <label for="science">Science Marks</label>
                    <input type="number" name="science" placeholder="Enter Science Marks" required max="100">
                    <label for="maths">Maths Marks</label>
                    <input type="number" name="maths" placeholder="Enter Maths Marks" required max="100">
                    <button type="submit">Submit Marks for Term <?php echo $i; ?></button>
                </form>
                <form method="get" action="update_marks.php">
                    <input type="hidden" name="id" value="<?php echo $studentID; ?>">
                    <input type="hidden" name="student_email" value="<?php echo $studentEmail; ?>">
                    <input type="hidden" name="term" value="Term <?php echo $i; ?>">
                    <button type="submit" class="update-btn">Update Marks for Term <?php echo $i; ?></button>
                </form>
            </div>
        <?php endfor; ?>
    </main>
</body>
</html>
