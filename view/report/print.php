<?php
include APP_ROOT . '/db/conn.php';
include_once APP_ROOT . '/includes/custom_functions.php';
include_once APP_ROOT . '/assets/tcpdf/tcpdf.php';

$fromDate = $_GET['from'];
$toDate = $_GET['to'];
$prevBal = $_GET['prevBal'];

// Convert the dates to the desired format
$formattedFromDate = formatDate($fromDate);
$formattedToDate = formatDate($toDate);

$pdf = new TCPDF('P', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetHeaderData('', '', 'Daily Sheet Report', '');
$pdf->setHeaderFont(array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$pdf->setFooterFont(array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
$pdf->SetDefaultMonospacedFont('helvetica');
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
$pdf->SetMargins(10, 10, 10); // Set margins (left, top, right)
$pdf->setPrintHeader(false);
$pdf->setPrintFooter(false);
$pdf->SetAutoPageBreak(TRUE, 10);
$pdf->SetFont('helvetica', '', 12);
$pdf->AddPage(); //default A4

// Border around the page
$pdf->SetLineStyle(array('width' => 0.5, 'color' => array(0, 0, 0)));
$pdf->Rect(5, 5, 200, 287, 'D');
$html = '<table cellpadding="2" cellspacing="0" border="0" style="border-collapse: collapse;">';
$html .= '<tr>';
$html .= '<td style="width: 60%; font-weight: bold; font-size: 18px;">';
$html .= '<img src="' . APP_URL . '/assets/img/uploads/client-logo.png" width="50" />';
$html .= '</td>';

$html .= '<td style="width: 40%; font-weight: bold; font-size: 16px; text-align: right; line-height: 24px;">Finance Report</td>';
$html .= '</tr>';

$html .= '<br>';

$html .= '<tr style="font-size: 11px;">';
$html .= '<td style="width: 50%;"><strong>From Date:</strong>  ' . $formattedFromDate . '</td>';
$html .= '<td style="width: 50%;"></td>';
$html .= '</tr>';

$html .= '<tr style="font-size: 11px;">';
$html .= '<td style="width: 50%;"><strong>To Date:</strong> ' . $formattedToDate . '</td>';
$html .= '<td style="width: 50%; text-align: right;"><strong>Previous Closing:</strong> ' . thSeparator($prevBal) . '</td>';
$html .= '</tr>';
$html .= '</table>';

// Add some space
$html .= '<br>';
$html .= '<br>';

// Table
$html .= '<table cellpadding="2" cellspacing="0" border="1" style="border-collapse: collapse;">';
$html .= '<tr style="font-size: 9px;">';
$html .= '<th style="width: 10%; background-color: lightgrey; border: 1px solid #000; font-weight: bold; text-align: center;">Sr.</th>';
$html .= '<th style="width: 60%; background-color: lightgrey; border: 1px solid #000; font-weight: bold; text-align: center;">Details</th>';
$html .= '<th style="width: 15%; background-color: lightgrey; border: 1px solid #000; font-weight: bold; text-align: center;">Income</th>';
$html .= '<th style="width: 15%; background-color: lightgrey; border: 1px solid #000; font-weight: bold; text-align: center;">Expense</th>';
$html .= '</tr>';

$totalReceipt = 0;
$totalPayment = 0;

// Fetch and display Ledger data from the database
if (isset($fromDate) && isset($toDate)) {
    // Get "From" and "To" values from the form data
    $from = $_POST['from'];
    $to = $_POST['to'];

    // Convert date format
    $from = date('Y-m-d', strtotime($from));
    $to = date('Y-m-d', strtotime($to));

    // Update SQL query
    $sql = "SELECT * FROM income_expense 
    WHERE date BETWEEN '$from' AND '$to' 
    AND reporting = 'yes'";
    
    // Fetch and display Ledger data from the database
    $result = mysqli_query($conn, $sql); // Re-run the query to get the correct result set
    $rowCount = mysqli_num_rows($result);
///TODO: priniting Logic is yet to complete
    if ($rowCount > 0) {
        $srNumber = 1;
        while ($row = mysqli_fetch_assoc($result)) {
            // Display the record
            $html .= '<tr style="font-size: 9px;">';
            $html .= '<td style="width: 10%; border: 1px solid #000; text-align: center;">' . $srNumber . '</td>';

            $html .= '<td style="width: 60%; border: 1px solid #000;">';
            // Check if 'qty' field exists in $row
            if (isset($row['qty'])) {
                $html .= $row['details'] . ' QTY ' . $row['qty'];
            } else {
                $html .= $row['details'];
            }
            $html .= '</td>';

            // Display CR as receipt and DR as payment if it's the else part of the logic
            if (
                $row['qty'] !== null &&
                $row['rate'] !== null &&
                $row['debit'] !== null &&
                $row['credit'] === null &&
                $row['is_jv'] !== 'yes'
            ) {
                $html .= '<td></td>'; // Empty receipt field
                $html .= '<td></td>'; // Empty payment field
            } else if (
                $row['qty'] !== null &&
                $row['rate'] !== null &&
                $row['debit'] === null &&
                $row['credit'] !== null &&
                $row['is_jv'] !== 'yes'
            ) {
                $html .= '<td></td>'; // Empty receipt field
                $html .= '<td></td>'; // Empty payment field
            } else {
                $html .= '<td style="width: 12%; border: 1px solid #000; text-align: right;">' . thSeparator($row['debit']) . '</td>'; // Display DR as payment
                $html .= '<td style="width: 12%; border: 1px solid #000; text-align: right;">' . thSeparator($row['credit']) . '</td>'; // Display CR as receipt

                $totalReceipt += $row['credit']; // Add receipt value to total receipt
                $totalPayment += $row['debit']; // Add payment value to total payment
            }

            $html .= '</tr>';
            $srNumber++;
        }
    } else {
        // No transactions made message
        $html .= '<tr><td colspan="5" style="text-align: center;">No Transactions from ' . $fromDate . ' to ' . $toDate . '</td></tr>';
    }

    // Close the result set
    mysqli_free_result($result);
} else {
    $html .= '<tr><td colspan="5" style="text-align: center;">Invalid date range</td></tr>';
}
$html .= '<tfoot>';
$html .= '<tr style="font-size: 9px;">';
$html .= '<th colspan="3" style="background-color: lightgrey; border: 1px solid #000; font-weight: bold; text-align: right;">Total</th>';
$html .= '<td style="background-color: lightgrey; border: 1px solid #000; font-weight: bold; text-align: right;">' . thSeparator($totalPayment) . '</td>';
$html .= '<td style="background-color: lightgrey; border: 1px solid #000; font-weight: bold; text-align: right;">' . thSeparator($totalReceipt) . '</td>';
$html .= '</tr>';
$html .= '</tfoot>';

// Calculate closing balance
$closingBalance = $prevBal + ($totalReceipt - $totalPayment);

$html .= '<tfoot>';
$html .= '<tr style="font-size: 11px; line-height: 18px;">';
$html .= '<th style="width: 85%; border: 3px solid white; font-weight: bold; text-align: right;">Grand Total:</th>';
$html .= '<td style="width: 15%; border: 3px solid white; font-weight: normal; text-align: right;">' . thSeparator($closingBalance) . '</td>';
$html .= '</tr>';
$html .= '</tfoot>';

$html .= '</table>';

// Add HTML content to PDF
$pdf->writeHTML($html, true, false, true, false, '');

// Set the footer with page number out of total pages
$pdf->setFooterData(array(0, 64, 0), array(0, 64, 128));
$pdf->setFooterFont(array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
$pdf->setFooterMargin(PDF_MARGIN_FOOTER);
$pdf->setPrintFooter(true);

// Display closing balance below the table
// $htmlClosingBalance = '<div style="text-align: right; margin-top: 20px;"><strong>Closing Balance:</strong> ' . thSeparator($closingBalance) . '</div>';
// $pdf->writeHTML($htmlClosingBalance, true, false, true, false, '');

// Set the time zone to your desired time zone
// date_default_timezone_set('Asia/Karachi'); // Reset to a specific timezone
// or
date_default_timezone_set('Etc/GMT-5'); // For UTC+5 (PKT Time)

// Custom Filename
$datetime = date('dmY_His');
$file_name = "DAILYSHEET_" . $datetime . ".pdf";
ob_end_clean();

if ($_GET['ACTION'] == 'VIEW') {
    $pdf->Output($file_name, 'I'); // I means Inline view
} else if ($_GET['ACTION'] == 'DOWNLOAD') {
    $pdf->Output($file_name, 'D'); // D means download
}

// Close the database connection
mysqli_close($conn);