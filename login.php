<?php
session_start();
include 'db.php';

// Redirect to dashboard if already logged in
if (isset($_SESSION['user_id'])) {
    header("Location: dashboard.php");
    exit();
}

$error = ""; // Initialize error variable

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM students WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    // Use password_verify to check the hashed password
    if ($user && password_verify($password, $user['password'])) {
        // Store user ID in session
        $_SESSION['user_id'] = $user['id'];
        // Redirect to dashboard after successful login
        header('Location: dashboard.php');
        exit(); // Stop further script execution
    } else {
        $error = "Invalid email or password!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Raiganj University</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />
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
        /* Loader styles */
        #loader {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(255, 255, 255, 0.9);
            z-index: 50;
            align-items: center;
            justify-content: center;
            flex-direction: column;
        }

        .loading-text {
            margin-top: 20px;
            font-size: 1.5rem;
            color: #3498db;
            animation: pulse 1.5s infinite;
        }

        @keyframes pulse {
            0% {
                transform: scale(1);
            }
            50% {
                transform: scale(1.1);
                color: #e74c3c; /* Change color during pulse */
            }
            100% {
                transform: scale(1);
                color: #3498db; /* Original color */
            }
        }

        /* Slider animation */
        .slider {
            width: 120px;
            height: 20px;
            position: relative;
            animation: slide 3s ease-in-out infinite;
        }

        @keyframes slide {
            0% {
                transform: translateX(-50%);
                background-color: #e74c3c; /* Start color */
            }
            25% {
                transform: translateX(0);
                background-color: #3498db; /* Mid color */
            }
            50% {
                transform: translateX(50%);
                background-color: #2ecc71; /* End color */
            }
            75% {
                transform: translateX(0);
                background-color: #f1c40f; /* Return color */
            }
            100% {
                transform: translateX(-50%);
                background-color: #e74c3c; /* Loop back to start color */
            }
        }
    </style>
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen">
    <div class="bg-white p-8 rounded-lg shadow-lg max-w-md w-full">
        <h2 class="text-3xl font-bold text-center mb-6 text-blue-600">Student Login</h2>
        <?php if ($error): ?>
            <div class="text-red-500 mb-4"><?php echo $error; ?></div>
        <?php endif; ?>
        <form id="loginForm" method="POST" onsubmit="showLoader(); return false;">
            <div class="mb-4">
                <label for="email" class="block text-gray-700">Email</label>
                <input type="email" id="email" name="email" placeholder="Enter your email" required 
                       class="w-full mt-1 p-3 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div class="mb-6">
                <label for="password" class="block text-gray-700">Password</label>
                <input type="password" id="password" name="password" placeholder="Enter your password" required 
                       class="w-full mt-1 p-3 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <button type="submit" class="w-full bg-blue-600 text-white py-3 rounded hover:bg-blue-700 transition">
                Login
            </button>
            <p class="text-center mt-6 text-gray-600">Don't Have Account ?  
            <a href="register.php" class="text-blue-600 hover:underline">Register here</a>.
           </p>
        </form>
    </div>

    <!-- Loader -->
    <div id="loader" class="flex items-center justify-center">
        <div class="slider animate__animated animate__slideInLeft"></div>
        <div class="loading-text animate__animated animate__fadeIn">Loading...</div>
    </div>

    <script>
        function showLoader() {
            document.getElementById('loader').style.display = 'flex';

            // Delay to simulate loading before actually submitting the form
            setTimeout(() => {
                document.getElementById('loginForm').submit();
            }, 3000); // Adjust this to match the duration of your loader
        }
    </script>
</body>
</html>
