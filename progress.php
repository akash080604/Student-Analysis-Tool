<?php
// Include the database connection file
include 'connect.php';

// Start the session to get the email from index.php
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_email'])) {
    // If not logged in, redirect to login page
    header("Location: index.html");
    exit();
}

// Use the correct session variable for the email
$email = $_SESSION['user_email'];

// Prepare SQL statements to fetch student details and marks for all terms
$studentQuery = "SELECT * FROM student WHERE email = ?";
$marksQuery = "SELECT * FROM marks WHERE email = ?";

// Prepare and execute the student query
$stmt = $pdo->prepare($studentQuery);
$stmt->bindParam(1, $email);
$stmt->execute();
$studentResult = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Prepare and execute the marks query
$stmtMarks = $pdo->prepare($marksQuery);
$stmtMarks->bindParam(1, $email);
$stmtMarks->execute();
$marksResults = $stmtMarks->fetchAll(PDO::FETCH_ASSOC);

// Function to determine grade and remark
function getGrade($marks) {
    if ($marks >= 91) return 'A+';
    if ($marks >= 81) return 'A';
    if ($marks >= 71) return 'B';
    if ($marks >= 61) return 'C';
    if ($marks >= 51) return 'D';
    if ($marks >= 36) return 'E';
    return 'F';
}

function getRemark($percentage) {
    return $percentage < 36 ? 'Fail' : 'Pass';
}

// ** Add this new function to calculate grades for each subject **
function getSubjectGrade($marks) {
    if ($marks >= 91) return 'A+';
    if ($marks >= 81) return 'A';
    if ($marks >= 71) return 'B';
    if ($marks >= 61) return 'C';
    if ($marks >= 51) return 'D';
    if ($marks >= 36) return 'E';
    return 'F';
}

// Check if student data is available
if (count($studentResult) > 0 && count($marksResults) > 0) {
    $student = $studentResult[0];

    // Calculate total, percentage, and grades for each term
    $termsData = [];
    foreach ($marksResults as $marks) {
        $totalMarks = $marks['english'] + $marks['hindi'] + $marks['sst'] + $marks['science'] + $marks['maths'];
        $percentage = ($totalMarks / 500) * 100;

        // ** Update this array to include subject grades **
        $termsData[] = [
            'term' => $marks['term'],
            'marks' => [
                'English' => $marks['english'],
                'Hindi' => $marks['hindi'],
                'SST' => $marks['sst'],
                'Science' => $marks['science'],
                'Maths' => $marks['maths'],
            ],
            'grades' => [ // Added grades for each subject
                'English' => getSubjectGrade($marks['english']),
                'Hindi' => getSubjectGrade($marks['hindi']),
                'SST' => getSubjectGrade($marks['sst']),
                'Science' => getSubjectGrade($marks['science']),
                'Maths' => getSubjectGrade($marks['maths']),
            ],
            'total' => $totalMarks,
            'percentage' => round($percentage, 2),
            'grade' => getGrade($totalMarks),
            'remark' => getRemark($percentage),
        ];
    }
} else {
    echo "<div>No student data found.</div>";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Progress Check</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-image: url('main.jpg'); /* Set main.jpg as background */
            background-size: cover;
            background-position: center;
            margin: 0;
            padding: 20px;
        }
        .container {
            width: 900px; /* Adjusted width */
            padding: 20px;
            background-color: #fff;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            margin: auto;
        }
        .term-box {
            display: inline-block;
            width: 30%; /* Adjusted width */
            margin: 1%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            background-color: #f9f9f9;
        }
        h2 {
            margin: 0 0 15px;
            font-size: 18px;
        }
        table {
            width: 100%;
            margin-top: 15px;
            border-collapse: collapse;
        }
        table, th, td {
            border: 1px solid #ccc;
            padding: 8px;
            text-align: left;
        }
        .chart-container {
            width: 100%;
            height: 300px;
            margin-top: 20px;
        }
        .fail {
            color: red;
        }
        .pass {
            color: green;
        }
        @media print {
            body {
                background-color: #fff;
                color: #000;
            }
            .container {
                width: 100%;
                border: none;
                box-shadow: none;
            }
            input[type="submit"], #pdfForm {
                display: none; /* Hide the button during printing */
            }
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
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
<button class="back-btn" onclick="window.location.href='index.php'">Back</button>
    <div class="container" id="printArea">
        <h2>Progress Chart</h2>
        <p><strong>Name:</strong> <?php echo htmlspecialchars($student['name']); ?></p>
        <p><strong>ID:</strong> <?php echo htmlspecialchars($student['ID']); ?></p>
        <p><strong>DOB:</strong> <?php echo htmlspecialchars($student['dob']); ?></p>
        <p><strong>Gender:</strong> <?php echo htmlspecialchars($student['gender']); ?></p>

        <h2>Marks</h2>
        <div class="term-boxes">
            <?php foreach ($termsData as $termData): ?>
            <div class="term-box">
                <h3><?php echo htmlspecialchars($termData['term']); ?></h3>
                <!-- Removed table displaying subject marks and grades -->
                <p><strong>Total:</strong> <?php echo $termData['total']; ?> out of 500</p>
                <p><strong>Percentage:</strong> <?php echo $termData['percentage']; ?>%</p>
                <p class="<?php echo $termData['remark'] === 'Fail' ? 'fail' : 'pass'; ?>">
                    <strong>Remark:</strong> <?php echo htmlspecialchars($termData['remark']); ?>
                </p>
            </div>
            <?php endforeach; ?>
        </div>

        <!-- Charts -->
        <div class="chart-container">
            <canvas id="marksChart"></canvas>
        </div>
        <div class="chart-container">
            <canvas id="percentageChart"></canvas>
        </div>
    </div>

    <!-- JavaScript and Chart code -->
    <script>
        // Marks Chart for each term
        const marksData = <?php echo json_encode(array_map(function($termData) {
            return $termData['marks'];
        }, $termsData)); ?>;

        const labels = <?php echo json_encode(array_map(function($termData) {
            return $termData['term'];
        }, $termsData)); ?>;

        const marksChart = new Chart(document.getElementById('marksChart').getContext('2d'), {
            type: 'bar',
            data: {
                labels: ['English', 'Hindi', 'SST', 'Science', 'Maths'],
                datasets: labels.map((label, index) => ({
                    label: label,
                    data: Object.values(marksData[index]),
                    backgroundColor: ['rgba(255, 192, 203, 0.6)', 'rgba(54, 162, 235, 0.6)', 'rgba(255, 206, 86, 0.6)', 'rgba(75, 192, 192, 0.6)', 'rgba(153, 102, 255, 0.6)'][index],
                    borderColor: ['rgba(255, 192, 203, 1)', 'rgba(54, 162, 235, 1)', 'rgba(255, 206, 86, 1)', 'rgba(75, 192, 192, 1)', 'rgba(153, 102, 255, 1)'][index],
                    borderWidth: 1
                })),
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });

        // Percentage Chart
        const percentageData = <?php echo json_encode(array_map(function($termData) {
            return $termData['percentage'];
        }, $termsData)); ?>;

        const percentageChart = new Chart(document.getElementById('percentageChart').getContext('2d'), {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Percentage',
                    data: percentageData,
                    fill: false,
                    borderColor: 'rgba(75, 192, 192, 1)',
                    tension: 0.1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    </script>
</body>
</html>
