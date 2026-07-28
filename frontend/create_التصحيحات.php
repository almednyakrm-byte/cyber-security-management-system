**create_التصحيحات.php**

<?php
// Start session
session_start();

// Check if user is logged in
if (!isset($_SESSION['logged_in'])) {
    header('Location: login.php');
    exit;
}

// Include database connection
require_once '../config/db.php';

// Check if form has been submitted
if (isset($_POST['submit'])) {
    // Validate form data
    $name = trim($_POST['name']);
    $description = trim($_POST['description']);

    // Check for empty fields
    if (empty($name) || empty($description)) {
        $error = 'Please fill in all fields';
    } else {
        // Insert data into database
        $query = "INSERT INTO تصحيحات (name, description) VALUES ('$name', '$description')";
        $result = mysqli_query($conn, $query);

        if ($result) {
            // Redirect back to list page
            header('Location: list_التصحيحات.php');
            exit;
        } else {
            $error = 'Error inserting data';
        }
    }
}

// Close database connection
mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>إضافة تصحيح</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f7f7f7;
        }
        .bg-slate-900 {
            background-color: #1a1a1a;
        }
        .text-indigo-500 {
            color: #6b5f7e;
        }
    </style>
</head>
<body class="bg-slate-900">
    <div class="container mx-auto p-4 mt-4 bg-white rounded-md shadow-md">
        <h1 class="text-3xl text-indigo-500 font-bold mb-4">إضافة تصحيح</h1>
        <form id="create-form" method="post" class="space-y-4">
            <div class="flex flex-wrap -mx-3 mb-6">
                <div class="w-full md:w-1/2 px-3 mb-6 md:mb-0">
                    <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="name">اسم التصحيح</label>
                    <input id="name" type="text" class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" name="name" required>
                </div>
                <div class="w-full md:w-1/2 px-3">
                    <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="description">وصف التصحيح</label>
                    <textarea id="description" class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" name="description" required></textarea>
                </div>
            </div>
            <button type="submit" class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded" name="submit">إضافة</button>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#create-form').submit(function(e) {
                e.preventDefault();
                var formData = $(this).serialize();
                $.ajax({
                    type: 'POST',
                    url: '../backend/التصحيحات.php',
                    data: formData,
                    success: function(response) {
                        if (response === 'success') {
                            window.location.href = 'list_التصحيحات.php';
                        } else {
                            alert('Error creating record');
                        }
                    }
                });
            });
        });
    </script>
</body>
</html>

**backend/التصحيحات.php**

<?php
// Include database connection
require_once '../config/db.php';

// Check if form data has been submitted
if (isset($_POST['submit'])) {
    // Validate form data
    $name = trim($_POST['name']);
    $description = trim($_POST['description']);

    // Insert data into database
    $query = "INSERT INTO تصحيحات (name, description) VALUES ('$name', '$description')";
    $result = mysqli_query($conn, $query);

    if ($result) {
        echo 'success';
    } else {
        echo 'Error creating record';
    }
}

// Close database connection
mysqli_close($conn);
?>