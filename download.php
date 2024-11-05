<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    die("Unauthorized access.");
}

// Include the database connection
include 'db.php';

// Check if 'file' parameter is set
if (!isset($_GET['file'])) {
    die("No file specified.");
}

// Sanitize file name to prevent directory traversal attacks
$filename = basename($_GET['file']); // Only takes the file name

// Define the full file path in the `docs` directory
$filePath = __DIR__ . "/docs/" . $filename;

if (file_exists($filePath)) {
    // Determine the MIME type for inline viewing
    $mimeType = mime_content_type($filePath);
    
    // Set headers to open file in browser
    header("Content-Description: File Transfer");
    header("Content-Type: $mimeType");
    header("Content-Disposition: inline; filename=\"" . basename($filePath) . "\"");
    header("Content-Length: " . filesize($filePath));
    
    // Output file
    readfile($filePath);
    exit;
} else {
    die("The requested file could not be found.");
}
