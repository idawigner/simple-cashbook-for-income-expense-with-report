<?php
include_once APP_ROOT . '/session/session_check.php';
include_once APP_ROOT . '/includes/custom_functions.php';
include APP_ROOT . '/db/conn.php';
global $conn, $debitArrow, $creditArrow;


function calculatePreviousBalance($conn, $fromDate)
{
    // Convert date format to match the database format
    $fromDate = date('Y-m-d', strtotime($fromDate));

    // Retrieve the latest entry on the previous date based on both date and time
    $previousEntrySql = "SELECT balance 
                         FROM income_expense
                         WHERE date < '$fromDate'
                         ORDER BY date DESC, time DESC 
                         LIMIT 1";
    
    $previousEntryResult = mysqli_query($conn, $previousEntrySql);
    $previousEntryRow = mysqli_fetch_assoc($previousEntryResult);
    
    // Return the balance of the latest entry from the previous date
    return isset($previousEntryRow['balance']) ? $previousEntryRow['balance'] : 0;
}


$creditArrow = '⬇';
$debitArrow = '⬆';

$pageTitlePrefix = "Cash Book";
$faviconPath = APP_URL . "/assets/img/icons/cash-book-icon.svg";

include APP_ROOT . '/includes/header.php';
include APP_ROOT . '/includes/menu.php';
include APP_ROOT . '/includes/sidebar.php';
?>

<!-- Insert Cash Book Modal -->
<div class="modal fade" id="myModal">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title">New Transaction</h3>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <form id="newRecordForm" method="get">
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="date">Date:</label>
                            <input type="date" class="form-control" id="date" name="date"
                                value="<?php echo date('Y-m-d'); ?>" required>
                        </div>
                        <!-- <div class="form-group col-md-6">
                            <label for="transactionType">Transaction Type:</label>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="transactionType" id="income"
                                    value="Income" onchange="toggleFields(this.value)" checked>
                                <label class="form-check-label" for="income">Income</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="transactionType" id="expense"
                                    value="Expense" onchange="toggleFields(this.value)">
                                <label class="form-check-label" for="expense">Expense</label>
                            </div>
                        </div> -->
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-12">
                            <label for="details">Details:</label>
                            <input type="text" class="form-control" id="details" name="details"
                                placeholder="Description">
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-12">
                            <label for="credit" class="text-success">Income (Rs. <?php echo $creditArrow; ?>):</label>
                            <input type="number" class="form-control" id="credit" name="credit">
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="debit" class="text-danger">Expense (Rs. <?php echo $debitArrow; ?>):</label>
                            <input type="number" class="form-control" id="debit" name="debit">
                        </div>
                        <div class="form-group col-md-6">
                            <label for="expenseType">Expense Type</label>
                            <select class="form-control" name="expenseType" id="expenseType" required>
                                <option value="">-- Select --</option>
                                <option value="Office">Office</option>
                                <option value="Personal">Personal</option>
                            </select>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" onclick="saveRecord()">Save</button>
            </div>
        </div>
    </div>
</div>

