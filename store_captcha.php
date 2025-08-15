<?php
session_start();
if (isset($_POST['captcha'])) {
    $_SESSION['captcha'] = $_POST['captcha']; // Store the user-submitted captcha in the session
}
?>
