<?php
session_start();
include 'db.php';

// Check if the admin is logged in
if (!isset($_SESSION['admin_logged_in'])) {
    header('Location: admin_login.php');
    exit();
}

// Initialize search query
$search_query = '';
if (isset($_POST['search'])) {
    $search_query = $_POST['search'];
}

// Prepare SQL query with join to fetch department names
$sql = "SELECT students.full_name, departments.name AS department_name, students.roll_no, students.registration_no
        FROM students
        LEFT JOIN departments ON students.department = departments.id
        WHERE students.full_name LIKE ? OR departments.name LIKE ? OR students.roll_no LIKE ? OR students.registration_no LIKE ?";
$search_param = "%$search_query%";
$stmt = $conn->prepare($sql);
$stmt->bind_param('ssss', $search_param, $search_param, $search_param, $search_param);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <title>View Students - Raiganj University LMS</title>
</head>
<body class="bg-gray-100">
    <div class="flex">
        <!-- Include Sidebar Menu -->
        <?php include 'admin_sidebar.php'; ?>

        <!-- Main Content -->
        <main class="flex-1 p-6">
            <h1 class="text-3xl font-bold mb-6">View Students</h1>

            <!-- Search Bar -->
            <form method="POST" class="mb-6">
                <input type="text" name="search" value="<?php echo htmlspecialchars($search_query); ?>" placeholder="Search by name, department, roll or registration number" class="border border-gray-300 p-2 w-full rounded-lg focus:outline-none focus:ring focus:ring-blue-300" />
                <button type="submit" class="mt-2 bg-blue-500 text-white p-2 rounded-lg hover:bg-blue-600 transition">Search</button>
            </form>

            <div class="overflow-x-auto bg-white shadow-md rounded-lg">
                <table class="min-w-full bg-white">
                    <thead class="bg-gray-200">
                        <tr>
                            <th class="py-2 px-4 border-b text-left">Full Name</th>
                            <th class="py-2 px-4 border-b text-left">Department</th>
                            <th class="py-2 px-4 border-b text-left">Roll No</th>
                            <th class="py-2 px-4 border-b text-left">Registration No</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($result->num_rows > 0): ?>
                            <?php while($row = $result->fetch_assoc()): ?>
                                <tr>
                                    <td class="py-2 px-4 border-b"><?php echo htmlspecialchars($row['full_name']); ?></td>
                                    <td class="py-2 px-4 border-b"><?php echo htmlspecialchars($row['department_name']); ?></td>
                                    <td class="py-2 px-4 border-b"><?php echo htmlspecialchars($row['roll_no']); ?></td>
                                    <td class="py-2 px-4 border-b"><?php echo htmlspecialchars($row['registration_no']); ?></td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="4" class="py-2 px-4 border-b text-center">No students found.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </main>
    </div>
</body>
</html>
