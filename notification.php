<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php'; // Load PHPMailer

// Function to send a notification email
function sendNotification($studentEmail, $studentID, $term, $percentage) {
    $mail = new PHPMailer(true);

    try {
        // Server settings
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'studentanalysistools@gmail.com'; // Your Gmail address
        $mail->Password = 'oxph vgsa tbhf fbig'; // Your app password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        // Recipient
        $mail->setFrom('studentanalysistools@gmail.com', 'Admin');
        $mail->addAddress($studentEmail); // Add recipient

        // Content
        $mail->isHTML(true);
        $mail->Subject = 'Marks Added Notification';
        $mail->Body = '
            <h3>Dear Student,</h3>
            <p>Your marks for Student ID: ' . htmlspecialchars($studentID) . ' have been added for <strong>' . htmlspecialchars($term) . '.</p>
            <p>Your percentage for this term is: <strong>' . number_format($percentage, 2) . '%</strong>.</p>
            <p>Please log in to your account to view your detailed marks.</p>
            <p>Thank You!<p>
            <br>
            <p>Best regards,<br>Student Analysis Tools Team</p>
        ';

        // Send email
        $mail->send();
        return "Notification sent successfully.";
    } catch (Exception $e) {
        return "Notification could not be sent. Error: {$mail->ErrorInfo}";
    }
}
?>
