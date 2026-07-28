**edit_التصحيحات.php**

<?php
// Start session
session_start();

// Check if user is logged in
if (!isset($_SESSION['logged_in'])) {
    header('Location: login.php');
    exit;
}

// Get id from URL
$id = $_GET['id'];

// Fetch existing record details via AJAX
$existingRecord = json_decode(file_get_contents('../backend/التصحيحات.php?id=' . $id), true);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit التصحيفات</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
    <div class="max-w-md mx-auto p-4 bg-white rounded-md shadow-md">
        <h2 class="text-slate-900 text-lg font-bold mb-4">Edit التصحيفات</h2>
        <form id="edit-form" class="space-y-4">
            <div>
                <label for="name" class="text-slate-900">Name:</label>
                <input type="text" id="name" name="name" class="w-full p-2 text-slate-900 border border-slate-300 rounded-md" value="<?= $existingRecord['name'] ?>">
            </div>
            <div>
                <label for="description" class="text-slate-900">Description:</label>
                <textarea id="description" name="description" class="w-full p-2 text-slate-900 border border-slate-300 rounded-md"><?= $existingRecord['description'] ?></textarea>
            </div>
            <button type="submit" class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded-md">Save Changes</button>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            // Fetch existing record details via AJAX
            $.ajax({
                type: 'GET',
                url: '../backend/التصحيحات.php?id=' + <?= $id ?>,
                success: function(data) {
                    const existingRecord = JSON.parse(data);
                    $('#name').val(existingRecord.name);
                    $('#description').val(existingRecord.description);
                }
            });

            // Submit form via AJAX
            $('#edit-form').submit(function(e) {
                e.preventDefault();
                const formData = $(this).serialize();
                $.ajax({
                    type: 'PUT',
                    url: '../backend/التصحيحات.php',
                    data: formData,
                    success: function() {
                        window.location.href = 'list_التصحيحات.php';
                    }
                });
            });
        });
    </script>
</body>
</html>


**backend/التصحيحات.php**

<?php
// Check if id is set
if (!isset($_GET['id'])) {
    http_response_code(404);
    exit;
}

// Get id from URL
$id = $_GET['id'];

// Fetch existing record details from database
// Replace with your actual database query
$existingRecord = [
    'id' => $id,
    'name' => 'Existing Record Name',
    'description' => 'Existing Record Description'
];

// Output JSON response
header('Content-Type: application/json');
echo json_encode($existingRecord);


**backend/edit_التصحيحات.php**

<?php
// Check if id is set
if (!isset($_GET['id'])) {
    http_response_code(404);
    exit;
}

// Get id from URL
$id = $_GET['id'];

// Update existing record details in database
// Replace with your actual database query
if (isset($_POST['name']) && isset($_POST['description'])) {
    // Update record
    // Replace with your actual database query
    echo 'Record updated successfully!';
} else {
    http_response_code(400);
}