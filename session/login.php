<?php
include_once APP_ROOT . '/session/session_check.php';
global $conn;

# Include connection
require_once APP_ROOT . '/db/conn.php';

# Define variables and initialize with empty values
$user_login_err = $user_password_err = $login_err = "";
$user_login = $user_password = "";

# Processing form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (empty(trim($_POST["user_login"]))) {
        $user_login_err = "Please enter your username or an email id.";
    } else {
        $user_login = trim($_POST["user_login"]);
    }

    if (empty(trim($_POST["user_password"]))) {
        $user_password_err = "Please enter your password.";
    } else {
        $user_password = trim($_POST["user_password"]);
    }

    # Validate credentials
    if (empty($user_login_err) && empty($user_password_err)) {
        # Prepare a select statement
        $sql = "SELECT id, username, password FROM user WHERE username = ? OR email = ?";

        if ($stmt = mysqli_prepare($conn, $sql)) {
            # Bind variables to the statement as parameters
            mysqli_stmt_bind_param($stmt, "ss", $param_user_login, $param_user_login);

            # Set parameters
            $param_user_login = $user_login;

            # Execute the statement
            if (mysqli_stmt_execute($stmt)) {
                # Store result
                mysqli_stmt_store_result($stmt);

                # Check if user exists, If yes then verify password
                if (mysqli_stmt_num_rows($stmt) == 1) {
                    # Bind values in result to variables
                    mysqli_stmt_bind_result($stmt, $id, $username, $hashed_password);

                    if (mysqli_stmt_fetch($stmt)) {
                        # Check if password is correct
                        if (password_verify($user_password, $hashed_password)) {

                            # Store data in session variables
                            $_SESSION["id"] = $id;
                            $_SESSION["username"] = $username;
                            $_SESSION["loggedin"] = TRUE;

                            # Redirect user to index page
                            echo "<script>" . "window.location.href='../dashboard.php'" . "</script>";
                            exit;
                        } else {
                            # If password is incorrect show an error message
                            $login_err = "The email or password you entered is incorrect.";
                        }
                    }
                } else {
                    # If user doesn't exists show an error message
                    $login_err = "Invalid username or password.";
                }
            } else {
                echo "<script>" . "alert('Oops! Something went wrong. Please try again later.');" . "</script>";
                echo "<script>" . "window.location.href='./login.php'" . "</script>";
                exit;
            }

            # Close statement
            mysqli_stmt_close($stmt);
        }
    }

    # Close connection
    mysqli_close($conn);
}

$pageTitlePrefix = "Login";
$faviconPath = APP_URL . "/assets/img/icons/lock-icon.svg";

include APP_ROOT . '/includes/header.php';
?>

<body>
    <div class="content-wrapper login-wrapper">

        <div class="content">
            <?php
            if (!empty($login_err)) {
                echo "<div class='alert alert-danger'>" . $login_err . "</div>";
            }
            ?>
            <div class="login-form">
                <div class="title"><span>Login</span></div>
                <form action="<?= htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" novalidate>
                    <div class="row d-flex justify-content-start">
                        <i class="fas fa-user"></i>
                        <input type="text" class="form-control" name="user_login" id="user_login"
                            value="<?= $user_login; ?>">
                        <small class="text-danger"><?= $user_login_err; ?></small>
                    </div>

                    <div class="row d-flex justify-content-start mt-4">
                        <i class="fas fa-lock"></i>
                        <input type="password" class="form-control" name="user_password" id="password">
                        <small class="text-danger"><?= $user_password_err; ?></small>
                    </div>

                    <div class="mb-3 form-check">
                        <input type="checkbox" class="form-check-input" id="togglePassword"
                            onclick="togglePasswordVisibility()">
                        <label for="togglePassword" class="form-check-label">Show Password</label>
                    </div>
                    <!--                <div class="forgetpass d-flex justify-content-end"><a href="">Forgot password?</a></div>-->

                    <div class="row button mt-5">
                        <input type="submit" name="submit" value="Log In">
                    </div>

                    <!--    <div class="signup-link">Not a member? <a href="#">Signup now</a></div>-->
                </form>
            </div>
        </div>
    </div>

    <script>
    function togglePasswordVisibility() {
        var passwordInput = document.getElementById("password");
        if (passwordInput.type === "password") {
            passwordInput.type = "text";
        } else {
            passwordInput.type = "password";
        }
    }
    </script>

</body>

</html>