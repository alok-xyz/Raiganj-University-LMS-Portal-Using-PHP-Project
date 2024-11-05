<?php
session_start();
include 'db.php';

// Check if the admin is logged in
if (!isset($_SESSION['admin_logged_in'])) {
    header('Location: admin_login.php');
    exit();
}

// Set the target directory in the project root for file uploads
$target_dir = "../docs/";

// Fetch all study materials with department information
$sql = "SELECT sm.id, sm.title, sm.document, sm.department_id, d.name AS department_name 
        FROM study_materials sm 
        JOIN departments d ON sm.department_id = d.id";
$result = $conn->query($sql);

// Handle form submission for adding new study materials
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
    $title = $_POST['title'];
    $department_id = $_POST['department_id'];

    // Check for duplicate entry
    $check_sql = "SELECT * FROM study_materials WHERE title = ? AND department_id = ?";
    $check_stmt = $conn->prepare($check_sql);
    $check_stmt->bind_param("si", $title, $department_id);
    $check_stmt->execute();
    $check_result = $check_stmt->get_result();

    if ($check_result->num_rows > 0) {
        echo "<script>alert('Error: A study material with this title already exists for the selected department.');</script>";
    } else {
        // Handle file upload
        $file_name = basename($_FILES["document"]["name"]);
        $target_file = $target_dir . $file_name;
        $fileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        // Only allow PDF, DOC, and DOCX file types
        if ($fileType != "pdf" && $fileType != "doc" && $fileType != "docx") {
            echo "<script>alert('Sorry, only PDF, DOC, and DOCX files are allowed.');</script>";
        } else {
            // Move file to the target directory
            if (move_uploaded_file($_FILES["document"]["tmp_name"], $target_file)) {
                // Save study material info in the database
                $insert_sql = "INSERT INTO study_materials (title, department_id, document) VALUES (?, ?, ?)";
                $stmt = $conn->prepare($insert_sql);
                $stmt->bind_param("sis", $title, $department_id, $file_name);

                if ($stmt->execute()) {
                    echo "<script>alert('Study material uploaded successfully.'); window.location.href='study_materials.php';</script>";
                } else {
                    echo "<script>alert('Error: Could not save study material.');</script>";
                }
            } else {
                echo "<script>alert('Error: Failed to upload file.');</script>";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <title>Study Materials - Raiganj University LMS</title>
</head>
<body class="bg-gray-100">
    <div class="flex">
        <?php include 'admin_sidebar.php'; ?>

        <main class="flex-1 p-6">
            <h1 class="text-2xl font-bold mb-4">Study Materials</h1>
            <form method="POST" enctype="multipart/form-data" class="bg-white p-4 rounded shadow-md mb-4">
                <input type="text" name="title" placeholder="Material Title" required class="border border-gray-300 p-2 w-full mb-4" />
                <select name="department_id" required class="border border-gray-300 p-2 w-full mb-4">
                    <option value="">Select Department</option>
                    <?php
                    // Fetch departments for the dropdown
                    $departments = $conn->query("SELECT id, name FROM departments");
                    while ($row = $departments->fetch_assoc()) {
                        echo "<option value='{$row['id']}'>{$row['name']}</option>";
                    }
                    ?>
                </select>
                <input type="file" name="document" required class="border border-gray-300 p-2 w-full mb-4" />
                <button type="submit" name="submit" class="bg-blue-500 text-white p-2 rounded w-full hover:bg-blue-600 transition">Upload</button>
            </form>

            <table class="min-w-full bg-white border border-gray-300">
                <thead>
                    <tr>
                        <th class="border border-gray-300 p-2">Title</th>
                        <th class="border border-gray-300 p-2">Department</th>
                        <th class="border border-gray-300 p-2">Document</th>
                        <th class="border border-gray-300 p-2">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td class="border border-gray-300 p-2"><?= htmlspecialchars($row['title']); ?></td>
                            <td class="border border-gray-300 p-2"><?= htmlspecialchars($row['department_name']); ?></td>
                            <td class="border border-gray-300 p-2">
                                <a href="../docs/<?= htmlspecialchars($row['document']); ?>" target="_blank" class="text-blue-600 hover:underline">Download</a>
                            </td>
                            <td class="border border-gray-300 p-2">
                                <button class="text-blue-500 edit-btn" data-id="<?= $row['id']; ?>" data-title="<?= htmlspecialchars($row['title']); ?>" data-department-id="<?= $row['department_id']; ?>" data-document="<?= htmlspecialchars($row['document']); ?>">Edit</button>
                                <button class="text-red-500 delete-btn" data-id="<?= $row['id']; ?>">Delete</button>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>

            <!-- Edit Modal -->
            <div id="editModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 flex justify-center items-center">
                <div class="bg-white p-4 rounded shadow-md w-1/3">
                    <h2 class="text-xl mb-4">Edit Study Material</h2>
                    <form id="editForm">
                        <input type="hidden" id="edit-id" name="edit_id">
                        <input type="text" id="edit-title" name="edit_title" placeholder="Material Title" required class="border border-gray-300 p-2 w-full mb-4">
                        <select id="edit-department-id" name="edit_department_id" required class="border border-gray-300 p-2 w-full mb-4">
                            <option value="">Select Department</option>
                            <?php
                            // Fetch departments for the dropdown
                            $departments = $conn->query("SELECT id, name FROM departments");
                            while ($row = $departments->fetch_assoc()) {
                                echo "<option value='{$row['id']}'>{$row['name']}</option>";
                            }
                            ?>
                        </select>
                        <input type="file" name="edit_document" class="border border-gray-300 p-2 w-full mb-4" />
                        <button type="submit" class="bg-blue-500 text-white p-2 rounded w-full hover:bg-blue-600 transition">Update</button>
                    </form>
                    <button id="closeEditModal" class="mt-2 text-red-500">Close</button>
                </div>
            </div>

            <!-- Delete Confirmation Modal -->
            <div id="deleteModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 flex justify-center items-center">
                <div class="bg-white p-4 rounded shadow-md w-1/3">
                    <h2 class="text-xl mb-4">Confirm Deletion</h2>
                    <p>Are you sure you want to delete this study material?</p>
                    <input type="hidden" id="delete-id">
                    <div class="flex justify-between mt-4">
                        <button id="confirmDelete" class="bg-red-500 text-white p-2 rounded">Delete</button>
                        <button id="closeDeleteModal" class="bg-gray-300 p-2 rounded">Cancel</button>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <script>
$(document).ready(function () {
    // Show edit modal
    $('.edit-btn').on('click', function () {
        const id = $(this).data('id');
        const title = $(this).data('title');
        const departmentId = $(this).data('department-id');

        $('#edit-id').val(id);
        $('#edit-title').val(title);
        $('#edit-department-id').val(departmentId);
        $('#editModal').removeClass('hidden');
    });

    // Close edit modal
    $('#closeEditModal').on('click', function () {
        $('#editModal').addClass('hidden');
    });

    // Handle edit form submission
    $('#editForm').on('submit', function (e) {
        e.preventDefault();
        const formData = new FormData(this);
        $.ajax({
            type: 'POST',
            url: 'edit_delete_study_materials.php', // Change to your edit file path
            data: formData,
            contentType: false,
            processData: false,
            success: function (response) {
                alert(response);
                window.location.reload();
            }
        });
    });

    // Show delete confirmation modal
    $('.delete-btn').on('click', function () {
        const id = $(this).data('id');
        $('#delete-id').val(id);
        $('#deleteModal').removeClass('hidden');
    });

    // Close delete modal
    $('#closeDeleteModal').on('click', function () {
        $('#deleteModal').addClass('hidden');
    });

    // Confirm deletion
    $('#confirmDelete').on('click', function () {
        const id = $('#delete-id').val();
        $.ajax({
            type: 'POST',
            url: 'edit_delete_study_materials.php', // Change to your delete file path
            data: { delete_id: id },
            success: function (response) {
                alert(response);
                window.location.reload();
            }
        });
    });
});
    </script>
</body>
</html>
