<?php
session_start();

// Admin credentials
$admin_username = 'rgu@adminlms2024';
$admin_password = '123';

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get user input
    $username_input = $_POST['username'];
    $password_input = $_POST['password'];

    // Check for correct credentials
    if ($username_input === $admin_username && $password_input === $admin_password) {
        $_SESSION['admin_logged_in'] = true;
        header('Location: admin_dashboard.php'); // Redirect to admin dashboard
        exit();
    } else {
        // Redirect to fake admin page for wrong credentials
        header('Location: fake_admin.php'); // Redirect to fake admin page
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <title>Admin Login - Raiganj University LMS</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        // Disable right-click and certain keyboard shortcuts
        document.addEventListener('contextmenu', function (e) {
            e.preventDefault(); // Disable right-click menu
        });

        document.addEventListener('keydown', function (e) {
            // Disable F12, Ctrl+Shift+I, Ctrl+U, Ctrl+S
            if (e.key === "F12" || 
                (e.ctrlKey && e.shiftKey && e.key === "I") || 
                (e.ctrlKey && e.key === "u") || 
                (e.ctrlKey && e.key === "s")) {
                e.preventDefault();
            }
        });
    </script>
    <style>
        body {
            background: url('https://images.unsplash.com/photo-1506748686214-e9df14d4d9d0?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&ixid=MnwzNjUyOXwwfDF8c2VhcmNofDB8fGJhY2tncm91bmR8ZW58MHx8fHwxNjcyNzg3ODQz&ixlib=rb-1.2.1&q=80&w=1080') no-repeat center center fixed;
            background-size: cover;
        }
    </style>
</head>
<body class="flex items-center justify-center h-screen">
    <div class="bg-white bg-opacity-90 p-8 rounded-lg shadow-lg w-96">
        <h1 class="text-2xl font-semibold mb-4 text-center text-gray-800">Admin Login</h1>
        <form method="POST">
            <input type="text" name="username" placeholder="Username" required class="border border-gray-300 p-2 w-full mb-4 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" />
            <input type="password" name="password" placeholder="Password" required class="border border-gray-300 p-2 w-full mb-4 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" />
            <button type="submit" class="bg-red-600 text-white p-2 rounded-lg w-full hover:bg-red-500 transition duration-200">Login</button>
        </form>
        <p class="text-center mt-4 text-gray-600 text-sm">© 2024 Raiganj University LMS</p>
    </div>
</body>
</html>
