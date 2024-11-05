<?php
session_start();
include 'db.php'; // Include database connection

// Fetch student details
if (isset($_SESSION['user_id'])) {
    $student_id = $_SESSION['user_id'];
    // Modify the SQL query to include department name
    $sql = "SELECT s.*, d.name AS department_name FROM students s 
            JOIN departments d ON s.department = d.id 
            WHERE s.id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $student_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $student = $result->fetch_assoc();

    if (!$student) {
        // Redirect if no student found
        header("Location: login.php");
        exit();
    }
} else {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Raiganj University</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="flex h-screen bg-gray-100">
    <?php include 'sidebar.php'; ?>

    <div class="flex-1 p-6">
        <h2 class="text-2xl font-semibold mb-4">Welcome, <?php echo htmlspecialchars($student['full_name']); ?></h2>
        <div class="bg-white shadow-md rounded-lg p-6">
            <h3 class="text-xl font-semibold">Your Details</h3>
            <div class="mt-4">
                <?php 
                // Construct the image path
                $photoPath = 'uploads/' . htmlspecialchars($student['photo']); 
                
                // Check if the image file exists
                if (file_exists($photoPath) && !empty($student['photo'])) {
                    echo '<img src="' . $photoPath . '" alt="Student Photo" class="w-24 h-24 rounded-full border-2 border-gray-300">';
                } else {
                    // Display an error message or default image if the file is missing
                    echo '<img src="path/to/default_image.jpg" alt="Default Photo" class="w-24 h-24 rounded-full border-2 border-gray-300">';
                    echo '<p class="text-red-500 mt-2">Image not found. Please ensure the image exists in the "uploads" folder.</p>';
                    error_log("Image path issue: Could not locate file at $photoPath");
                }
                ?>
                <p class="mt-2"><strong>Department:</strong> <?php echo htmlspecialchars($student['department_name']); ?></p>
                <p><strong>Registration No:</strong> <?php echo htmlspecialchars($student['registration_no']); ?></p>
                <p><strong>Roll No:</strong> <?php echo htmlspecialchars($student['roll_no']); ?></p>
                <p><strong>Semester:</strong> <?php echo htmlspecialchars($student['semester']); ?></p>
                <p><strong>Email:</strong> <?php echo htmlspecialchars($student['email']); ?></p>
            </div>
        </div>
    </div>
</body>
</html>
