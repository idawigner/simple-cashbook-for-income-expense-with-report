<?php
include_once APP_ROOT . '/session/session_check.php';
include APP_ROOT . '/db/conn.php';
global $conn;

$pageTitlePrefix = "Dashboard";
$faviconPath = APP_URL . "/assets/img/uploads/dashboard-logo.svg";

include APP_ROOT . '/includes/header.php';
include APP_ROOT . '/includes/menu.php';
include APP_ROOT . '/includes/sidebar.php';
?>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0" style="color: #1A9DFC; font-weight: bold;">DASHBOARD</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?php echo APP_URL; ?>">Home</a></li>
                        <li class="breadcrumb-item active">Dashboard</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <section class="content">
        <div class="dashboard-area wide-dashboard-area">
            <div class="container-fluid">
                <!-- Cards Container -->
                <div class="row">

                    <div class="col-lg-3 col-6">
                        <!-- small box -->
                        <div class="small-box custom-box">
                            <div class="inner">
                                <h3>15,000</h3>
                                <p>Income</p>
                            </div>
                            <div class="icon">
                                <i class="fa-solid fa-rug"></i>
                            </div>
                            <a href="#" class="small-box-footer custom-link">More info <i
                                    class="fas fa-arrow-circle-right"></i></a>
                        </div>
                    </div>
                    <!-- ./col -->

                    <div class="col-lg-3 col-6">
                        <!-- small box -->
                        <div class="small-box custom-box">
                            <div class="inner">
                                <h3>12,000</h3>
                                <p>Expense</p>
                            </div>
                            <div class="icon">
                                <i class="fa-solid fa-sitemap"></i>
                            </div>
                            <a href="#" class="small-box-footer custom-link">More info <i
                                    class="fas fa-arrow-circle-right"></i></a>
                        </div>
                    </div>
                    <!-- ./col -->

                    <div class="col-lg-3 col-6">
                        <!-- small box -->
                        <div class="small-box custom-box">
                            <div class="inner">
                                <h3>3,000</h3>
                                <p>Balance</p>
                            </div>
                            <div class="icon">
                                <i class="fa-solid fa-users"></i>
                            </div>
                            <a href="#" class="small-box-footer custom-link">More info <i
                                    class="fas fa-arrow-circle-right"></i></a>
                        </div>
                    </div>
                    <!-- ./col -->
                </div>


                <div class="row">

                    <!-- SubAccount Card -->
                    <div class="card-container col-6 col-lg-2 col-md-4">
                        <a href="<?php echo APP_URL; ?>/view/sub-accounts/sub_account.php" class="card-link">
                            <div class="card dashboard-card">
                                <div class="card-body text-center m-0">
                                    <img src="<?php echo APP_URL; ?>/assets/img/icons/ledger-icon.svg"
                                        alt="Ledger Icon" />
                                    <h5 class="card-title" style="color: #0279CE">SubAccount</h5>
                                </div>
                            </div>
                        </a>
                    </div>

                    <!-- Bill Card -->
                    <div class="card-container col-6 col-lg-2 col-md-4">
                        <a href="<?php echo APP_URL; ?>/view/bill" class="card-link">
                            <div class="card dashboard-card">
                                <div class="card-body text-center m-0">
                                    <img src="<?php echo APP_URL; ?>/assets/img/icons/plots-icon.svg" alt="Land Icon" />
                                    <h5 class="card-title" style="color: #22B2BB">Bill</h5>
                                </div>
                            </div>
                        </a>
                    </div>

                    <!-- Inventory Card -->
                    <div class="card-container col-6 col-lg-2 col-md-4">
                        <a href="<?php echo APP_URL; ?>/view/inventory/index.php" class="card-link">
                            <div class="card dashboard-card">
                                <div class="card-body text-center m-0">
                                    <img src="<?php echo APP_URL; ?>/assets/img/icons/inventory-icon.svg"
                                        alt="Inventory Icon" />
                                    <h5 class="card-title" style="color: #5166B4">Inventory</h5>
                                </div>
                            </div>
                        </a>
                    </div>

                    <!-- Daily Sheet Card -->
                    <div class="card-container col-6 col-lg-2 col-md-4">
                        <!--                        <a href="--><?php //echo APP_URL; ?>
                        <!--/view/report/daily_sheet.php" class="card-link">-->
                        <a href="#" class="card-link">
                            <div class="card dashboard-card">
                                <div class="card-body text-center m-0">
                                    <img src="<?php echo APP_URL; ?>/assets/img/icons/daily-sheet-icon.svg"
                                        alt="Icon" />
                                    <h5 class="card-title" style="color: #0279CE">Daily Sheet</h5>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
                <!-- /.row -->

            </div>
            <!-- /.container-fluid -->
        </div>
        <!-- /.Dashboard Area -->
    </section>
</div>


<?php
include APP_ROOT . '/includes/footer.php';
?>