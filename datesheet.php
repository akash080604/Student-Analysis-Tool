<?php
session_start();
include 'connect.php';

$success_message = '';
$error_message = '';

// Handle form submission for adding/updating a datesheet entry
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if we are adding/updating a datesheet
    if (isset($_POST['term'], $_POST['subject'], $_POST['exam_date'])) {
        $term = htmlspecialchars($_POST['term']);
        $subject = htmlspecialchars($_POST['subject']);
        $exam_date = htmlspecialchars($_POST['exam_date']);
        $datesheet_id = isset($_POST['datesheet_id']) ? $_POST['datesheet_id'] : null;

        if (!empty($term) && !empty($subject) && !empty($exam_date)) {
            try {
                // Check if the exam date already exists for the same term and subject
                $stmt = $pdo->prepare("SELECT * FROM datesheet WHERE term = :term AND subject = :subject AND date = :date");
                $stmt->bindParam(':term', $term);
                $stmt->bindParam(':subject', $subject);
                $stmt->bindParam(':date', $exam_date);
                $stmt->execute();

                if ($stmt->rowCount() > 0) {
                    $error_message = "The exam is already scheduled on this date for the selected subject. Please choose another date.";
                } else {
                    if ($datesheet_id) {
                        // Update an existing entry
                        $stmt = $pdo->prepare("UPDATE datesheet SET term = :term, subject = :subject, date = :date WHERE id = :id");
                        $stmt->bindParam(':id', $datesheet_id);
                    } else {
                        // Insert a new entry
                        $stmt = $pdo->prepare("INSERT INTO datesheet (term, subject, date) VALUES (:term, :subject, :date)");
                    }

                    $stmt->bindParam(':term', $term);
                    $stmt->bindParam(':subject', $subject);
                    $stmt->bindParam(':date', $exam_date);

                    if ($stmt->execute()) {
                        $success_message = $datesheet_id ? "Datesheet entry updated successfully!" : "Datesheet entry added successfully!";
                    } else {
                        $error_message = "Failed to add/update datesheet entry. Please try again.";
                    }
                }
            } catch (PDOException $e) {
                $error_message = "Error: " . $e->getMessage();
            }
        } else {
            $error_message = "All fields are required.";
        }
    }

    // Handle delete action
    if (isset($_POST['delete_id'])) {
        $delete_id = $_POST['delete_id'];
        try {
            $stmt = $pdo->prepare("DELETE FROM datesheet WHERE id = :id");
            $stmt->bindParam(':id', $delete_id);
            if ($stmt->execute()) {
                $success_message = "Datesheet entry deleted successfully!";
            } else {
                $error_message = "Failed to delete datesheet entry. Please try again.";
            }
        } catch (PDOException $e) {
            $error_message = "Error: " . $e->getMessage();
        }
    }
}

// Fetch datesheet entries for each term
$terms = ['Term 1', 'Term 2', 'Term 3', 'Term 4'];
$datesheet_entries = [];

foreach ($terms as $term) {
    try {
        $stmt = $pdo->prepare("SELECT * FROM datesheet WHERE term = :term ORDER BY date");
        $stmt->bindParam(':term', $term);
        $stmt->execute();
        $datesheet_entries[$term] = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        $error_message = "Error: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Datesheet</title>
    <style>
        /* Same styling as before */
        body {
            background-image: url('login.jpg');
            background-size: cover;
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
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
        .add-btn, .update-btn, .delete-btn {
            color: white;
            border: none;
            padding: 8px 12px;
            border-radius: 5px;
            cursor: pointer;
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
        .add-btn { background-color: #4CAF50; }
        .update-btn { background-color: #2196F3; }
        .delete-btn { background-color: #f44336; }
        .add-btn:hover { background-color: #45a049; }
        .update-btn:hover { background-color: #1e88e5; }
        .delete-btn:hover { background-color: #d32f2f; }
        .message { margin: 10px 0; padding: 10px; border-radius: 5px; }
        .success { background-color: #dff0d8; color: #3c763d; }
        .error { background-color: #f2dede; color: #a94442; }
    </style>
</head>
<body>
<button class="back-btn" onclick="window.location.href='control.php'">Back</button>

<div class="content-box">
    <h2>Manage Exam Datesheet</h2>

    <?php if (!empty($success_message)) echo "<p class='message success'>$success_message</p>"; ?>
    <?php if (!empty($error_message)) echo "<p class='message error'>$error_message</p>"; ?>

    <form method="post" action="">
        <div>
            <label for="term">Term:</label>
            <select name="term" id="term" required>
                <option value="">Select Term</option>
                <option value="Term 1">Term 1</option>
                <option value="Term 2">Term 2</option>
                <option value="Term 3">Term 3</option>
                <option value="Term 4">Term 4</option>
            </select>
        </div>

        <div>
            <label for="subject">Subject:</label>
            <select name="subject" id="subject" required>
                <option value="">Select Subject</option>
                <option value="English">English</option>
                <option value="Hindi">Hindi</option>
                <option value="SST">SST</option>
                <option value="Science">Science</option>
                <option value="Maths">Maths</option>
            </select>
        </div>

        <div>
            <label for="exam_date">Date:</label>
            <input type="date" name="exam_date" id="exam_date" required>
        </div>

        <input type="hidden" name="datesheet_id" id="datesheet_id">
        
        <button type="submit" class="add-btn" id="save-btn">Save Datesheet</button>
    </form>

    <h3>Term-wise Datesheet Entries</h3>
    <?php foreach ($datesheet_entries as $term => $entries): ?>
        <?php if (count($entries) > 0): ?>
            <h4><?php echo $term; ?></h4>
            <table>
                <tr>
                    <th>Subject</th>
                    <th>Date</th>
                    <th>Actions</th>
                </tr>
                <?php foreach ($entries as $entry): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($entry['subject']); ?></td>
                        <td><?php echo htmlspecialchars($entry['date']); ?></td>
                        <td>
                            <button class="update-btn" onclick="editDatesheet(<?php echo htmlspecialchars(json_encode($entry)); ?>)">Edit</button>
                            <form method="post" action="" style="display:inline;">
                                <input type="hidden" name="delete_id" value="<?php echo htmlspecialchars($entry['id']); ?>">
                                <button type="submit" class="delete-btn" onclick="return confirm('Are you sure you want to delete this entry?')">Delete</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </table>
        <?php endif; ?>
    <?php endforeach; ?>
</div>

<script>
function editDatesheet(entry) {
    document.getElementById('term').value = entry.term;
    document.getElementById('subject').value = entry.subject;
    document.getElementById('exam_date').value = entry.date;
    document.getElementById('datesheet_id').value = entry.id;
    document.getElementById('save-btn').innerText = 'Update Datesheet';
}
</script>

</body>
</html>
