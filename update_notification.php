<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Load Composer's autoloader for PHPMailer
require 'vendor/autoload.php'; // Ensure you've installed PHPMailer via Composer

function sendUpdateNotification($studentEmail, $studentID, $term, $percentage) {
    $mail = new PHPMailer(true); // Create a new PHPMailer instance

    try {
        // Server settings
        $mail->isSMTP(); // Set mailer to use SMTP
        $mail->Host = 'smtp.gmail.com'; // Gmail SMTP server
        $mail->SMTPAuth = true; // Enable SMTP authentication
        $mail->Username = 'studentanalysistools@gmail.com'; // Your Gmail address
        $mail->Password = 'oxph vgsa tbhf fbig'; // Your app password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; // Enable TLS encryption
        $mail->Port = 587; // TCP port to connect to

        // Recipients
        $mail->setFrom('studentanalysistools@gmail.com', 'Admin'); // Sender
        $mail->addAddress($studentEmail); // Add a recipient

        // Content
        $mail->isHTML(true); // Set email format to HTML
        $mail->Subject = 'Marks Updated Successfully';
        $mail->Body = "<p>Dear Student (ID: $studentID),</p>
                       <p>Your marks for Term: <strong>$term</strong> have been updated successfully.</p>
                       <p>Your updated percentage is: <strong>" . number_format($percentage, 2) . "%</strong>.</p>
                       <p>Please log in to your account to view your detailed marks.</p>
                       <p>Thank you!</p>
                       <br>
                       <p>Best regards,<br>Student Analysis Tools Team</p>";

        // Send the email
        $mail->send();
        return 'Notification sent successfully.';
    } catch (Exception $e) {
        return "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
}
?>
