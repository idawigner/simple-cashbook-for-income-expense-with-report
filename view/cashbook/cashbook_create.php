<?php
include(APP_ROOT . '/db/conn.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $date = $_POST['date'] ?? date("Y-m-d");
    $details = $_POST['details'] ?? null;
    $credit = $_POST['credit'] ?? null;
    $debit = $_POST['debit'] ?? null;
    $expenseType = $_POST['expenseType'] ?? null;


    $reporting = isset($_POST['reporting']) ? ($_POST['reporting'] == 'yes' ? 'yes' : 'no') : 'yes';
    $entryType = $_POST['entryType'] ?? null;

    // $expenseType = empty($expenseType) ? 'NULL' : $expenseType;
    $credit = empty($credit) ? 'NULL' : $credit;
    $debit = empty($debit) ? 'NULL' : $debit;

    $sql = "INSERT INTO income_expense (date, details, credit, debit, expense_type, reporting) 
            VALUES ('$date', '$details', $credit, $debit, '$expenseType', '$reporting')";

    if (mysqli_query($conn, $sql)) {
        $lastInsertedId = mysqli_insert_id($conn);
        $newBalance = calculateBalance($conn, $lastInsertedId, $date);

        $updateBalanceSql = "UPDATE income_expense SET balance = '$newBalance' WHERE id = '$lastInsertedId'";
        mysqli_query($conn, $updateBalanceSql);

        // Recalculate balances for all subsequent records for the same 
        recalculateSubsequentBalances($conn, $date);

        echo json_encode(array("status" => "success", 'id' => $lastInsertedId));
    } else {
        echo json_encode(array("status" => "error", "message" => mysqli_error($conn)));
    }

    mysqli_close($conn);
}

function getPreviousRecordBalance($conn, $date)
{
    $sql = "SELECT COALESCE(balance, 0) AS balance FROM income_expense 
            WHERE date < '$date' 
            ORDER BY date DESC, id DESC LIMIT 1";
    $result = mysqli_query($conn, $sql);

    if ($result) {
        $row = mysqli_fetch_assoc($result);
        return $row ? $row['balance'] : 0;
    } else {
        error_log("Error in getPreviousRecordBalance: " . mysqli_error($conn));
        return 0;
    }
}

function getCurrentDebit($conn, $currentId)
{
    $sql = "SELECT COALESCE(debit, 0) AS debit FROM income_expense WHERE id = '$currentId'";
    $result = mysqli_query($conn, $sql);

    if ($result) {
        $row = mysqli_fetch_assoc($result);
        return $row ? $row['debit'] : 0;
    } else {
        error_log("Error in getCurrentDebit: " . mysqli_error($conn));
        return 0;
    }
}

function getCurrentCredit($conn, $currentId)
{
    $sql = "SELECT COALESCE(credit, 0) AS credit FROM income_expense WHERE id = '$currentId'";
    $result = mysqli_query($conn, $sql);

    if ($result) {
        $row = mysqli_fetch_assoc($result);
        return $row ? $row['credit'] : 0;
    } else {
        error_log("Error in getCurrentCredit: " . mysqli_error($conn));
        return 0;
    }
}

function calculateBalance($conn, $currentId, $date)
{
    $previousBalance = getPreviousRecordBalance($conn, $date);
    $currentDebit = getCurrentDebit($conn, $currentId);
    $currentCredit = getCurrentCredit($conn, $currentId);

    return $previousBalance - $currentDebit + $currentCredit;
}

function recalculateSubsequentBalances($conn, $startDate)
{
    $sql = "SELECT id, date FROM income_expense WHERE date >= '$startDate' ORDER BY date, id";
    $result = mysqli_query($conn, $sql);

    if ($result) {
        $previousBalance = getPreviousRecordBalance($conn, $startDate);
        while ($row = mysqli_fetch_assoc($result)) {
            $currentId = $row['id'];
            $currentDebit = getCurrentDebit($conn, $currentId);
            $currentCredit = getCurrentCredit($conn, $currentId);
            $newBalance = $previousBalance - $currentDebit + $currentCredit;

            $updateBalanceSql = "UPDATE income_expense SET balance = '$newBalance' WHERE id = '$currentId'";
            mysqli_query($conn, $updateBalanceSql);

            $previousBalance = $newBalance;
        }
    } else {
        error_log("Error in recalculateSubsequentBalances: " . mysqli_error($conn));
    }
}