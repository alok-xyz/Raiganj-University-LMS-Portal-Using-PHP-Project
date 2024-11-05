<?php
session_start();
include 'db.php';

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// Get the user's department ID
$user_id = $_SESSION['user_id'];
$user_department_query = "SELECT department FROM students WHERE id = ?";
$stmt = $conn->prepare($user_department_query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$user_department_result = $stmt->get_result();

if ($user_department_result->num_rows > 0) {
    $user_department_id = $user_department_result->fetch_assoc()['department'];

    // Fetch study materials for the user's department
    $sql = "SELECT sm.id, sm.title, d.name AS department_name, sm.document 
            FROM study_materials sm 
            JOIN departments d ON sm.department_id = d.id 
            WHERE sm.department_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_department_id); // Bind department ID
    $stmt->execute();
    $result = $stmt->get_result();
} else {
    // If no department is found, set result to an empty array
    $result = [];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <title>Study Materials - Raiganj University LMS</title>
</head>
<body class="bg-gray-100">
    <div class="flex">
        <!-- Include Sidebar Menu -->
        <div class="flex h-screen bg-gray-100">
            <?php include 'sidebar.php'; ?>
        </div>

        <!-- Main Content -->
        <main class="flex-1 p-6">
            <h1 class="text-2xl font-bold mb-6">Study Materials</h1>

            <div class="overflow-x-auto bg-white shadow-md rounded-lg">
                <table class="min-w-full bg-white border border-gray-300 rounded-lg">
                    <thead class="bg-gray-200">
                        <tr>
                            <th class="py-3 px-6 text-left text-gray-700 font-semibold">Title</th>
                            <th class="py-3 px-6 text-left text-gray-700 font-semibold">Department</th>
                            <th class="py-3 px-6 text-left text-gray-700 font-semibold">Document</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($result->num_rows > 0): ?>
                            <?php while ($row = $result->fetch_assoc()): ?>
                                <tr class="hover:bg-gray-100">
                                    <td class="py-4 px-6 border-b text-gray-700"><?= htmlspecialchars($row['title']) ?></td>
                                    <td class="py-4 px-6 border-b text-gray-700"><?= htmlspecialchars($row['department_name']) ?></td>
                                    <td class="py-4 px-6 border-b text-blue-600 hover:underline">
                                    <a href="download.php?file=<?= urlencode($row['document']) ?>" target="_blank">Download</a>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="3" class="py-4 px-6 border-b text-center text-gray-500">No study materials found for your department.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </main>
    </div>
</body>
</html>
