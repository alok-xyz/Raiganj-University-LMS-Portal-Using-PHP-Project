<?php
session_start(); // Start the session
include 'db.php'; // Include your database connection

// Check if the student is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php'); // Redirect to login page if not logged in
    exit();
}

// Initialize variables
$message = '';

// Process the form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_SESSION['user_id'];
    $new_password = $_POST['new_password'];

    // Hash the new password
    $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

    // Update the password in the database
    $sql = "UPDATE students SET password = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $hashed_password, $user_id);

    if ($stmt->execute()) {
        $message = "Password updated successfully!";
    } else {
        $message = "Error updating password: " . $stmt->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <title>Update Profile - Raiganj University LMS</title>
    <style>
        /* Custom styles for the password visibility toggle */
        .toggle-password {
            cursor: pointer;
        }
    </style>
</head>
<body class="bg-gray-100">
    <div class="flex">
        <!-- Include Sidebar Menu -->
        <div class="flex h-screen bg-gray-100">
            <?php include 'sidebar.php'; ?>
        </div>

        <!-- Main Content -->
        <main class="flex-1 p-6">
            <h1 class="text-2xl font-bold mb-4">Update Profile</h1>
            <?php if (!empty($message)): ?>
                <div class="bg-green-100 text-green-800 p-4 rounded mb-4">
                    <p><?php echo $message; ?></p>
                </div>
            <?php endif; ?>
            <form method="POST" class="bg-white p-4 rounded shadow-md">
                <div class="relative mb-4">
                    <input type="password" id="new_password" name="new_password" placeholder="New Password" required class="border border-gray-300 p-2 w-full" />
                    <span class="absolute inset-y-0 right-0 flex items-center pr-3 cursor-pointer toggle-password" id="togglePassword">
                        <i class="fas fa-eye" id="toggleIcon"></i>
                    </span>
                </div>
                <button type="submit" class="bg-blue-500 text-white p-2 rounded w-full hover:bg-blue-600 transition">Update Password</button>
            </form>
        </main>
    </div>

    <script>
        // JavaScript to toggle password visibility
        const togglePassword = document.getElementById('togglePassword');
        const passwordInput = document.getElementById('new_password');
        const toggleIcon = document.getElementById('toggleIcon');

        togglePassword.addEventListener('click', function () {
            // Toggle the password visibility
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);
            // Toggle the icon
            toggleIcon.classList.toggle('fa-eye');
            toggleIcon.classList.toggle('fa-eye-slash');
        });
    </script>
</body>
</html>
