<?php
include APP_ROOT . '/db/conn.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $recordId = $_POST['id'];

    // Fetch existing values from the income_expense
    $fetchSql = "SELECT details FROM income_expense WHERE id = '$recordId'";
    $result = mysqli_query($conn, $fetchSql);

    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $existingDetails = $row['details'];

        $date = $_POST['editDate' . $recordId] ?? date("Y-m-d");
        $details = $_POST['editDetails' . $recordId] ?? $existingDetails;
        $credit = $_POST['editCredit' . $recordId] ?? null;
        $debit = $_POST['editDebit' . $recordId] ?? null;
        $expenseType = $_POST['editExpenseType' . $recordId] ?? null;

        $edited = $_POST['edited' . $recordId] ?? 'yes';

        $credit = empty($credit) ? 'NULL' : $credit;
        $debit = empty($debit) ? 'NULL' : $debit;


        $sql = "UPDATE income_expense SET date='$date', details='$details', credit=$credit, debit=$debit, expense_type='$expenseType', edited='$edited' 
        WHERE id = $recordId";

        if (mysqli_query($conn, $sql)) {
            recalculateSubsequentBalances($conn, $date);
            echo json_encode(array("status" => "success"));
        } else {
            echo json_encode(array("status" => "error", "message" => mysqli_error($conn)));
        }
    } else {
        // No record found in the ledger for given ID
        echo json_encode(array("status" => "error", "message" => "No record found for ledgerId '$recordId'"));
    }
    mysqli_close($conn);
    exit;
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