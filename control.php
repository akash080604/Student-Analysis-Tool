<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Details</title>
    <link rel="stylesheet" href="styles.css"> <!-- Link to an external stylesheet -->
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
        .logout-btn, .datesheet-btn {
            position: absolute;
            top: 20px;
            color: white;
            border: none;
            padding: 8px 12px;
            text-align: center;
            text-decoration: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .logout-btn {
            right: 20px;
            background-color: #f44336;
        }
        .logout-btn:hover {
            background-color: #d32f2f;
        }
        .datesheet-btn {
            left: 20px;
            background-color: #4CAF50;
        }
        .datesheet-btn:hover {
            background-color: #45a049;
        }
        .content-box {
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            padding: 10px;
            border: 1px solid #ddd;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        .add-btn {
            background-color: #4CAF50;
            color: white;
            border: none;
            padding: 8px 12px;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            border-radius: 5px;
            cursor: pointer;
        }
        .add-btn:hover {
            background-color: #45a049;
        }
        .add-btn {
    background-color: #4CAF50;
    color: white;
    border: none;
    padding: 8px 12px;
    text-align: center;
    text-decoration: none;
    display: inline-block;
    border-radius: 5px;
    cursor: pointer;
}
.add-btn:hover {
    background-color: #45a049;
}

    </style>
</head>
<body>
    <header>
        <h1>Student Details</h1>
        <!-- Add Datesheet button -->
        <a href="datesheet.php" class="datesheet-btn">Add Datesheet</a>
        <a href="upload_material.php" class="add-btn">Upload Material</a>
        <!-- Logout button -->
        <form action="admin.html" method="post" style="display: inline;">
            <button type="submit" class="logout-btn">Logout</button>
        </form>
    </header>
    
    <main>
        <div class="content-box"> <!-- Content box for the table -->
            <table>
                <thead>
                    <tr>
                        <th>Email</th>
                        <th>Name</th>
                        <th>ID</th>
                        <th>D.O.B</th>
                        <th>Gender</th>
                        <th>Action</th> <!-- Header for action buttons -->
                    </tr>
                </thead>
                <tbody>
                <?php
                session_start(); // Start the session

                // Include the database connection
                include 'connect.php';

                try {
                    // Fetch all students' details from the database
                    $stmt = $pdo->query("SELECT email, name, ID, dob, gender FROM student");
                    $students = $stmt->fetchAll(PDO::FETCH_ASSOC);

                    if ($students) {
                        // Loop through the student records and display them in the table
                        foreach ($students as $student) {
                            echo "<tr>";
                            echo "<td>" . htmlspecialchars($student['email']) . "</td>";
                            echo "<td>" . htmlspecialchars($student['name']) . "</td>";
                            echo "<td>" . htmlspecialchars($student['ID']) . "</td>";
                            echo "<td>" . htmlspecialchars($student['dob']) . "</td>";
                            echo "<td>" . htmlspecialchars($student['gender']) . "</td>";
                            echo "<td>";
                            echo "<a href='add_marks.php?id=" . urlencode($student['ID']) . "&student_email=" . urlencode($student['email']) . "' class='add-btn'>Add Marks</a> ";
                            echo "<a href='add_attendance.php?id=" . urlencode($student['ID']) . "&student_email=" . urlencode($student['email']) . "' class='add-btn'>Add Attendance</a> ";
                            echo "<a href='add_timetable.php?id=" . urlencode($student['ID']) . "&student_email=" . urlencode($student['email']) . "' class='add-btn'>Add Timetable</a> ";
                            echo "<a href='update_timetable.php?id=" . urlencode($student['ID']) . "&student_email=" . urlencode($student['email']) . "' class='add-btn'>Update Timetable</a>";
                            echo "</td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='6'>No students found.</td></tr>";
                    }
                } catch (PDOException $e) {
                    echo "<tr><td colspan='6'>Error: " . htmlspecialchars($e->getMessage()) . "</td></tr>";
                }
                ?>
                </tbody>
            </table>
        </div> <!-- End of content box -->
    </main>
</body>
</html>
