**create_المخاطر.php**

<?php
// Start session
session_start();

// Check if user is logged in
if (!isset($_SESSION['logged_in'])) {
    header('Location: login.php');
    exit;
}

// Include header and navigation
include 'header.php';
include 'navigation.php';
?>

<div class="container mx-auto p-4 pt-6 md:p-6 lg:px-12">
    <div class="bg-white rounded-lg shadow-md p-4">
        <h2 class="text-slate-900 font-bold text-lg mb-4">إضافة مخاطر جديدة</h2>
        <form id="create-risk-form" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="name" class="text-slate-900 font-bold">اسم المخاطر</label>
                    <input type="text" id="name" name="name" class="w-full p-2 text-slate-900 border border-slate-300 rounded-lg focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" required>
                </div>
                <div>
                    <label for="description" class="text-slate-900 font-bold">وصف المخاطر</label>
                    <textarea id="description" name="description" class="w-full p-2 text-slate-900 border border-slate-300 rounded-lg focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" required></textarea>
                </div>
            </div>
            <div class="flex justify-end">
                <button type="submit" class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded-lg">حفظ</button>
            </div>
        </form>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
<script>
    $(document).ready(function() {
        $('#create-risk-form').submit(function(e) {
            e.preventDefault();
            var formData = $(this).serialize();
            $.ajax({
                type: 'POST',
                url: '../backend/المخاطر.php',
                data: formData,
                success: function(response) {
                    if (response === 'success') {
                        window.location.href = 'list_المخاطر.php';
                    } else {
                        alert('Error: ' + response);
                    }
                }
            });
        });
    });
</script>

<?php
// Include footer
include 'footer.php';
?>


**المخاطر.php (backend)**

<?php
// Check if form data is submitted
if (isset($_POST['name']) && isset($_POST['description'])) {
    // Connect to database
    $conn = mysqli_connect('localhost', 'username', 'password', 'database');
    
    // Check connection
    if (!$conn) {
        die('Connection failed: ' . mysqli_connect_error());
    }
    
    // Insert data into database
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $sql = "INSERT INTO المخاطر (name, description) VALUES ('$name', '$description')";
    if (mysqli_query($conn, $sql)) {
        echo 'success';
    } else {
        echo 'Error: ' . mysqli_error($conn);
    }
    
    // Close connection
    mysqli_close($conn);
}
?>