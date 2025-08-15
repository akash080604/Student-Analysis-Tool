<?php
session_start(); // Start the session to access session variables

// Check if the user is logged in
if (!isset($_SESSION['user_email'])) {
    // If not logged in, redirect to login page
    header("Location: index.html");
    exit();
}

// Store the user's email in a variable
$userEmail = $_SESSION['user_email'];

// Include the database connection
include 'connect.php';

// Initialize variables for storing feedback messages
$message = '';
$studentID = ''; // Initialize student ID for updating purposes

// Check if we're editing an existing student
if (isset($_GET['edit']) && !empty($_GET['edit'])) {
    $studentID = $_GET['edit'];

    // Fetch the existing data for the student
    $stmt = $pdo->prepare("SELECT * FROM student WHERE ID = :id");
    $stmt->execute([':id' => $studentID]);
    $student = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$student) {
        $message = "Student not found.";
    }
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get the data from the POST request
    $name = trim($_POST['name']);
    $studentID = trim($_POST['student_id']);
    $dob = trim($_POST['dob']);
    $gender = trim($_POST['gender']);
    $class = trim($_POST['class']);

    // Validate inputs
    if (!empty($name) && !empty($studentID) && !empty($dob) && !empty($gender) && !empty($class)) {
        try {
            // Check if it's an update or an insert
            if (isset($_POST['update'])) {
                // Update the student record
                $stmt = $pdo->prepare("UPDATE student SET name = :name, dob = :dob, gender = :gender, class = :class WHERE ID = :id");
                $stmt->execute([
                    ':name' => htmlspecialchars($name),
                    ':dob' => htmlspecialchars($dob),
                    ':gender' => htmlspecialchars($gender),
                    ':class' => htmlspecialchars($class),
                    ':id' => $studentID
                ]);
                $message = "Student details updated successfully.";
            } else {
                // Check if the email already exists
                $checkStmt = $pdo->prepare("SELECT COUNT(*) FROM student WHERE email = :email");
                $checkStmt->execute([':email' => $userEmail]);
                $emailExists = $checkStmt->fetchColumn() > 0;

                if (!$emailExists) {
                    // Insert the data into the student table
                    $stmt = $pdo->prepare("INSERT INTO student (email, name, ID, dob, gender, class) VALUES (:email, :name, :id, :dob, :gender, :class)");
                    $stmt->execute([
                        ':email' => $userEmail,
                        ':name' => htmlspecialchars($name),
                        ':id' => htmlspecialchars($studentID),
                        ':dob' => htmlspecialchars($dob),
                        ':gender' => htmlspecialchars($gender),
                        ':class' => htmlspecialchars($class)
                    ]);
                    $message = "Student details added successfully.";
                } else {
                    $message = "Error: Already exists.";
                }
            }
        } catch (PDOException $e) {
            // Check for duplicate entry error
            if ($e->getCode() == 23000) {
                $message = "ID already exists.";
            } else {
                $message = "Error: " . htmlspecialchars($e->getMessage());
            }
        }
    } else {
        $message = "Please fill in all fields.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add or Update Student Details</title>
    <link rel="stylesheet" href="styles.css">
    <style>
         body {
            font-family: Arial, sans-serif;
            background-image: url('main.jpg'); /* Set main.jpg as background */
            background-size: cover;
            margin: 0;
            padding: 20px;
        }
        .form-container {
            width: 400px; /* Set width as needed */
            padding: 20px;
            background-color: #fff;
            text-align: center;
            border: 1px solid #ccc;
            border-radius: 10px;
            backdrop-filter: blur(10px);
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            margin: 50px auto;/* Center the text inside the box */
        }

        /* Logo style */
        .logo {
            max-width: 100px; /* Adjust logo size */
            margin-bottom: 20px;
        }

        /* Additional styles for form elements */
        input, select, button {
            width: 70%; /* Make inputs full width */
            padding: 10px;
            margin: 5px 0; /* Spacing between elements */
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        button {
            width:30%;
            background-color: #28a745; /* Green button */
            color: white;
            border: none;
            cursor: pointer;
        }

        button:hover {
            background-color: #218838; /* Darker green on hover */
        }
        .back-btn {
            width: 65px;;
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

    <div class="form-container">
        <img src="user.png" alt="User Logo" class="logo"> <!-- Add logo here -->
        <h3><?php echo $studentID ? 'Update' : 'Add'; ?> Student Details</h3>

        <!-- Display feedback message -->
        <p><?php echo $message; ?></p>

        <form method="post" action="">
            <input type="text" name="name" value="<?php echo $student['name'] ?? ''; ?>" placeholder="Enter Name" required>
            <input type="text" name="student_id" value="<?php echo $student['ID'] ?? ''; ?>" placeholder="Enter Student ID" required>
            <input type="date" name="dob" value="<?php echo $student['dob'] ?? ''; ?>" placeholder="Date of Birth" required>
            <select name="gender" required>
                <option value="">Select Gender</option>
                <option value="Male" <?php echo isset($student['gender']) && $student['gender'] == 'Male' ? 'selected' : ''; ?>>Male</option>
                <option value="Female" <?php echo isset($student['gender']) && $student['gender'] == 'Female' ? 'selected' : ''; ?>>Female</option>
            </select>
            <input type="text" name="class" value="<?php echo $student['class'] ?? ''; ?>" placeholder="Class" required>
            
            <!-- Hidden field to distinguish between add and update -->
            <button type="submit" name="<?php echo $studentID ? 'update' : ''; ?>">
                <?php echo $studentID ? 'Update' : 'Submit'; ?>
            </button>
        </form>
    </div>
</body>
</html>
