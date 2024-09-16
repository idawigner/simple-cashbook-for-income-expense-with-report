<?php

// Database Connection for Live Session
// $servername = "localhost";
// $username = "";
// $password = "";
// $dbname = "";


// Database Connection for Local Session
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "cashbook";


// Defining variable for the connection
$conn = mysqli_connect($servername, $username, $password, $dbname);

// If connection fails, Show alert with message
if (!$conn) {
  echo '<script type="text/javascript">
            alert("Connection failed. Please check your database connection details.");
          </script>';
  die(mysqli_connect_error());
}