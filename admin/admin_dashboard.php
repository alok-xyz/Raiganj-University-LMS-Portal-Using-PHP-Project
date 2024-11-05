<?php
session_start();
include 'db.php';

// Check if the admin is logged in
if (!isset($_SESSION['admin_logged_in'])) {
    header('Location: admin_login.php');
    exit();
}

// Fetch total students
$students_sql = "SELECT COUNT(*) AS total_students FROM students";
$students_result = $conn->query($students_sql);
$total_students = $students_result->fetch_assoc()['total_students'];

// Fetch total study materials
$materials_sql = "SELECT COUNT(*) AS total_materials FROM study_materials";
$materials_result = $conn->query($materials_sql);
$total_materials = $materials_result->fetch_assoc()['total_materials'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <title>Admin Dashboard - Raiganj University LMS</title>
</head>
<body class="bg-gray-100">
    <div class="flex">
        <!-- Include Sidebar Menu -->
        <?php include 'admin_sidebar.php'; ?>

        <!-- Main Content -->
        <main class="flex-1 p-6">
            <h1 class="text-3xl font-bold mb-6">Admin Dashboard</h1>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 gap-6">
                <div class="bg-white p-6 rounded-lg shadow-md flex flex-col items-center justify-center">
                    <div class="text-4xl font-bold text-blue-600"><?php echo $total_students; ?></div>
                    <div class="text-lg font-semibold mt-2">Total Students</div>
                    <div class="mt-4">
                        <i class="fas fa-user-graduate text-blue-600 text-3xl"></i>
                    </div>
                </div>
                
                <div class="bg-white p-6 rounded-lg shadow-md flex flex-col items-center justify-center">
                    <div class="text-4xl font-bold text-green-600"><?php echo $total_materials; ?></div>
                    <div class="text-lg font-semibold mt-2">Total Study Materials</div>
                    <div class="mt-4">
                        <i class="fas fa-book-open text-green-600 text-3xl"></i>
                    </div>
                </div>
            </div>
        </main>
    </div>
</body>
</html>