<!-- Edit Cash Book Modal -->
<div class="edit-modals-container">
    <?php
    // Retrieve data from the 'income_expense' table
    $sql = "SELECT * FROM income_expense";
    $result = mysqli_query($conn, $sql);

    // Function to display the edit modal for Cash Book
    function displayEditModalIncome($result)
    {
        global $debitArrow, $creditArrow;
        while ($row = mysqli_fetch_assoc($result)) :
    ?>
    <!-- Edit Modal -->
    <div class="modal fade edit-modal" id="editModal<?php echo $row['id']; ?>">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title">Edit Transaction</h3>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <form id="editRecordForm<?php echo $row['id']; ?>" method="get">
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="editDate<?php echo $row['id']; ?>">Date:</label>
                                <input type="date" class="form-control" id="editDate<?php echo $row['id']; ?>"
                                    name="editDate<?php echo $row['id']; ?>" value="<?php echo $row['date']; ?>"
                                    required>
                            </div>
                            <!-- <div class="form-group col-md-6">
                                <label for="editTransactionType">Transaction Type:</label>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="editTransactionType"
                                        id="editIncome" value="Income" onchange="toggleFields(this.value)" checked>
                                    <label class="form-check-label" for="editIncome">Income</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="editTransactionType"
                                        id="editExpense" value="Expense" onchange="toggleFields(this.value)">
                                    <label class="form-check-label" for="editExpense">Expense</label>
                                </div>
                            </div> -->
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-12">
                                <label for="editDetails<?php echo $row['id']; ?>">Details:</label>
                                <input type="text" class="form-control" id="editDetails<?php echo $row['id']; ?>"
                                    name="editDetails<?php echo $row['id']; ?>" value="<?php echo $row['details']; ?>"
                                    placeholder="Description">
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-12">
                                <label for="editCredit<?php echo $row['id']; ?>" class="text-success">Income (Rs.
                                    <?php echo $creditArrow; ?>):</label>
                                <input type="number" step="1" class="form-control"
                                    id="editCredit<?php echo $row['id']; ?>" name="editCredit<?php echo $row['id']; ?>"
                                    value="<?php echo $row['credit']; ?>">
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="editDebit<?php echo $row['id']; ?>" class="text-danger">Expense (Rs.
                                    <?php echo $debitArrow; ?>):</label>
                                <input type="number" class="form-control" id="editDebit<?php echo $row['id']; ?>"
                                    name="editDebit<?php echo $row['id']; ?>" value="<?php echo $row['debit']; ?>">
                            </div>
                            <div class="form-group col-md-6">
                                <label for="editExpenseType<?php echo $row['id']; ?>">Expense Type</label>
                                <select class="form-control" name="editExpenseType<?php echo $row['id']; ?>"
                                    id="editExpenseType<?php echo $row['id']; ?>" required>
                                    <option value="">-- Select --</option>
                                    <option value="Office"
                                        <?php echo ($row['expense_type'] == 'Office') ? 'selected' : ''; ?>>Office
                                    </option>
                                    <option value="Personal"
                                        <?php echo ($row['expense_type'] == 'Personal') ? 'selected' : ''; ?>>Personal
                                    </option>
                                </select>
                            </div>
                        </div>

                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger"
                        onclick="deleteRecord(<?php echo $row['id']; ?>)">Delete</button>
                    <button type="button" class="btn btn-success"
                        onclick="updateRecord(<?php echo $row['id']; ?>)">Update</button>
                </div>
            </div>
        </div>
    </div>
    <!-- End Edit Modal -->
    <?php
        endwhile;
    }

    // Retrieve and display data for Cash Book
    $sqlLedger = "SELECT * FROM income_expense";
    $result = mysqli_query($conn, $sqlLedger);
    displayEditModalIncome($result);
    ?>
