<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/phpmailer/phpmailer/src/Exception.php';
require 'vendor/phpmailer/phpmailer/src/PHPMailer.php';
require 'vendor/phpmailer/phpmailer/src/SMTP.php';

session_start();

if (!isset($_SESSION['user_email'])) {
    echo "No email found in session.";
    exit();
}

$email = $_SESSION['user_email'];
$otp = rand(100000, 999999);
$_SESSION['otp'] = $otp;

$mail = new PHPMailer(true);

try {
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'studentanalysistools@gmail.com'; // Your Gmail address
    $mail->Password = 'oxph vgsa tbhf fbig'; // Your app password
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port = 587;

    $mail->setFrom('studentanalysistools@gmail.com', 'Admin');
    $mail->addAddress($email);
    $mail->Subject = 'Student Analysis Tool OTP Code';
    $mail->Body = 'Your OTP to log into the Student Analysis Tool account is: ' . $otp;

    if ($mail->send()) {
        header("Location: otp.php");
        exit();
    } else {
        echo "Failed to send OTP.";
    }
} catch (Exception $e) {
    echo "Mailer Error: {$mail->ErrorInfo}";
}
