<?php
session_start();
include 'connect.php';

$datesheet_entries = [];
$terms = ['Term 1', 'Term 2', 'Term 3', 'Term 4'];

// Fetch datesheet entries for each term
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
    <title>View Datesheet</title>
    <style>
        body {
            background-image: url('main.jpg');
            background-size: cover;
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }

        /* Fixed "ALL THE BEST!" Box at the Top */
        .message-container {
            top: 0;
            left: 0;
            width: 100%;
            background-color: white;
            padding: 15px;
            text-align: center;
            z-index: 1000;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        /* Style for the "ALL THE BEST!" text */
        .message-container span {
            font-size: 40px;
            font-weight: bold;
            color: black; /* Set the color to black */
        }

        /* Content Box Styling */
        .content-box {
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            margin-top: 40px; /* Adjusted top margin to avoid overlap */
        }

        h2 {
            text-align: center;
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

        .term-heading {
            background-color: #4CAF50;
            color: white;
            padding: 10px;
            text-align: center;
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
<div class="message-container">
    <span>BEST OF LUCK</span>
</div>
<button class="back-btn" onclick="window.location.href='index.php'">Back</button>
<div class="content-box">
    <h2>Exam Datesheet</h2>

    <?php foreach ($datesheet_entries as $term => $entries): ?>
        <?php if (count($entries) > 0): ?>
            <div class="term-heading">
                <h3><?php echo $term; ?></h3>
            </div>
            <table>
                <tr>
                    <th>Subject</th>
                    <th>Exam Date</th>
                </tr>
                <?php foreach ($entries as $entry): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($entry['subject']); ?></td>
                        <td><?php echo htmlspecialchars($entry['date']); ?></td>
                    </tr>
                <?php endforeach; ?>
            </table>
            <br>
        <?php else: ?>
            <p>No datesheet available for <?php echo $term; ?>.</p>
        <?php endif; ?>
    <?php endforeach; ?>

</div>

</body>
</html>