</div>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0" style="color: #1A9DFC;"><?php echo $pageTitlePrefix; ?></h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?php echo APP_URL; ?>">Home</a></li>
                        <li class="breadcrumb-item active">Cash Book</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <section class="content">
        <div class="dashboard-area wide-dashboard-area">
            <div class="container-fluid">
                <!--Date Filter & New record Button -->
                <div class="row mb-4">
                    <div class="col-md-7 mb-3 text-center text-md-left">
                        <!-- Center on mobile -->
                        <form action="" method="post" class="p-0">
                            <label for="from">From</label>
                            <input type="text" id="from" name="from" style="width: 125px; padding-left: 7px;"
                                value="<?php echo isset($_POST['from']) ? $_POST['from'] : date('m/01/Y'); ?>" required>
                            <label for="to">to</label>
                            <input type="text" id="to" name="to" style="width: 125px; padding-left: 7px;"
                                value="<?php echo isset($_POST['to']) ? $_POST['to'] : date('m/t/Y'); ?>" required>
                            <input class="btn btn-file text-primary" type="submit" name="submit" value="Filter">
                        </form>
                    </div>

                    <!-- Previous Balance Field -->
                    <div class="col-md-4 text-center text-md-right">
                        <label for="prevBalance" class="mr-2">Previous Closing:</label>
                        <?php
                        $fromDate = isset($_POST['from']) ? $_POST['from'] : date('m/01/Y');
                        $previousBalance = calculatePreviousBalance($conn, $fromDate);

                        // Display '--' if there is no previous balance
                        $prevBalanceDisplay = $previousBalance !== 0 ? thSeparator($previousBalance) : '--';
                        ?>
                        <input type="text" id="prevBalance" name="prevBalance" readonly
                            value="<?php echo $prevBalanceDisplay; ?>" style="width: 150px; padding-left: 7px;">
                    </div>

                    <div class="col-md-1 mb-3 text-center text-md-right">
                        <!-- Center on mobile -->
                        <button class="new-record-button" type="button" data-toggle="modal" data-target="#myModal"
                            onclick="">
                            <img src="<?php echo APP_URL; ?>/assets/img/icons/plus-icon.svg" alt="Plus Icon"
                                class="new-record-button-icon">
                            <span class="new-record-button-text">New</span>
                        </button>
                    </div>
                </div>

                <!-- Display Data on Cash Book Page -->

                <!-- Display Rows -->
                <div class="row mt-4">
                    <div class="col-lg-12">
                        <table class="table table-bordered" id="CashBookTable">
                            <thead>
                                <tr>
                                    <th>Sr.</th>
                                    <th>Date</th>
                                    <th style="text-align: center;">Details</th>
                                    <th>Income (Rs. <?php echo $creditArrow; ?>)</th>
                                    <th>Expense (Rs. <?php echo $debitArrow; ?>)</th>
                                    <th class="bright-bg-blue">Balance (Rs.)</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                // Check if "From" and "To" values are set
                                if (isset($_POST['from']) && isset($_POST['to'])) {
                                    // Get "From" and "To" values from the form data
                                    $from = $_POST['from'];
                                    $to = $_POST['to'];

                                    // Convert date format to match the database format
                                    $from = date('Y-m-d', strtotime($from));
                                    $to = date('Y-m-d', strtotime($to));

                                    // Update your SQL query with a WHERE clause for the date range
                                    $sql = "SELECT * FROM income_expense WHERE date BETWEEN '$from' AND '$to'";
                                } else {
                                    $startOfMonth = date('Y-m-01'); // First day of the current month
                                    $endOfMonth = date('Y-m-t'); // Last day of the current month

                                    // If "From" and "To" values are not set, retrieve this month's records
                                    $sql = "SELECT * FROM income_expense WHERE date BETWEEN '$startOfMonth' AND '$endOfMonth'";
                                }

                                // Fetch and display Cash Book data from the database
                                $result = mysqli_query($conn, $sql); // Re-run the query to get the correct result set
                                $srNumber = 1;

                                // Display Cash Book data as table rows
                                while ($row = mysqli_fetch_assoc($result)) {
                                    $isEdited = $row['edited'] === 'yes';
                                    echo '<tr>';
                                    echo '<td>' . $srNumber . '</td>';
                                    echo '<td>' . formatDate($row['date']) . '</td>';
                                    echo '<td>' . strtoupper($row['details']) . '</td>';
                                    // echo '<td>' . ($isEdited ? 'ᴱᴰᴵᵀᴱᴰ ' : '') . strtoupper($row['details']) . '</td>';

                                    // Only apply the class if 'credit' is not empty
                                    $creditClass = !empty($row['credit']) ? 'light-bg-green' : '';
                                    echo '<td data-col="credit" class="' . $creditClass . '">' . thSeparator($row['credit']) . '</td>';
                                    
                                    // Only apply the class if 'debit' is not empty
                                    $debitClass = '';
                                    $expenseTypeBgColor = '';
                                    
                                    if(!empty($row['debit'])) {
                                        $debitClass = 'light-bg-red';
                                        // Set background color based on expense_type
                                        $expenseTypeBgColor = ($row['expense_type'] === 'Office') ? 'green' : 'blue';
                                    }
                                    echo '<td data-col="debit" class="' . $debitClass . '">
                                            <span style="float: left;">' . thSeparator($row['debit']) . '</span>
                                            <span style="float: right; color: white; background-color:' . $expenseTypeBgColor . '; padding: 2px 5px;">' . $row['expense_type'] . '</span>
                                        </td>';


                                    // Balance Logic
                                    $balance = thSeparator($row['balance']);
                                    if ($balance >= 0) {
                                        echo '<td data-col="balance" style="color: #6DB60B;">' . $balance . '</td>';
                                    } else {
                                        echo '<td data-col="balance" style="color: red; background-color: rgba(235,235,235,0.75)">' . $balance . '</td>';
                                    }

                                    echo '<td>';
                                    echo '<i class="fas fa-edit text-primary mr-2" title="Edit" onclick="openEditModal(' . $row['id'] . ')"></i>';
                                    echo '</td>';
                                    echo '</tr>';

                                    $srNumber++;
                                }

                                ?>
                            </tbody>
                            <tfoot>
                                <tr class="bg-gray-light text-sm">
                                    <th colspan="3" class="text-center">Totals</th>
                                    <td id="totalCredit" class="text-black-20">0</td>
                                    <td id="totalDebit" class="text-black-20">0</td>
                                    <td id="balance" class="text-bold">0</td>
                                    <td></td>
                                </tr>
                                <tr class="text-bold text-sm">
                                    <th colspan="5" class="text-right" style="text-align: right;">Grand Total</th>
                                    <td colspan="2" class="text-black-20" id="grandTotal">0</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>

                <!-- /.container-fluid -->
            </div>
            <!-- /.Dashboard Area -->
    </section>
