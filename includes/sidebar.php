<?php
// Get the current page URL
$current_page = $_SERVER['REQUEST_URI'];

// Define a function to check if a page is active
function isActive($page)
{
    global $current_page;
    return strpos($current_page, $page) !== false ? 'active' : '';
}
?>

<aside class="main-sidebar plot-sidebar sidebar-light-primary elevation-3">
    <!-- Brand Logo -->
    <a href="https://dawigner.com" target="_blank" class="brand-link">
        <img src="<?php echo APP_URL; ?>/assets/img/uploads/developer-logo.png" alt="Developer Logo"
            class="brand-image img-circle elevation-3" style="opacity: .8">
        <span class="brand-text font-weight-light"><strong>TENZ SOFT</strong></span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar user panel (optional) -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
                <img src="<?php echo APP_URL; ?>/assets/img/uploads/dashboard-logo.svg" alt="Brand Logo">
            </div>
            <div class="info">
                <a href="<?php echo APP_URL; ?>" class="d-block">CashFlow App</a>
            </div>
        </div>

        <!-- SidebarSearch Form -->
        <div class="form-inline">
            <div class="input-group" data-widget="sidebar-search">
                <input class="form-control form-control-sidebar" type="search" placeholder="Search" aria-label="Search">
                <div class="input-group-append">
                    <button class="btn btn-sidebar">
                        <i class="fas fa-search fa-fw"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">


                <!-- SubAccount -->
                <li
                    class="nav-item <?php echo (strpos($current_page, '/view/cashbook') !== false) ? 'menu-open' : ''; ?>">
                    <a href="<?php echo APP_URL; ?>/view/cashbook" class="nav-link sidebar-nav-link">
                        <img src="<?php echo APP_URL; ?>/assets/img/icons/cash-book-icon.svg" alt="Ledger Icon" />
                        <p>
                            Cash Book
                        </p>
                    </a>
                </li>

                <!-- Report -->
                <li
                    class="nav-item <?php echo (strpos($current_page, '/view/report') !== false) ? 'menu-open' : ''; ?>">
                    <a href="<?php echo APP_URL; ?>/view/report"
                        class="nav-link sidebar-nav-link <?php echo isActive('/view/report'); ?>">
                        <img src="<?php echo APP_URL; ?>/assets/img/icons/daily-sheet-icon.svg" alt="Ledger Icon" />
                        <p>
                            Report
                        </p>
                    </a>
                </li>

            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>