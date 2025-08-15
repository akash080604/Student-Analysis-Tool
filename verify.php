<?php
session_start();

if (!isset($_SESSION['otp'])) {
    echo "<script>alert('OTP not found.'); window.location.href='otp.php';</script>";
    exit();
}

$enteredOtp = $_POST['otp'];

if ($enteredOtp == $_SESSION['otp']) {
    echo "OTP verified successfully!";
    // Redirect to index.php
    header("Location: index.php");
    exit(); // Terminate the script after redirection
} else {
    // Show error in an alert box and redirect to otp.php
    echo "<script>alert('Invalid OTP.'); window.location.href='otp.php';</script>";
    exit();
}
?>
