<?php
session_start();
require 'connect.php'; // Database connection
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require 'vendor/autoload.php'; // Include PHPMailer

// Check if the user is logged in
if (!isset($_SESSION['user_email'])) {
    header("Location: login.html");
    exit();
}

if (!isset($_GET['id']) || !isset($_GET['student_email'])) {
    die('Invalid student ID or email.');
}

$studentID = $_GET['id'];
$studentEmail = $_GET['student_email'];

// Handle the form submission to add or update attendance
$successMessage = ''; // Variable to store the success message

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $subject = $_POST['subject'];
    $totalClass = $_POST['total_class'];
    $present = $_POST['present'];
    
    // Calculate absent classes automatically
    $absent = $totalClass - $present;

    // Calculate attendance percentage
    if ($totalClass > 0) {
        $percent = ($present / $totalClass) * 100;
    } else {
        $percent = 0; // Avoid division by zero
    }

    try {
        // Check if attendance for this subject already exists for the student
        $stmt = $pdo->prepare("SELECT * FROM attendance WHERE email = :email AND subject = :subject");
        $stmt->bindParam(':email', $studentEmail);
        $stmt->bindParam(':subject', $subject);
        $stmt->execute();
        
        if ($stmt->rowCount() > 0) {
            // Update attendance if already exists
            $updateStmt = $pdo->prepare("UPDATE attendance SET total_class = :total_class, present = :present, absent = :absent, percent = :percent WHERE email = :email AND subject = :subject");
            $updateStmt->bindParam(':total_class', $totalClass);
            $updateStmt->bindParam(':present', $present);
            $updateStmt->bindParam(':absent', $absent);
            $updateStmt->bindParam(':percent', $percent);
            $updateStmt->bindParam(':email', $studentEmail);
            $updateStmt->bindParam(':subject', $subject);
            $updateStmt->execute();
        } else {
            // Insert new attendance record
            $insertStmt = $pdo->prepare("INSERT INTO attendance (email, id, subject, total_class, present, absent, percent) VALUES (:email, :id, :subject, :total_class, :present, :absent, :percent)");
            $insertStmt->bindParam(':email', $studentEmail);
            $insertStmt->bindParam(':id', $studentID);
            $insertStmt->bindParam(':subject', $subject);
            $insertStmt->bindParam(':total_class', $totalClass);
            $insertStmt->bindParam(':present', $present);
            $insertStmt->bindParam(':absent', $absent);
            $insertStmt->bindParam(':percent', $percent);
            $insertStmt->execute();
        }

        // Send email after attendance is updated or added
        $mail = new PHPMailer(true);
        try {
            // Server settings
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';  // Gmail SMTP server
            $mail->SMTPAuth = true;
            $mail->Username = 'studentanalysistools@gmail.com';  // Your Gmail username
            $mail->Password = 'tvcp hkfg sgin mzms';  // Your Gmail app-specific password
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;  // TCP port to connect to

            // Recipients
            $mail->setFrom('studentanalysistools@gmail.com', 'Admin');
            $mail->addAddress($studentEmail, $studentID); // Add a recipient
            $mail->addReplyTo('studentanalysistools@gmail.com', 'Admin');

            // Content
            $mail->isHTML(true);
            $mail->Subject = "Attendance Update for $subject";
            $mail->Body    = "<p>Attendance for $subject has been updated for student $studentEmail (ID: $studentID).</p><p>Total Classes: $totalClass<br>Classes Present: $present<br>Classes Absent: $absent<br>Attendance Percentage: " . number_format($percent, 2) . "%</p>";

            $mail->send();

            // Redirect back to the same page after sending the email
            header("Location: add_attendance.php?id=" . urlencode($studentID) . "&student_email=" . urlencode($studentEmail));
            exit();

        } catch (Exception $e) {
            echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }

        // Set success message to show on page
        $successMessage = "Attendance for $subject has been successfully added/updated!";
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}

// Fetch existing attendance records to display them for the student
$attendanceRecords = [];
$stmt = $pdo->prepare("SELECT * FROM attendance WHERE email = :email AND id = :id");
$stmt->bindParam(':email', $studentEmail);
$stmt->bindParam(':id', $studentID);
$stmt->execute();
$attendanceRecords = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Attendance</title>
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
        .form-container {
            max-width: 600px;
            margin: auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        h1 {
            text-align: center;
        }
        label {
            font-size: 16px;
            margin-bottom: 8px;
            display: block;
        }
        input[type="text"], input[type="number"] {
            width: 100%;
            padding: 8px;
            margin-bottom: 12px;
            border-radius: 5px;
            border: 1px solid #ddd;
        }
        .btn {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .btn:hover {
            background-color: #45a049;
        }
        .btn-update {
            background-color: #008CBA;
        }
        .btn-update:hover {
            background-color: #007B9A;
        }
        .attendance-records {
            margin-top: 20px;
            font-size: 14px;
        }
        .attendance-records table {
            width: 100%;
            border-collapse: collapse;
        }
        .attendance-records th, .attendance-records td {
            padding: 8px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        /* Back button styles */
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
    <!-- Back Button -->
    <button class="back-btn" onclick="window.location.href='control.php'">Back</button>

    <div class="form-container">
        <h1>Add Attendance for <?php echo htmlspecialchars($studentEmail); ?> (ID: <?php echo htmlspecialchars($studentID); ?>)</h1>

        <!-- Show success message if attendance is added/updated -->
        <?php if ($successMessage): ?>
            <script>
                alert("<?php echo $successMessage; ?>");
            </script>
        <?php endif; ?>

        <!-- Attendance form -->
        <form action="add_attendance.php?id=<?php echo urlencode($studentID); ?>&student_email=<?php echo urlencode($studentEmail); ?>" method="POST">
            <label for="subject">Subject</label>
            <select name="subject" required>
                <option value="English">English</option>
                <option value="Hindi">Hindi</option>
                <option value="SST">SST</option>
                <option value="Science">Science</option>
                <option value="Maths">Maths</option>
            </select>

            <label for="total_class">Total Classes</label>
            <input type="number" name="total_class" min="0" required>

            <label for="present">Classes Present</label>
            <input type="number" name="present" min="0" required>

            <button type="submit" class="btn">Add/Update Attendance</button>
        </form>

        <!-- Display existing attendance records -->
        <div class="attendance-records">
            <h2>Existing Attendance Records</h2>
            <table>
                <thead>
                    <tr>
                        <th>Subject</th>
                        <th>Total Classes</th>
                        <th>Present</th>
                        <th>Absent</th>
                        <th>Percentage</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($attendanceRecords as $record): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($record['subject']); ?></td>
                            <td><?php echo htmlspecialchars($record['total_class']); ?></td>
                            <td><?php echo htmlspecialchars($record['present']); ?></td>
                            <td><?php echo htmlspecialchars($record['absent']); ?></td>
                            <td><?php echo number_format($record['percent'], 2) . '%'; ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
