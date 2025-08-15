<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_email'])) {
    header("Location: login.html");
    exit();
}

// Store the user's email in a variable
$userEmail = $_SESSION['user_email'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Analysis Tool</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-image: url('main.jpg');
            background-size: cover;
            background-position: center;
        }

        .container {
            display: flex;
            height: 100vh;
        }

        .sidebar {
            width: 250px;
            backdrop-filter: blur(10px);
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.5);
            padding: 20px;
        }

        .sidebar h1 {
            font-size: 24px;
            margin: 0;
            color: white;
        }

        .logo {
            width: 50px;
            height: 50px;
            margin-bottom: 20px;
        }

        .nav-buttons {
            display: flex;
            flex-direction: column;
            margin-bottom: 20px;
        }

        .nav-button {
            background-color: white;
            border: 1px solid #ccc;
            padding: 10px;
            margin: 5px 0;
            text-align: center;
            border-radius: 5px;
            transition: background-color 0.3s;
            cursor: pointer;
        }

        .nav-button:hover {
            background-color: #e0e0e0;
        }

        .content {
            flex-grow: 1;
            padding: 20px;
            background-color: rgba(255, 255, 255, 0.8);
            box-shadow: -2px 0 5px rgba(0, 0, 0, 0.1);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: flex-start;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            width: 100%;
            padding: 10px;
            backdrop-filter: blur(10px);
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.5);
            background-color: rgba(0, 128, 128, 0.8);
        }

        .header h2 {
            margin: 0;
            color: white;
            display: flex;
            align-items: center;
        }

        .header img {
            width: 30px;
            height: 30px;
            margin-right: 10px;
        }

        .logout, .add-detail, .delete-account {
            background-color: white;
            border: 1px solid #ccc;
            padding: 10px;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
            margin-left: 10px;
        }

        .logout:hover, .add-detail:hover, .delete-account:hover {
            background-color: #e0e0e0;
        }

        .table-container {
            width: 100%;
            margin-top: 15px;
        }

        .table-container table {
            width: 100%;
            border-collapse: collapse;
        }

        .table-container th, .table-container td {
            padding: 8px;
            border: 1px solid #ccc;
            text-align: left;
        }

        .table-container th {
            background-color: #f2f2f2;
            font-weight: bold;
        }
    </style>
    <script>
        function logout() {
            alert("Logout successfully");
            window.location.href = "login.html";
        }

        function contactUs() {
            window.location.href = "contact_us1.html";
        }

        function deleteAccount() {
            if (confirm("Are you sure you want to delete your account? This action cannot be undone.")) {
                window.location.href = "delete_account.php";
            }
        }
    </script>
</head>
<body>
    <div class="container">
        <div class="sidebar">
            <img src="assessment.png" alt="Logo" class="logo">
            <h1>Academics</h1>
            <div class="nav-buttons">
                <div class="nav-button" onclick="window.location.href='profile.php';">Profile</div>
                <div class="nav-button" onclick="window.location.href='timetable.php';">Timetable</div>
                <div class="nav-button" onclick="window.location.href='attendance.php';">Attendance</div>
                <div class="nav-button" onclick="window.location.href='show_datesheet.php';">Datesheet</div>
                <div class="nav-button" onclick="window.location.href='study_materials.php';">Study Material</div>
                <div class="nav-button" onclick="window.location.href='progress.php';">Progress Check</div>
                <div class="nav-button" onclick="window.location.href='report.php';">Report</div>
                <div class="nav-button" onclick="window.location.href='certificate.php';">Certificate</div>
                <div class="nav-button" onclick="contactUs()">Contact Us</div>
            </div>
        </div>
        
        <div class="content">
            <div class="header">
                <h2>
                    <img src="user.png" alt="User Icon">
                    Email: <?php echo htmlspecialchars($userEmail); ?>
                </h2>
                <div>
                    <button class="add-detail" onclick="window.location.href='add_detail.php';">Add Detail</button>
                    <button class="logout" onclick="logout()">Logout</button>
                    <button class="delete-account" onclick="deleteAccount()">Delete Account</button>
                </div>
            </div>

            <div class="table-container">
                <table>
                    <tr>
                        <th>Event</th>
                        <th>Subject</th>
                    </tr>
                    <tr><td>BROADCAST</td><td>THINKSWISS RESEARCH SCHOLARSHIPS 2025</td></tr>
                    <tr><td>BROADCAST</td><td>IET Bangalore Local Network Student Competition</td></tr>
                    <tr><td>BROADCAST</td><td>Notification for Proctored Learning-Special Examination-November 2024</td></tr>
                    <tr><td>BROADCAST</td><td>Global STEM Leaders Fellowship Program 2024</td></tr>
                    <tr><td>BROADCAST</td><td>Harvard University Online Course - Data Science</td></tr>
                    <tr><td>BROADCAST</td><td>Application for Student Exchange Program 2025</td></tr>
                    <tr><td>BROADCAST</td><td>Call for Papers - International Conference on Machine Learning 2024</td></tr>
                    <tr><td>BROADCAST</td><td>Campus Internship Program - Apply Now</td></tr>
                    <tr><td>BROADCAST</td><td>National Coding Championship 2024</td></tr>
                    <tr><td>BROADCAST</td><td>Workshop on Artificial Intelligence - Register Today</td></tr>
                    <tr><td>BROADCAST</td><td>Environmental Sustainability Awareness Week</td></tr>
                    <tr><td>BROADCAST</td><td>Innovative Design Contest 2024 - Participate Now</td></tr>
                    <tr><td>BROADCAST</td><td>Summer Internship Program - Open for Applications</td></tr>
                    <tr><td>BROADCAST</td><td>Annual Sports Day - Event Details</td></tr>
                    <tr><td>BROADCAST</td><td>Guest Lecture on Blockchain Technology</td></tr>
                    <tr><td>BROADCAST</td><td>Volunteer Opportunity for Community Development Project</td></tr>
                </table>
            </div>
        </div>
    </div>
</body>
</html>
