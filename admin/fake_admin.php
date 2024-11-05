<?php
session_start();

// Destroy the session if the user is logging out
if (isset($_GET['action']) && $_GET['action'] === 'logout') {
    session_destroy(); // Destroy the session
    header('Location: admin_login.php'); // Redirect to the login page
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <title>Welcome to Admin Page</title>
    <style>
        body {
            background: url('https://images.unsplash.com/photo-1506748686214-e9df14d4d9d0?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&ixid=MnwzNjUyOXwwfDF8c2VhcmNofDB8fGJhY2tncm91bmR8ZW58MHx8fHwxNjcyNzg3ODQz&ixlib=rb-1.2.1&q=80&w=1080') no-repeat center center fixed;
            background-size: cover;
        }
    </style>
</head>
<body class="flex items-center justify-center h-screen">
    <div class="bg-white bg-opacity-80 p-10 rounded-lg shadow-lg w-96 text-center">
        <h1 class="text-3xl font-semibold mb-4 text-gray-800">Welcome, Admin!</h1>
        <p class="mb-6 text-gray-600">You seem to have entered the wrong credentials.<br> Don't worry, everything is fine here!</p>
        <p class="mb-6 text-gray-500 italic">You are not a Admin! Go Drink Water!</p>
        <a href="?action=logout" class="bg-red-600 text-white p-2 rounded-lg hover:bg-red-500 transition duration-200">Logout</a>
        <p class="mt-4 text-gray-600 text-sm">© 2024 Raiganj University LMS</p>
    </div>
</body>
</html>
