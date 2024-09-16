<?php
# Check if user is not logged in
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== TRUE) {
    # Check if the current page is not the login page to avoid a loop
    if (basename($_SERVER["PHP_SELF"]) !== "login.php") {
        echo "<script>" . "window.location.href='" . APP_URL . "/session/login.php';" . "</script>";
        exit;
    }
}

# Check if the current page is the login page to avoid a loop
if (
    isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === TRUE
    && (basename($_SERVER["PHP_SELF"]) === "login.php" || basename($_SERVER["PHP_SELF"]) === "index.php" || $_SERVER["PHP_SELF"] === APP_URL . "/" || pathinfo($_SERVER["PHP_SELF"])["extension"] == "")
) {
    header("Location: " . APP_URL . "/dashboard.php");
    exit;
}