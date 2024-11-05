<?php
session_start();
include 'db.php'; // Database connection

// Check if the admin is logged in
if (!isset($_SESSION['admin_logged_in'])) {
    header('Location: admin_login.php');
    exit();
}

// Set the target directory for file uploads
$target_dir = "../docs/";

// Edit functionality
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit_id'])) {
    $edit_id = $_POST['edit_id'];
    $title = $_POST['edit_title'];
    $department_id = $_POST['edit_department_id'];

    // Initialize a variable to hold the new document name
    $new_file_name = null;

    // Check if a new file is uploaded
    if (!empty($_FILES['edit_document']['name'])) {
        $file_name = basename($_FILES["edit_document"]["name"]);
        $target_file = $target_dir . $file_name;
        $fileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        // Validate file type
        if ($fileType != "pdf" && $fileType != "doc" && $fileType != "docx") {
            echo "Only PDF, DOC, and DOCX files are allowed.";
            exit;
        } else {
            // Move the new file to the target directory
            if (move_uploaded_file($_FILES["edit_document"]["tmp_name"], $target_file)) {
                $new_file_name = $file_name; // Set new file name for database update
            } else {
                echo "Failed to upload new file.";
                exit;
            }
        }
    }

    // Prepare SQL statement for updating the study material
    if ($new_file_name) {
        // Update the study material including the new document
        $update_sql = "UPDATE study_materials SET title = ?, department_id = ?, document = ? WHERE id = ?";
        $stmt = $conn->prepare($update_sql);
        $stmt->bind_param("sisi", $title, $department_id, $new_file_name, $edit_id);
    } else {
        // Update the study material without changing the document
        $update_sql = "UPDATE study_materials SET title = ?, department_id = ? WHERE id = ?";
        $stmt = $conn->prepare($update_sql);
        $stmt->bind_param("sii", $title, $department_id, $edit_id);
    }

    // Execute the statement and check for success
    if ($stmt->execute()) {
        echo "Study material updated successfully.";
    } else {
        echo "Error updating study material: " . $conn->error;
    }

    $stmt->close();
}

// Delete functionality
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_id'])) {
    $delete_id = $_POST['delete_id'];

    // Retrieve the filename from the database before deleting
    $select_sql = "SELECT document FROM study_materials WHERE id = ?";
    $stmt = $conn->prepare($select_sql);
    $stmt->bind_param("i", $delete_id);
    $stmt->execute();
    $stmt->bind_result($file_name);
    $stmt->fetch();
    $stmt->close();

    // Delete the file from the server if it exists
    if ($file_name && file_exists($target_dir . $file_name)) {
        unlink($target_dir . $file_name);
    }

    // Prepare SQL statement for deletion
    $delete_sql = "DELETE FROM study_materials WHERE id = ?";
    $stmt = $conn->prepare($delete_sql);
    $stmt->bind_param("i", $delete_id);

    // Execute the deletion
    if ($stmt->execute()) {
        echo "Study material deleted successfully.";
    } else {
        echo "Error deleting study material: " . $conn->error;
    }

    $stmt->close();
}

// Close database connection
$conn->close();
?>
