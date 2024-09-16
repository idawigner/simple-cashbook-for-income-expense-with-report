<?php
include_once APP_ROOT . '/session/session_check.php';
include_once APP_ROOT . '/includes/custom_functions.php';
include APP_ROOT . '/db/conn.php';
global $conn;

// $filterType = $_POST['filterType'] ?? 'All';

$pageTitlePrefix = "Report";
$faviconPath = APP_URL . "/assets/img/icons/daily-sheet-icon.svg";

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
                    <h1 class="m-0" style="color: #1A9DFC;">Report</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?php echo APP_URL; ?>">Home</a></li>
                        <li class="breadcrumb-item active">Report</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <section class="content">
        <div class="dashboard-area wide-dashboard-area">
            <div class="container-fluid">
                <!-- Date Filter & New record Button -->
                <div class="row mb-4">
                    <div class="col-md-8 mb-3 text-center text-md-left">
                        <form action="" method="post" class="p-0">
                            <label for="from">From</label>
                            <input type="text" id="from" name="from" style="width: 125px; padding-left: 7px;"
                                value="<?php echo $_POST['from'] ?? date('m/1/Y'); ?>" required>
                            <label for="to">to</label>
                            <input type="text" id="to" name="to" style="width: 125px; padding-left: 7px;"
                                value="<?php echo $_POST['to'] ?? date('m/t/Y'); ?>" required>

                            <!-- <label for="filterType">Exp-Type</label>
                            <select name="filterType" id="filterType" style="width: 75px; padding-left: 2px;">
                                <option value="All"
                                    <?php if (isset($_POST['filterType']) && $_POST['filterType'] == 'All') echo 'selected'; ?>>
                                    All</option>
                                <option value="Office"
                                    <?php if (isset($_POST['filterType']) && $_POST['filterType'] == 'Office') echo 'selected'; ?>>
                                    Office</option>
                                <option value="Personal"
                                    <?php if (isset($_POST['filterType']) && $_POST['filterType'] == 'Personal') echo 'selected'; ?>>
                                    Personal</option>
                            </select> -->

                            <input class="btn btn-file text-primary" type="submit" name="submit" value="Filter">
                        </form>
                    </div>

                    <!-- Previous Balance Field -->
                    <div class="col-md-4 text-center text-md-right">
                        <?php
                        echo '<label for="prevBalance" class="mr-2">Previous Closing:</label>';

                        // Check if "From" and "To" values are set
                        if (isset($_POST['from']) && isset($_POST['to'])) {
                            // Get "From" and "To" values from the form data
                            $from = $_POST['from'];
                            $to = $_POST['to'];

                            // Convert date format to match the database format
                            $from = date('Y-m-d', strtotime($from));
                            $to = date('Y-m-d', strtotime($to));
                        } else {
                            // Set the current date
                            $from = date('Y-m-01'); // First day of the current month
                            $to = date('Y-m-t'); // Last day of the current month
                        }
                        
                        // Start building the SQL query
                        $sql = "SELECT * FROM income_expense WHERE reporting = 'yes' AND date BETWEEN '$from' AND '$to'";

                        // Fetch and calculate previous balance
                        $result = mysqli_query($conn, $sql);
                        $prevTotalCredit = 0;
                        $prevTotalDebit = 0;

                        while ($row = mysqli_fetch_assoc($result)) {
                            $prevTotalCredit += $row['credit'];
                            $prevTotalDebit += $row['debit'];
                        }

                        $prevBalance = $prevTotalCredit - $prevTotalDebit;
                        echo '<input type="text" id="prevBalance" name="prevBalance" readonly value="' . thSeparator($prevBalance) . '" style="width: 150px; padding-left: 7px;">';
                        ?>
                    </div>

                    <!-- Print Sheet Button -->
                    <!-- <div class="mb-3 col-md-1 text-center text-md-right">
                        <?php
                        $pdfLink = APP_URL . '/view/report/print.php?ACTION=VIEW';
                        $pdfLink .= '&from=' . urlencode(isset($_POST['from']) ? $_POST['from'] : date('m/d/Y'));
                        $pdfLink .= '&to=' . urlencode(isset($_POST['to']) ? $_POST['to'] : date('m/d/Y'));
                        // $pdfLink .= '&type=' . urlencode(isset($_POST['filterType']) ? $_POST['filterType'] : 'All');
                        $pdfLink .= '&prevBal=' . urlencode(isset($prevBalance) ? $prevBalance : '0');
                        ?>
                        <a class="new-record-button" target="_blank" href="<?php echo $pdfLink; ?>">
                            <span class="new-record-button-text">Print</span>
                        </a>
                    </div> -->
                </div>

                <!-- Display Data on Report Page -->
                <div class="row mt-4">
                    <div class="col-lg-12">
                        <table class="table table-bordered" id="ReportTable">
                            <thead>
                                <tr>
                                    <th>Sr.</th>
                                    <th>Details</th>
                                    <th>Income</th>
                                    <th>Expense</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                // Check if "From" and "To" values are set
                                if (isset($_POST['from']) && isset($_POST['to'])) {
                                    // Get "From" and "To" values from the form data
                                    $from = $_POST['from'];
                                    $to = $_POST['to'];

                                    // Convert date format
                                    $from = date('Y-m-d', strtotime($from));
                                    $to = date('Y-m-d', strtotime($to));
                                } else {
                                    // Set the current date
                                    $from = date('Y-m-d');
                                    $to = date('Y-m-d');
                                }

                                // Define the expense type condition based on the filter type
                                // $expenseTypeCondition = "expense_type " . ($filterType === 'All' ? "IN ('Office', 'Personal')" : "= '$filterType'");

                                // Update SQL query
                                $sql = "SELECT * FROM income_expense 
                                WHERE date BETWEEN '$from' AND '$to' 
                                AND reporting = 'yes'";
                                //AND $expenseTypeCondition";
                                $result = mysqli_query($conn, $sql);

                                $srNumber = 1;

                                // Fetch and display Ledger data
                                while ($row = mysqli_fetch_assoc($result)) {
                                    echo '<tr>';
                                    echo '<td>' . $srNumber . '</td>';
                                    echo '<td style="text-align: left;">' . $row['details'] . '</td>';
                                    echo '<td data-col="credit" style="text-align: left;">' . thSeparator($row['credit']) . '</td>';
                                    
                                    $expenseTypeBgColor = '';
                                    if(!empty($row['debit'])) {
                                        // Set background color based on expense_type
                                        $expenseTypeBgColor = ($row['expense_type'] === 'Office') ? 'green' : 'blue';
                                    }
                                    echo '<td data-col="debit">
                                            <span style="float: left;">' . thSeparator($row['debit']) . '</span>
                                            <span style="float: right; color: white; background-color:' . $expenseTypeBgColor . '; padding: 2px 5px;">' . $row['expense_type'] . '</span>
                                        </td>';
                                    echo '</tr>';
                                    
                                    $srNumber++;
                                }
                                ?>
                            </tbody>
                            <tfoot>
                                <tr class="bg-gray-light text-bold text-sm">
                                    <th colspan="2" class="text-right" style="text-align: right;">Totals</th>
                                    <td id="totalCredit" class="text-black-20 light-bg-green">0</td>
                                    <td id="totalDebit" class="text-black-20 light-bg-red">0</td>
                                </tr>
                                <tr class="text-bold text-sm">
                                    <th colspan="2" class="text-right" style="text-align: right;">Grand Total</th>
                                    <td colspan="2" class="text-black-20" id="grandTotal">0</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>

