<?php
session_start();
require 'connect.php'; // Database connection

// Check if the user is logged in
if (!isset($_SESSION['user_email'])) {
    header("Location: login.html");
    exit();
}

$userEmail = $_SESSION['user_email'];

try {
    // Prepare the query to get the student ID and attendance data for each subject
    $query = "SELECT id, subject, total_class, present, absent, percent FROM attendance WHERE email = :email";
    $stmt = $pdo->prepare($query);  // Use $pdo for prepared statements
    $stmt->bindParam(':email', $userEmail, PDO::PARAM_STR);
    $stmt->execute();

    // Fetch the results
    $attendanceData = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Get student ID (assuming it is the same for all subjects)
    if (count($attendanceData) > 0) {
        $studentId = $attendanceData[0]['id']; // Get student ID from first subject record
    }
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Attendance</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-image: url('main.jpg'); /* Set main.jpg as background */
            background-size: cover;
            margin: 0;
            padding: 20px;
        }
        
        .attendance-container {
            width: 900px; /* Adjusted width */
            padding: 20px;
            background-color: #fff;
            border: 1px solid #ccc;
            border-radius: 5px;
            backdrop-filter: blur(10px);
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            margin: auto;
        }
        
        h1 {
            font-size: 24px;
            text-align: center;
        }

        .student-info {
            text-align: center;
            margin-bottom: 20px;
        }

        .attendance-table {
            width: 100%;
            border-collapse: collapse;
        }

        .attendance-table th, .attendance-table td {
            padding: 10px;
            border: 1px solid #ccc;
            text-align: center;
        }

        .low-attendance {
            color: red; /* Highlight attendance below 75% in red */
        }

        .chart-container {
            width: 100%;
            margin-top: 30px;
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
<div class="attendance-container">
    <h1>Attendance Record</h1>
    
    <!-- Display student email and ID -->
    <div class="student-info">
        <p><strong>Email:</strong> <?php echo htmlspecialchars($userEmail); ?></p>
        <p><strong>Student ID:</strong> <?php echo htmlspecialchars($studentId); ?></p>
    </div>
    
    <table class="attendance-table">
        <tr>
            <th>Subject</th>
            <th>Total Classes</th>
            <th>Present</th>
            <th>Absent</th>
            <th>Attendance %</th>
        </tr>
        <?php foreach ($attendanceData as $data): ?>
            <tr>
                <td><?php echo htmlspecialchars($data['subject']); ?></td>
                <td><?php echo htmlspecialchars($data['total_class']); ?></td>
                <td><?php echo htmlspecialchars($data['present']); ?></td>
                <td><?php echo htmlspecialchars($data['absent']); ?></td>
                <td class="<?php echo ($data['percent'] < 75) ? 'low-attendance' : ''; ?>">
                    <?php echo htmlspecialchars($data['percent']) . '%'; ?>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
    
    <!-- Chart.js graph -->
    <div class="chart-container">
        <canvas id="attendanceChart"></canvas>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Prepare the data for the chart
    const subjects = <?php echo json_encode(array_column($attendanceData, 'subject')); ?>;
    const attendancePercent = <?php echo json_encode(array_column($attendanceData, 'percent')); ?>;

    // Create the chart
    const ctx = document.getElementById('attendanceChart').getContext('2d');
    const attendanceChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: subjects, // Subjects for X-axis
            datasets: [{
                label: 'Attendance Percentage',
                data: attendancePercent, // Attendance percentages for Y-axis
                backgroundColor: attendancePercent.map(percent => percent < 75 ? 'red' : 'green'), // Color based on attendance percentage
                borderColor: '#000',
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true,
                    max: 100,
                    title: {
                        display: true,
                        text: 'Percentage'
                    }
                }
            }
        }
    });
</script>

</body>
</html>
