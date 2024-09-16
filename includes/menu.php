<nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
        </li>
        <li class="nav-item d-none d-sm-inline-block">
            <a href="<?php echo APP_URL; ?>/dashboard.php" class="nav-link" style="font-size: 14px">Dashboard</a>
        </li>
        <li class="nav-item d-none d-sm-inline-block">
            <a href="<?php echo APP_URL; ?>/view/cashbook" class="nav-link" style="font-size: 14px">Cash Book</a>
        </li>
    </ul>

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
        <li class="nav-item dropdown">
            <?php
            $username = htmlspecialchars($_SESSION["username"]);
            $imagePath = "";

            switch ($username) {
                case "dawigner":
                    $imagePath = APP_URL . "/assets/img/uploads/dawigner.png";
                    break;
                case "tenzsoft":
                case "adreesch":
                    $imagePath = APP_URL . "/assets/img/uploads/developer-logo.png";
                    break;
                case "admin":
                    $imagePath = APP_URL . "/assets/img/icons/admin-user-icon.svg";
                    break;
                    // Add more cases for other usernames if needed
                default:
                    $imagePath = APP_URL . "/assets/img/default-user-icon.png";
                    break;
            }
            ?>
            <a class="nav-link dropdown-toggle d-flex align-items-center" href="" id="navbarDropdownMenuLink"
                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <img src="<?php echo $imagePath; ?>" style="margin: 0 10px; border-radius: 50%" width="30" height="30">
                <span class="no-icon"><?= $username; ?></span>
            </a>
            <div class="dropdown-menu mt-2 ml-3" aria-labelledby="navbarDropdownMenuLink">
                <a class="dropdown-item" href="<?php echo APP_URL; ?>/session/logout.php">Logout</a>
            </div>
        </li>

        <!-- Full Screen Toggle -->
        <li class="nav-item">
            <a class="nav-link" data-widget="fullscreen" href="#" role="button">
                <i class="fas fa-expand-arrows-alt"></i>
            </a>
        </li>

    </ul>
</nav>