<?php
# Initialize the session
session_start();

# Unset all session variables
$_SESSION = array();

# Destroy the session
session_destroy();

# Redirect to login page
echo "<script>" . "window.location.href='" . APP_URL . "/session/login.php';" . "</script>";
exit;
