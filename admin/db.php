<?php
$servername = "localhost"; // Adjust as necessary
$username = "root"; // Adjust as necessary
$password = ""; // Adjust as necessary
$dbname = "university_lms";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
