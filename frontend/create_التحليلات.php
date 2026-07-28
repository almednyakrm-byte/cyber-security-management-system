**create_التحليلات.php**

<?php
// Session validation
if (!isset($_SESSION['username'])) {
    header('Location: ../login.php');
    exit;
}

// Include header
include '../includes/header.php';

// Include Tailwind CSS
?>

<link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">

<?php
// Include navigation
include '../includes/navigation.php';
?>

<div class="container mx-auto p-4 mt-6">
    <h1 class="text-3xl font-bold text-slate-900 mb-4">إضافة جديد</h1>

    <form id="create-form" class="bg-white p-4 rounded shadow-md">
        <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
            <div>
                <label for="name" class="block text-sm font-medium text-slate-900">الاسم</label>
                <input type="text" id="name" name="name" class="block w-full p-2 mt-1 text-sm text-slate-900 bg-white border border-gray-300 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
            </div>
            <div>
                <label for="description" class="block text-sm font-medium text-slate-900">الوصف</label>
                <textarea id="description" name="description" class="block w-full p-2 mt-1 text-sm text-slate-900 bg-white border border-gray-300 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"></textarea>
            </div>
            <div>
                <label for="category" class="block text-sm font-medium text-slate-900">التصنيف</label>
                <select id="category" name="category" class="block w-full p-2 mt-1 text-sm text-slate-900 bg-white border border-gray-300 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                    <option value="">اختر تصنيف</option>
                    <!-- Add options here -->
                </select>
            </div>
            <div>
                <label for="status" class="block text-sm font-medium text-slate-900">الحالة</label>
                <select id="status" name="status" class="block w-full p-2 mt-1 text-sm text-slate-900 bg-white border border-gray-300 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                    <option value="">اختر حالة</option>
                    <!-- Add options here -->
                </select>
            </div>
        </div>

        <button type="submit" class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">حفظ</button>
    </form>
</div>

<?php
// Include footer
include '../includes/footer.php';

// Include JavaScript
?>

<script>
    $(document).ready(function() {
        $('#create-form').submit(function(e) {
            e.preventDefault();

            $.ajax({
                type: 'POST',
                url: '../backend/التحليلات.php',
                data: $(this).serialize(),
                success: function(response) {
                    if (response === 'success') {
                        window.location.href = '../list_التحليلات.php';
                    } else {
                        alert('Error: ' + response);
                    }
                }
            });
        });
    });
</script>


**Note:** Replace `../backend/التحليلات.php` with the actual URL of your backend script that handles the form submission. Also, add options to the `category` and `status` select elements as needed.

**backend/التحليلات.php**

<?php
// Handle form submission
if (isset($_POST['name']) && isset($_POST['description']) && isset($_POST['category']) && isset($_POST['status'])) {
    // Insert data into database
    $name = $_POST['name'];
    $description = $_POST['description'];
    $category = $_POST['category'];
    $status = $_POST['status'];

    // Insert data into database
    $query = "INSERT INTO التحليلات (name, description, category, status) VALUES ('$name', '$description', '$category', '$status')";
    $result = mysqli_query($conn, $query);

    if ($result) {
        echo 'success';
    } else {
        echo 'Error: ' . mysqli_error($conn);
    }
}
?>


**Note:** Replace `../backend/التحليلات.php` with the actual URL of your backend script that handles the form submission. Also, replace `mysqli_query` and `mysqli_error` with your preferred database library.