<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Raiganj University LMS</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
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
</head>
<body class="bg-gradient-to-r from-blue-100 to-blue-200 min-h-screen flex items-center justify-center">
    <div class="bg-white p-10 rounded-lg shadow-lg w-full max-w-5xl">
        <h2 class="text-3xl font-bold text-center mb-8 text-blue-600">Student Registration</h2>
        
        <script>
            const registrationEnabled = false; // Set to true to enable, false to disable registration

            $(document).ready(function () {
                if (!registrationEnabled) {
                    // Alert the user and disable the form if registration is closed
                    alert('Registration is Temporarily Closed Now');
                    $('#registrationForm :input').prop('disabled', true);
                }
            });
        </script>

        <form id="registrationForm" action="register_process.php" method="POST" enctype="multipart/form-data">
            <div class="mb-6">
                <label for="full_name" class="block text-gray-700 font-medium mb-2">Full Name</label>
                <input type="text" name="full_name" id="full_name" placeholder="Enter your full name" 
                       class="border border-gray-300 rounded w-full p-3 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
            </div>
            <div class="mb-6">
                <label for="department" class="block text-gray-700 font-medium mb-2">Department</label>
                <select name="department" id="department" class="border rounded w-full py-2 px-3" required>
                    <option value="" disabled selected>Select Department</option>
                    <?php
                    include 'db.php'; // Ensure proper db connection
                    $departmentQuery = "SELECT id, name FROM departments";
                    $departments = $conn->query($departmentQuery);
                    while ($row = $departments->fetch_assoc()) {
                        echo "<option value='{$row['id']}'>{$row['name']}</option>";
                    }
                    ?>
                </select>
            </div>
            <div class="mb-6">
                <label for="registration_no" class="block text-gray-700 font-medium mb-2">Registration No</label>
                <input type="text" name="registration_no" id="registration_no" placeholder="Enter registration number" 
                       class="border border-gray-300 rounded w-full p-3 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
            </div>
            <div class="mb-6">
                <label for="roll_no" class="block text-gray-700 font-medium mb-2">Roll No</label>
                <input type="text" name="roll_no" id="roll_no" placeholder="Enter roll number" 
                       class="border border-gray-300 rounded w-full p-3 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
            </div>
            <div class="mb-6">
                <label for="semester" class="block text-gray-700 font-medium mb-2">Semester</label>
                <select name="semester" id="semester" class="border rounded w-full py-2 px-3" required>
                    <option value="" disabled selected>Select Semester</option>
                    <option value="1">1st</option>
                    <option value="2">2nd</option>
                    <option value="3">3rd</option>
                    <option value="4">4th</option>
                    <option value="5">5th</option>
                    <option value="6">6th</option>
                </select>
            </div>
            <div class="mb-6">
                <label for="email" class="block text-gray-700 font-medium mb-2">Email</label>
                <input type="email" name="email" id="email" placeholder="Enter your email address" 
                       class="border border-gray-300 rounded w-full p-3 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
            </div>
            <div class="mb-6">
                <label for="password" class="block text-gray-700 font-medium mb-2">Password</label>
                <input type="password" name="password" id="password" placeholder="Create a password" 
                       class="border border-gray-300 rounded w-full p-3 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
            </div>
            <div class="mb-6">
                <label for="student_photo" class="block text-gray-700 font-medium mb-2">Upload Photo (under 100KB)</label>
                <input type="file" name="student_photo" id="student_photo" accept="image/*" 
                       class="border border-gray-300 rounded w-full p-3 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
            </div>
            <button type="submit" class="w-full py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition duration-300">
                Register
            </button>
        </form>
        <p class="text-center mt-6 text-gray-600">Already registered? 
            <a href="login.php" class="text-blue-600 hover:underline">Login here</a>.
        </p>
    </div>
</body>
</html>
