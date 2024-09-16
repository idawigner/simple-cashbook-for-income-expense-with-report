<?php

 // Function to format CNIC
 function formatCnic($cnic) {
    // Ensure the CNIC is 13 digits long before formatting
    if (strlen($cnic) == 13) {
        return substr($cnic, 0, 5) . '-' . substr($cnic, 5, 7) . '-' . substr($cnic, 12, 1);
    }
    return $cnic; // Return the original CNIC if it's not 13 digits
}

// Function to format phone numbers
function formatPhoneNo($phoneNo) {
    // Ensure the phone number is at least 10 digits long before formatting
    if (strlen($phoneNo) >= 10) {
        $len = strlen($phoneNo);
        $prefix = substr($phoneNo, 0, $len - 7);
        $mainPart = substr($phoneNo, -7);
        return $prefix . '-' . $mainPart;
    }
    return $phoneNo; // Return the original phone number if it's not at least 10 digits
}

// Function to format Date into desired format
function formatDate($date) {
    return date('m/d/Y', strtotime($date));
}

// Function to add separators for the Asian number system
function thSeparator($number) {
    $isNegative = false;
    if ($number < 0) { $isNegative=true; $number=abs($number); } $number=strval($number); $len=strlen($number);
    $formattedNumber='' ; 
    if ($len> 3) {
        $lastThree = substr($number, -3);
        $remaining = substr($number, 0, $len - 3);
        $formattedNumber = $lastThree;

        while (strlen($remaining) > 2) {
            $lastTwo = substr($remaining, -2);
            $remaining = substr($remaining, 0, strlen($remaining) - 2);
            $formattedNumber = $lastTwo . ',' . $formattedNumber;
        }

        if (strlen($remaining) > 0) {
            $formattedNumber = $remaining . ',' . $formattedNumber;
        }
    } else {
        $formattedNumber = $number;
    }

    return $isNegative ? '-' . $formattedNumber : $formattedNumber;
}

// Define the function to add thousand separators
function thSeparatorGlobal($number) {
    return number_format($number);
}

function d2ThSeparator($number) {
    return number_format($number, 2);
}

function formatNumber($value, $decimals) {
    // Check if the value is not empty and not null
    if (!empty($value) && $value !== null) {
        // Check if the decimal portion is zero
        if ($value == intval($value)) {
            // If the decimal portion is zero, format without decimals
            return number_format($value, 0);
        } else {
            // If the decimal portion is not zero, format with the specified number of decimals
            return number_format($value, $decimals);
        }
    } else {
        // If the value is empty or null, return an empty string
        return '0';
    }
}

// Function to convert excess square feet into marlas
function convertToMarlas(&$totalSF, &$totalM) {
    $excessSF = 272; // Define the excess square feet threshold
    $marlaSize = 272; // 1 marla = 272 sqft
    while ($totalSF >= $excessSF) {
        $marlasToAdd = floor($totalSF / $marlaSize);
        $totalSF -= $marlasToAdd * $marlaSize;
        $totalM += $marlasToAdd;
    }
}

// Function to convert excess marlas into kanals
function convertToKanals(&$totalM, &$totalK) {
    $excessMarlas = 20; // Define the excess marlas threshold
    $kanalSize = 20; // 1 kanal = 20 marlas
    while ($totalM >= $excessMarlas) {
        $kanalsToAdd = floor($totalM / $kanalSize);
        $totalM -= $kanalsToAdd * $kanalSize;
        $totalK += $kanalsToAdd;
    }
}

// Function to convert excess kanals into acres
function convertToAcres(&$totalK, &$totalA) {
    $excessKanals = 8; // Define the excess kanals threshold
    $acreSize = 8; // 1 acre = 8 kanals
    while ($totalK >= $excessKanals) {
        $acresToAdd = floor($totalK / $acreSize);
        $totalK -= $acresToAdd * $acreSize;
        $totalA += $acresToAdd;
    }
}

// Function to convert square feet into acres
function convertSqFtToAcres($sqFt) {
    $acreSize = 43520; // 1 acre = 43,520 sqft
    $acres = $sqFt / $acreSize;
    return $acres;
}

// Function to generate the select options for the relation field
function generateRelationOptions($selectedValue = '') {
    $relations = [
        '' => '- Select -',
        'R/O' => 'R/O',
        'S/O' => 'S/O',
        'D/O' => 'D/O',
        'H/O' => 'H/O',
        'F/O' => 'F/O',
        'W/O' => 'W/O',
        'Widow of' => 'Widow of'
    ];

    $options = '';
    foreach ($relations as $value => $label) {
        $selected = ($value == $selectedValue) ? 'selected' : '';
        $options .= "<option value=\"$value\" $selected>$label</option>";
    }
    return $options;
}

// Function to make a dropdown readonly
function makeDropdownReadonly($elementId) {
    return "
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Make the dropdown readonly
        document.getElementById('$elementId')
            .addEventListener(
                'mousedown',
                function(e) {
                    e.preventDefault();
                    this.blur();
                    return false;
                });
    });
    </script>
    ";
}