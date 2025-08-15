<?php
// otp.php
session_start(); // Start the session

// Check if the user's email is set in the session
$userEmail = isset($_SESSION['user_email']) ? $_SESSION['user_email'] : 'No email';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OTP Input</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: url('login.jpg') no-repeat center center; /* Add your background image */
            background-size: cover;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .otp-container {
            background-color: rgba(255, 255, 255, 0); /* White background with some transparency */
            backdrop-filter: blur(10px);
            padding: 40px;
            border-radius: 5px;
            text-align: center;
            box-shadow: 0px 0px 20px rgba(0, 0, 0, 0.1);
            width: 400px;
        }

        .otp-container input[type="text"] {
            width: 100%;
            height: 40px;
            font-size: 24px;
            text-align: center;
            margin: 5px;
            border: 1px solid #b3d9d9;
            border-radius: 5px;
        }

        .otp-container button {
            background-color: #7dc7c7;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            margin-top: 20px;
        }

        .otp-container button:hover {
            background-color: #66aaaa;
        }

        .otp-container a {
            display: block;
            margin-top: 10px;
            text-decoration: none;
            color: #66aaaa;
        }
    </style>
</head>
<body>
    <div class="otp-container">
        <form action="verify.php" method="POST">
            <p>Enter OTP</p>
            <p>Email: <?php echo htmlspecialchars($userEmail); ?></p> <!-- Display the email from the session -->
            <div>
                <input type="text" id="otp" name="otp" maxlength="6" required>
            </div>
            <button type="submit">Verify OTP</button>
            <a href="save.php?resend=true">Resend OTP</a>
        </form>
    </div>
</body>
</html>
