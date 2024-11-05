<?php
// Start session
session_start();

// Include database connection
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get form data
    $full_name = $_POST['full_name'];
    $department_id = $_POST['department'];
    $registration_no = $_POST['registration_no'];
    $roll_no = $_POST['roll_no'];
    $semester = $_POST['semester'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    
    // Handle file upload
    $photo = $_FILES['student_photo'];
    $photoName = basename($photo['name']);
    $photoPath = 'uploads/' . $photoName;

    // Move uploaded file to target directory
    if (move_uploaded_file($photo['tmp_name'], $photoPath)) {
        // Hash the password
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        
        // Prepare SQL statement
        $insertQuery = "INSERT INTO students (full_name, department, registration_no, roll_no, semester, email, password, photo, created_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW())";
        $stmt = $conn->prepare($insertQuery);
        
        // Bind parameters (notice we bind the hashed password now)
        $stmt->bind_param('ssssssss', $full_name, $department_id, $registration_no, $roll_no, $semester, $email, $hashed_password, $photoName);

        // Execute and check for errors
        if ($stmt->execute()) {
            echo "<script>alert('Registration Successfully'); window.location.href='login.php';</script>";
        } else {
            echo "Error: " . $stmt->error;
        }

        // Close statement
        $stmt->close();
    } else {
        echo "Failed to upload photo.";
    }
}

// Close database connection
$conn->close();
?>
