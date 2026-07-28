**edit_الإبلاغات.php**

<?php
// Session validation
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Get ID from URL
$id = $_GET['id'];

// Fetch existing record details via GET
$record = json_decode(file_get_contents('../backend/الإبلاغات.php?id=' . $id), true);

// Check if record exists
if (empty($record)) {
    echo 'Record not found';
    exit;
}

// Set page title and breadcrumbs
$page_title = 'Edit الإبلاغات';
$breadcrumbs = ['Home', 'الإبلاغات', 'Edit'];

// Include header and breadcrumbs
include 'header.php';
?>

<div class="max-w-7xl mx-auto p-4 sm:p-6 lg:p-8">
    <h1 class="text-3xl font-bold leading-tight text-slate-900 mb-4"><?= $page_title ?></h1>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb flex items-center">
            <?php foreach ($breadcrumbs as $breadcrumb) : ?>
                <li class="breadcrumb-item"><?= $breadcrumb ?></li>
            <?php endforeach; ?>
        </ol>
    </nav>

    <form id="edit-form" class="space-y-6">
        <div>
            <label for="title" class="block text-sm font-medium text-slate-900">Title</label>
            <input type="text" id="title" name="title" class="block w-full p-2 pl-10 text-sm text-slate-900 placeholder:text-slate-400 border border-slate-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500" value="<?= $record['title'] ?>">
        </div>

        <div>
            <label for="description" class="block text-sm font-medium text-slate-900">Description</label>
            <textarea id="description" name="description" class="block w-full p-2 pl-10 text-sm text-slate-900 placeholder:text-slate-400 border border-slate-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500" rows="4"><?= $record['description'] ?></textarea>
        </div>

        <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-500 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">Save Changes</button>
    </form>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const form = document.getElementById('edit-form');
        form.addEventListener('submit', function (e) {
            e.preventDefault();
            const formData = new FormData(form);
            fetch('../backend/الإبلاغات.php', {
                method: 'PUT',
                body: formData,
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        window.location.href = 'list_الإبلاغات.php';
                    } else {
                        console.error(data.error);
                    }
                })
                .catch(error => console.error(error));
        });
    });
</script>

<?php
// Include footer
include 'footer.php';
?>


**backend/الإبلاغات.php**

<?php
// Check if ID is set
if (!isset($_GET['id'])) {
    http_response_code(400);
    exit;
}

// Get ID
$id = $_GET['id'];

// Check if ID is valid
if (!is_numeric($id)) {
    http_response_code(400);
    exit;
}

// Fetch existing record details
$record = get_record($id);

// Return JSON response
echo json_encode($record);

function get_record($id) {
    // Your database query to fetch the record
    // For example:
    $db = new PDO('sqlite:database.db');
    $stmt = $db->prepare('SELECT * FROM الإبلاغات WHERE id = :id');
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    $record = $stmt->fetch();
    $db = null;
    return $record;
}
?>