<?php
// Include database connection
include 'connect.php';

$message = "";

// Handle file upload
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    if ($_POST['action'] === 'upload') {
        $subject = $_POST['subject'];
        $class = $_POST['class'];  // Changed from term to class

        if (isset($_FILES['file']) && $_FILES['file']['error'] === UPLOAD_ERR_OK) {
            $fileTmpPath = $_FILES['file']['tmp_name'];
            $fileName = $_FILES['file']['name'];
            $uploadDir = __DIR__ . '/uploads/';
            $destPath = $uploadDir . $fileName;

            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }

            if (move_uploaded_file($fileTmpPath, $destPath)) {
                try {
                    $sql = "INSERT INTO study_materials (subject, class, file_name, file_path) VALUES (:subject, :class, :file_name, :file_path)";
                    $stmt = $pdo->prepare($sql);
                    $stmt->execute([
                        ':subject' => $subject,
                        ':class' => $class,  // Insert class instead of term
                        ':file_name' => $fileName,
                        ':file_path' => 'uploads/' . $fileName,
                    ]);
                    $message = "<p style='color: green;'>File uploaded successfully!</p>";
                } catch (PDOException $e) {
                    $message = "<p style='color: red;'>Database error: " . htmlspecialchars($e->getMessage()) . "</p>";
                }
            } else {
                $message = "<p style='color: red;'>Error moving file to uploads directory.</p>";
            }
        } else {
            $message = "<p style='color: red;'>No file selected or file upload error.</p>";
        }
    }
}

// Handle file deletion
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'delete') {
    $id = $_POST['id'];
    try {
        $sql = "DELETE FROM study_materials WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':id' => $id]);
        $message = "<p style='color: green;'>Material deleted successfully!</p>";
    } catch (PDOException $e) {
        $message = "<p style='color: red;'>Error deleting material: " . htmlspecialchars($e->getMessage()) . "</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Manage Study Materials</title>
    <style>
        body {
            background-image: url('login.jpg');
            background-size: cover;
            font-family: Arial, sans-serif;
            color: #000; /* Set text color to black */
        }

        .container {
            margin: 50px auto;
            padding: 20px;
            background-color: #fff; /* Set background color to white */
            color: #000; /* Set text color to black */
            border-radius: 10px;
            max-width: 700px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
            background-color: white; /* Set table background to white */
            border-radius: 5px;
        }
        th, td {
            text-align: left;
            padding: 10px;
            border: 1px solid #ddd;
        }
        th {
            background: #333;
            color: #fff;
        }
        td {
            background: #f4f4f4;
        }
        .btn {
            padding: 5px 10px;
            border-radius: 5px;
            color: #fff;
            text-decoration: none;
            background: #007bff;
            margin-right: 10px;
        }
        .btn:hover {
            background: #0056b3;
        }
        .delete-btn {
            background: #dc3545;
        }
        .delete-btn:hover {
            background: #c82333;
        }
        .message {
            margin-bottom: 20px;
        }
        .form-row {
            margin-bottom: 15px;
        }
        .form-row input {
            width: 80%;
            padding: 10px;
            margin: 5px 0;
            border-radius: 5px;
        }
        .form-row button {
            width: 15%;
        }
        .action-btns {
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .back-btn {
            position: absolute;
            top: 20px;
            left: 20px;
            padding: 10px 20px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .back-btn:hover {
            background-color: #d32f2f;
        }
    </style>
</head>
<body>
<button class="back-btn" onclick="window.location.href='control.php'">Back</button>
    <div class="container">
        <h1>Manage Study Materials</h1>
        <div class="message">
            <?= $message; ?>
        </div>

        <form action="upload_material.php" method="POST" enctype="multipart/form-data">
    <input type="hidden" name="action" value="upload">
    
    <div class="form-row">
        <label for="subject">Subject:</label>
        <select name="subject" id="subject" required>
            <option value="English">English</option>
            <option value="Hindi">Hindi</option>
            <option value="Maths">Maths</option>
            <option value="SST">SST</option>
            <option value="Science">Science</option>
            <!-- Add more subjects as needed -->
        </select>
    </div>
    
    <div class="form-row">
        <label for="class">Class:</label> <!-- Changed from term to class -->
        <select name="class" id="class" required>
            <option value="6">Class 6</option>
            <option value="7">Class 7</option>
            <option value="8">Class 8</option>
            <option value="9">Class 9</option>
            <option value="10">Class 10</option>
        </select>
    </div>
    
    <div class="form-row">
        <label for="file">File:</label>
        <input type="file" name="file" id="file" required>
    </div>
    
    <div class="form-row">
        <button type="submit" class="btn">Upload Material</button>
    </div>
</form>


        <table>
            <thead>
                <tr>
                    <th>Subject</th>
                    <th>Class</th> <!-- Changed from term to class -->
                    <th>File</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                try {
                    $stmt = $pdo->query("SELECT * FROM study_materials");
                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                        echo "<tr>
                                <td>{$row['subject']}</td>
                                <td>{$row['class']}</td> <!-- Changed from term to class -->
                                <td>{$row['file_name']}</td>
                                <td class='action-btns'>
                                    <form method='POST' style='display:inline;'>
                                        <input type='hidden' name='id' value='{$row['id']}'>
                                        <input type='hidden' name='action' value='delete'>
                                        <button type='submit' class='btn delete-btn'>Delete</button>
                                    </form>
                                </td>
                              </tr>";
                    }
                } catch (PDOException $e) {
                    echo "<tr><td colspan='4'>Error: " . htmlspecialchars($e->getMessage()) . "</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</body>
</html>