</div>

<?php
// Close the database connection
mysqli_close($conn);
include APP_ROOT . '/includes/footer.php';
?>

<script>
var prevBal = <?php echo $previousBalance ?? 0; ?>;
// Calculate and update totals
function updateTotals() {
    var totalDebit = 0;
    var totalCredit = 0;
    var balance = 0;

    // Loop through the rows and calculate totals
    $('#CashBookTable tbody tr').each(function() {
        var $row = $(this);
        var credit = parseFloat($row.find('td[data-col="credit"]').text().replace(/,/g, '')) || 0;
        var debit = parseFloat($row.find('td[data-col="debit"]').text().replace(/,/g, '')) || 0;

        totalCredit += credit;
        totalDebit += debit;
        balance = totalCredit - totalDebit;
    });

    // Calculate grandTotal
    var grandTotal = totalCredit - totalDebit + prevBal;

    // Update the totals in the new row (rounded to the nearest whole number)
    $('#totalDebit').text(thSeparator(totalDebit.toFixed(0)));
    $('#totalCredit').text(thSeparator(totalCredit.toFixed(0)));
    $('#balance').text(thSeparator(balance.toFixed(0)));
    $('#grandTotal').text(thSeparator(grandTotal.toFixed(0))); // Update grandTotal
}


$(document).ready(function() {
    // Call the function to update totals when the page is ready
    updateTotals();

    // Call the function to update totals whenever the table is redrawn
    $('#CashBookTable').on('draw.dt', function() {
        updateTotals();
    });

    // Call the function to update totals whenever the column visibility changes
    $('#CashBookTable').on('column-visibility.dt', function() {
        updateTotals();
    });
});



function saveRecord() {
    // Validate the form
    var formId = 'newRecordForm';
    if (validateForm(formId)) {
        // Get form values
        var formData = new FormData(document.getElementById(formId));

        // Make an AJAX request to handle form submission
        var xhr = new XMLHttpRequest();
        xhr.open('POST', '<?php echo APP_URL; ?>/view/cashbook/cashbook_create.php', true);
        xhr.onload = function() {
            handleResponse(xhr);
        };

        // Set up error handling for the request
        xhr.onerror = function() {
            console.error('Error: Unable to process the request.');
        };

        xhr.send(formData);
    }
}

function updateRecord(id) {
    // Validate the form
    var formId = 'editRecordForm' + id;
    console.log(formId);
    if (validateForm(formId, true)) {
        // Get form values
        var formData = new FormData(document.getElementById(formId));
        formData.append('id', id);

        // Make an AJAX request to handle form submission
        var xhr = new XMLHttpRequest();
        xhr.open('POST', '<?php echo APP_URL; ?>/view/cashbook/cashbook_update.php', true);
        xhr.onload = function() {
            handleResponse(xhr, 'CashBook');
        };

        // Set up error handling for the request
        xhr.onerror = function() {
            console.error('Error: Unable to process the request.');
        };

        xhr.send(formData);
    }
}

function deleteRecord(id) {
    var confirmation = confirm('Are you sure you want to delete this entry?');

    if (confirmation) {
        var formData = new FormData();
        formData.append('id', id);

        var xhr = new XMLHttpRequest();
        xhr.open('POST', '<?php echo APP_URL; ?>/view/cashbook/cashbook_delete.php', true);
        xhr.onload = function() {
            handleResponse(xhr);
        };

        xhr.onerror = function() {
            console.error('Error: Unable to process the request.');
        };

        xhr.send(formData);
    }
}

