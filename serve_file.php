<?php
session_start();
// Check if the user is logged in and is an admin
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] != 'admin') {
    die("Unauthorized access.");
}

// Define the path to the file
$file_path = 'uploads/' . $_GET['file'];
if (file_exists($file_path)) {
    header('Content-Description: File Transfer');
    header('Content-Type: application/octet-stream');
    header('Content-Disposition: attachment; filename="' . basename($file_path) . '"');
    header('Expires: 0');
    header('Cache-Control: must-revalidate');
    header('Pragma: public');
    header('Content-Length: ' . filesize($file_path));
    flush(); // Flush system output buffer
    readfile($file_path);
    exit;
} else {
    echo "File not found.";
}
?>
