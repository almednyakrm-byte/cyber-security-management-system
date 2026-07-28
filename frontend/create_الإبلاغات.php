**create_الإبلاغات.php**

<?php
// Session validation
if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in']) {
    header('Location: login.php');
    exit;
}

// Include header and navigation
require_once 'header.php';
require_once 'nav.php';

// Include form validation and submission script
require_once 'form_validation.php';

// Form data
$data = array(
    'title' => '',
    'description' => '',
    'date' => '',
    'status' => '',
);

// Form validation
if (isset($_POST['submit'])) {
    $data = array(
        'title' => $_POST['title'],
        'description' => $_POST['description'],
        'date' => $_POST['date'],
        'status' => $_POST['status'],
    );
    $errors = validateForm($data);
    if (empty($errors)) {
        // Insert data into database
        $result = insertData($data);
        if ($result) {
            // Redirect back to list page
            header('Location: list_الإبلاغات.php');
            exit;
        } else {
            $errors[] = 'Error inserting data';
        }
    }
}

// Include form view
require_once 'create_الإبلاغات_form.php';
?>

<?php require_once 'footer.php'; ?>


**create_الإبلاغات_form.php**

<!-- Create report form -->
<div class="max-w-md mx-auto p-4 bg-white rounded-lg shadow-md">
    <h2 class="text-lg font-bold text-slate-900 mb-4">إضافة تقرير جديد</h2>
    <form id="create-report-form" method="post" enctype="multipart/form-data">
        <div class="mb-4">
            <label for="title" class="block text-sm font-medium text-slate-900">عنوان التقرير</label>
            <input type="text" id="title" name="title" class="block w-full p-2 pl-10 text-sm text-slate-900 placeholder-slate-400 border border-slate-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500" placeholder="عنوان التقرير">
        </div>
        <div class="mb-4">
            <label for="description" class="block text-sm font-medium text-slate-900">وصف التقرير</label>
            <textarea id="description" name="description" class="block w-full p-2 pl-10 text-sm text-slate-900 placeholder-slate-400 border border-slate-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500" placeholder="وصف التقرير"></textarea>
        </div>
        <div class="mb-4">
            <label for="date" class="block text-sm font-medium text-slate-900">تاريخ التقرير</label>
            <input type="date" id="date" name="date" class="block w-full p-2 pl-10 text-sm text-slate-900 placeholder-slate-400 border border-slate-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500" placeholder="تاريخ التقرير">
        </div>
        <div class="mb-4">
            <label for="status" class="block text-sm font-medium text-slate-900">حالة التقرير</label>
            <select id="status" name="status" class="block w-full p-2 pl-10 text-sm text-slate-900 placeholder-slate-400 border border-slate-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500">
                <option value="">اختر حالة التقرير</option>
                <option value="pending">قيد الانتظار</option>
                <option value="in_progress">في طور الإنجاز</option>
                <option value="completed">مكتمل</option>
            </select>
        </div>
        <button type="submit" id="submit-btn" class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">إضافة التقرير</button>
    </form>
</div>

<script>
    // AJAX form submission
    document.getElementById('create-report-form').addEventListener('submit', function(event) {
        event.preventDefault();
        var formData = new FormData(this);
        $.ajax({
            type: 'POST',
            url: '../backend/الإبلاغات.php',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                if (response === 'success') {
                    window.location.href = 'list_الإبلاغات.php';
                } else {
                    alert('Error submitting form');
                }
            },
            error: function(xhr, status, error) {
                alert('Error submitting form');
            }
        });
    });
</script>


**form_validation.php**

function validateForm($data) {
    $errors = array();
    if (empty($data['title'])) {
        $errors[] = 'Please enter a title';
    }
    if (empty($data['description'])) {
        $errors[] = 'Please enter a description';
    }
    if (empty($data['date'])) {
        $errors[] = 'Please enter a date';
    }
    if (empty($data['status'])) {
        $errors[] = 'Please select a status';
    }
    return $errors;
}

function insertData($data) {
    // Insert data into database
    // ...
    return true;
}


Note: This code assumes that you have a `header.php`, `nav.php`, `footer.php`, and `list_الإبلاغات.php` files in your project. You will need to modify the code to fit your specific project structure and database schema.