<?php
include APP_ROOT . '/db/conn.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $recordId = $_POST['id'];

    // Get the date of the record to be deleted
    $dateQuery = "SELECT date FROM income_expense WHERE id = '$recordId'";
    $dateResult = mysqli_query($conn, $dateQuery);
    $dateRow = mysqli_fetch_assoc($dateResult);
    $deleteDate = $dateRow['date'];

    // Perform the deletion
    $sql = "DELETE FROM income_expense WHERE id = '$recordId'";
    $result = mysqli_query($conn, $sql);

    if ($result) {
        // Update the balance for remaining records
        recalculateSubsequentBalances($conn, $deleteDate);

        $response = array('status' => 'success', 'message' => 'Record deleted successfully.');
    } else {
        $response = array('status' => 'error', 'message' => 'Error deleting record: ' . mysqli_error($conn));
    }
    echo json_encode($response);
} else {
    echo json_encode(array('status' => 'error', 'message' => 'Invalid request.'));
}

mysqli_close($conn);

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
