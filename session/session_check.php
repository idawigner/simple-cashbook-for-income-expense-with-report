<?php
# Start the session if not already started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

# Check if the file is included for the first time
if (!isset($_SESSION['session_check_included'])) {
    # Mark that the file is included
    $_SESSION['session_check_included'] = true;

    # Check if user is not logged in and not on login.php
    if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== TRUE) {
        # Check if the current page is not the login page to avoid a loop
        if (basename($_SERVER["PHP_SELF"]) !== "login.php") {
            header("Location: " . APP_URL . "/session/login.php");
            exit;
        }
    }

    # Check if user is logged in and on login.php, the root index file, or the root URL
    if (
        isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === TRUE
        && (basename($_SERVER["PHP_SELF"]) === "login.php" || basename($_SERVER["PHP_SELF"]) === "index.php" || $_SERVER["PHP_SELF"] === APP_URL . "/" || pathinfo($_SERVER["PHP_SELF"])["extension"] == "")
    ) {
        header("Location: " . APP_URL . "/dashboard.php");
        exit;
    }
} else {
    # If the file is included again, perform only the initial check for logged-in status

    # Check if user is not logged in and not on login.php
    if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== TRUE) {
        # Check if the current page is not the login page to avoid a loop
        if (basename($_SERVER["PHP_SELF"]) !== "login.php") {
            header("Location: " . APP_URL . "/session/login.php");
            exit;
        }
    }

    # Check if user is logged in and navigating to a specific file, not login.php, index.php, or the root URL
    if (
        isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === TRUE
        && basename($_SERVER["PHP_SELF"]) !== "login.php"
        && basename($_SERVER["PHP_SELF"]) !== "index.php"
        && $_SERVER["PHP_SELF"] !== APP_URL . "/"
        && pathinfo($_SERVER["PHP_SELF"])["extension"] != ""
    ) {
        # No redirection needed, user stays on the requested page
    }
}
