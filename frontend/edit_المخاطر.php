**edit_المخاطر.php**

<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in']) {
    header('Location: login.php');
    exit;
}

// Get id from URL
$id = $_GET['id'];

// Fetch existing record details via GET
$url = '../backend/المخاطر.php?id=' . $id;
$response = file_get_contents($url);
$data = json_decode($response, true);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit المخاطر</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
    <div class="max-w-md mx-auto p-4 bg-white rounded-lg shadow-md">
        <h2 class="text-lg font-bold text-slate-900 mb-4">Edit المخاطر</h2>
        <form id="edit-form" class="space-y-4">
            <div>
                <label for="name" class="block text-sm font-medium text-slate-900">Name:</label>
                <input type="text" id="name" name="name" class="block w-full p-2 pl-10 text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-indigo-500 focus:border-indigo-500" value="<?= $data['name'] ?>">
            </div>
            <div>
                <label for="description" class="block text-sm font-medium text-slate-900">Description:</label>
                <textarea id="description" name="description" class="block w-full p-2 pl-10 text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-indigo-500 focus:border-indigo-500" rows="4"><?= $data['description'] ?></textarea>
            </div>
            <button type="submit" class="w-full px-4 py-2 text-sm font-medium text-white bg-indigo-500 border border-transparent rounded-lg shadow-md hover:bg-indigo-600 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">Update</button>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#edit-form').submit(function(e) {
                e.preventDefault();
                var formData = $(this).serialize();
                $.ajax({
                    type: 'PUT',
                    url: '../backend/المخاطر.php',
                    data: formData,
                    success: function(response) {
                        if (response.success) {
                            window.location.href = 'list_المخاطر.php';
                        } else {
                            alert('Error updating record');
                        }
                    },
                    error: function(xhr, status, error) {
                        alert('Error updating record: ' + error);
                    }
                });
            });
        });
    </script>
</body>
</html>


**backend/المخاطر.php**

<?php
// Get id from URL
$id = $_GET['id'];

// Fetch existing record details from database
// Replace with your actual database query
$data = array(
    'id' => $id,
    'name' => 'Existing Record Name',
    'description' => 'Existing Record Description'
);

// Return data as JSON
header('Content-Type: application/json');
echo json_encode($data);