</div>

<?php
// Close the database connection
mysqli_close($conn);
include APP_ROOT . '/includes/footer.php';
?>


<script>
var prevBal = <?php echo $prevBalance ?? 0; ?>;

function updateTotals() {
    var totalDebit = 0;
    var totalCredit = 0;

    // Loop through the rows and calculate totals
    $('#ReportTable tbody tr').each(function() {
        var $row = $(this);
        var debit = parseFloat($row.find('td[data-col="debit"]').text().replace(/,/g, '')) || 0;
        var credit = parseFloat($row.find('td[data-col="credit"]').text().replace(/,/g, '')) || 0;

        totalDebit += debit;
        totalCredit += credit;
    });

    // Calculate grandTotal
    var grandTotal = prevBal + totalCredit - totalDebit;

    // Update the totals in the new row (rounded to the nearest whole number)
    $('#totalDebit').text(thSeparator(totalDebit.toFixed(0)));
    $('#totalCredit').text(thSeparator(totalCredit.toFixed(0)));
    $('#grandTotal').text(thSeparator(grandTotal.toFixed(0))); // Update grandTotal
}

$(document).ready(function() {
    // Call the function to update totals when the page is ready
    updateTotals();

    // Call the function to update totals whenever the table is redrawn
    $('#ReportTable').on('draw.dt', function() {
        updateTotals();
    });

    // Call the function to update totals whenever the column visibility changes
    $('#ReportTable').on('column-visibility.dt', function() {
        updateTotals();
    });
});


// Call the function whenever the table is redrawn (e.g., after pagination or filtering)
$('#ReportTable').on('draw.dt', function() {
    updateTotals();
});



// Save user preferences to local storage
function saveUserPreferences(length, page) {
    localStorage.setItem('tableLength', length);
    localStorage.setItem('currentPage', page);
}

// Retrieve user preferences from local storage
function getUserPreferences() {
    return {
        length: localStorage.getItem('tableLength') || 50,
        page: localStorage.getItem('currentPage') || 1,
    };
}

// Example usage:
var preferences = getUserPreferences();

// Save user preferences when changing length or page
$("#ReportTable")
    .on('length.dt', function(e, settings, len) {
        saveUserPreferences(len, settings._iDisplayStart / len + 1);
    })
    .on('page.dt', function(e, settings) {
        saveUserPreferences(settings._iDisplayLength, settings._iDisplayStart / settings._iDisplayLength + 1);
    });
</script>
</body>

</html>