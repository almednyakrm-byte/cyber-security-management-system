**edit_التحليلات.php**

<?php
// Session validation
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Get id from URL
$id = $_GET['id'];

// Fetch existing record details via GET
$url = '../backend/التحليلات.php?id=' . $id;
$response = file_get_contents($url);
$data = json_decode($response, true);

// Check if data exists
if (empty($data)) {
    echo 'Error: Record not found.';
    exit;
}

// Set page title
$page_title = 'Edit التحليلات';

// Include header
include 'header.php';

?>

<!-- Page content -->
<div class="container mx-auto p-4 pt-6 md:p-6 lg:p-12 xl:p-12">
    <h1 class="text-3xl font-bold text-slate-900 mb-4"><?= $page_title ?></h1>
    <form id="edit-form" class="bg-white rounded-lg shadow-md p-4">
        <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
            <div>
                <label for="name" class="block text-sm font-medium text-slate-900">Name</label>
                <input type="text" id="name" name="name" class="block w-full p-2 mt-1 text-sm text-slate-900 bg-white border border-slate-300 rounded-lg focus:outline-none focus:ring focus:ring-indigo-500 focus:border-indigo-500" value="<?= $data['name'] ?>">
            </div>
            <div>
                <label for="description" class="block text-sm font-medium text-slate-900">Description</label>
                <textarea id="description" name="description" class="block w-full p-2 mt-1 text-sm text-slate-900 bg-white border border-slate-300 rounded-lg focus:outline-none focus:ring focus:ring-indigo-500 focus:border-indigo-500" rows="4"><?= $data['description'] ?></textarea>
            </div>
        </div>
        <button type="submit" class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded-lg">Update</button>
    </form>
</div>

<!-- Include footer -->
<?php include 'footer.php'; ?>

<script>
    // Fetch existing record details via GET
    fetch('../backend/التحليلات.php?id=<?= $id ?>')
        .then(response => response.json())
        .then(data => {
            // Populate form fields
            document.getElementById('name').value = data.name;
            document.getElementById('description').value = data.description;
        })
        .catch(error => console.error(error));

    // Submit form via AJAX PUT request
    document.getElementById('edit-form').addEventListener('submit', function(event) {
        event.preventDefault();
        const formData = new FormData(this);
        fetch('../backend/التحليلات.php', {
            method: 'PUT',
            body: formData
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    window.location.href = 'list_<?= $mod_slug ?>.php';
                } else {
                    console.error(data.error);
                }
            })
            .catch(error => console.error(error));
    });
</script>


**backend/التحليلات.php**

<?php
// Check if id exists
if (!isset($_GET['id'])) {
    echo json_encode(['error' => 'Invalid id']);
    exit;
}

// Get id
$id = $_GET['id'];

// Fetch existing record details
$query = "SELECT * FROM التحليلات WHERE id = '$id'";
$result = mysqli_query($conn, $query);
$data = mysqli_fetch_assoc($result);

// Check if data exists
if (empty($data)) {
    echo json_encode(['error' => 'Record not found']);
    exit;
}

// Update record via PUT request
if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
    parse_str(file_get_contents('php://input'), $formData);
    $query = "UPDATE التحليلات SET name = '$formData[name]', description = '$formData[description]' WHERE id = '$id'";
    $result = mysqli_query($conn, $query);
    if ($result) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['error' => 'Error updating record']);
    }
    exit;
}

// Output existing record details
echo json_encode($data);


Note: This code assumes you have a `header.php` and `footer.php` file that includes the HTML header and footer, respectively. You'll need to modify the code to match your specific file structure and database connection. Additionally, this code uses a simple `mysqli` connection, which is not recommended for production use. Consider using a more secure connection method, such as PDO or a framework like Laravel.