function handleResponse(xhr) {
    console.log(xhr.responseText); // Log the response text

    try {
        var response = JSON.parse(xhr.responseText);

        if (response.status === 'success') {
            // Close the modals
            $('#myModal').modal('hide');
            $('#editModal').modal('hide');

            // Add a timeout before reloading the page
            setTimeout(function() {
                location.reload();
            }, 200);
        } else {
            // Error handling
            alert('Error: ' + response.message);
        }
    } catch (error) {
        // Log any JSON parsing errors
        console.error('JSON parsing error:', error);
    }
}

function validateForm(formId, isEditMode) {
    var isValid = true;
    var formElements = document.getElementById(formId).elements;
    var fieldsToExclude = ['credit', 'debit', 'expenseType', 'transactionType'];
    var editFieldsToExclude = ['editCredit', 'editDebit', 'editExpenseType', 'editTransactionType'];

    for (var i = 0; i < formElements.length; i++) {
        var field = formElements[i];

        if (field.type === 'button') {
            // Reset border for buttons
            field.style.border = '';
            continue;
        }

        if (!isEditMode && fieldsToExclude.includes(field.name)) {
            // Exclude insert modal fields
            field.style.border = '';
            continue;
        }

        if (isEditMode) {
            // Exclude edit modal fields based on startsWith
            var excluded = editFieldsToExclude.some(function(prefix) {
                return field.name.startsWith(prefix);
            });
            if (excluded) {
                field.style.border = '';
                continue;
            }
        }

        if (field.value.trim() === '') {
            console.log('Empty field ID: ', field.id); // Display the field ID
            isValid = false;
            field.style.border = '1px solid red';
        } else {
            field.style.border = '1px solid #9DCD5A';
        }
    }

    return isValid;
}

function toggleFields(transactionType) {
    var defaultHiddenFields = ['debit', 'expenseType'];

    // Get references to fields to show for Expense
    var fieldsToShowForExpense = ['debit', 'expenseType'];
    // Get references to all fields you want to hide
    var fieldsToHideForExpense = ['credit'];


    // Hide/show fields based on transaction type
    if (transactionType === 'Expense') {
        // Show Expense related fields
        for (var k = 0; k < fieldsToShowForExpense.length; k++) {
            document.getElementById(fieldsToShowForExpense[k]).style.display = 'block';
            // Show labels associated with these fields
            document.querySelector("label[for='" + fieldsToShowForExpense[k] + "']").style.display = 'block';
        }
        // Hide other fields and their labels
        for (var l = 0; l < fieldsToHideForExpense.length; l++) {
            document.getElementById(fieldsToHideForExpense[l]).style.display = 'none';
            // Hide labels associated with these fields
            document.querySelector("label[for='" + fieldsToHideForExpense[l] + "']").style.display = 'none';
        }
    } else {
        // Show all fields for other transaction types
        // Show labels for fields that might have been hidden previously
        for (var n = 0; n < fieldsToHideForExpense.length; n++) {
            document.getElementById(fieldsToHideForExpense[n]).style.display = 'block';
            // Show labels associated with these fields
            document.querySelector("label[for='" + fieldsToHideForExpense[n] + "']").style.display = 'block';
        }
        // Hide default hidden fields
        for (var o = 0; o < defaultHiddenFields.length; o++) {
            document.getElementById(defaultHiddenFields[o]).style.display = 'none';
            // Hide labels associated with these fields
            document.querySelector("label[for='" + defaultHiddenFields[o] + "']").style.display = 'none';
        }
    }
}

function openEditModal(id) {
    // Close any open modals
    $('.modal').modal('hide');

    // Open the Cash Book edit modal
    $('#editModal' + id).modal('show');
}


function debitFieldStatus(qty, rate, debitField) {
    // If both qty and rate are empty, make the credit field editable
    if (qty === null && rate === null) {
        debitField.readOnly = false;
    } else {
        // If either qty or rate has a value, make the credit field readonly
        debitField.readOnly = true;
    }
}




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
$("#CashBookTable")
    .on('length.dt', function(e, settings, len) {
        saveUserPreferences(len, settings._iDisplayStart / len + 1);
    })
    .on('page.dt', function(e, settings) {
        saveUserPreferences(settings._iDisplayLength, settings._iDisplayStart / settings._iDisplayLength + 1);
    });
</script>
</body>

</html>