**list_التحليلات.php**

<?php
// Session validation
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>التحليلات</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f7f7f7;
        }
        .header {
            background-color: #2d3748;
            color: #fff;
            padding: 1rem;
            text-align: center;
        }
        .header a {
            color: #fff;
            text-decoration: none;
        }
        .header a:hover {
            color: #ccc;
        }
        .table {
            background-color: #fff;
            padding: 1rem;
            border-radius: 0.5rem;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .table th, .table td {
            padding: 0.5rem;
            border-bottom: 1px solid #ddd;
        }
        .table th {
            background-color: #f0f0f0;
        }
        .table td {
            text-align: center;
        }
        .table .btn {
            background-color: #2d3748;
            color: #fff;
            border: none;
            padding: 0.5rem 1rem;
            border-radius: 0.25rem;
            cursor: pointer;
        }
        .table .btn:hover {
            background-color: #3b4453;
        }
        .search-bar {
            padding: 1rem;
            background-color: #f7f7f7;
            border: 1px solid #ddd;
            border-radius: 0.5rem;
        }
        .search-bar input[type="search"] {
            padding: 0.5rem;
            border: none;
            border-radius: 0.25rem;
            width: 100%;
        }
        .search-bar input[type="search"]:focus {
            outline: none;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>
<body>
    <div class="header">
        <a href="index.php">الرئيسية</a>
        <span class="text-lg font-bold">مركز التحليلات</span>
        <a href="logout.php">تسجيل الخروج</a>
        <span class="text-lg font-bold">مركز التحليلات</span>
        <span class="text-lg font-bold">اسم المستخدم: <?php echo $_SESSION['username']; ?></span>
    </div>
    <div class="container mx-auto p-4">
        <div class="flex justify-between mb-4">
            <h2 class="text-2xl font-bold">قائمة التحليلات</h2>
            <a href="create_التحليلات.php" class="btn">إضافة جديد</a>
        </div>
        <div class="search-bar">
            <input type="search" id="search-input" placeholder="بحث...">
            <button class="btn" id="search-btn">بحث</button>
        </div>
        <table class="table">
            <thead>
                <tr>
                    <th>رقم التحليل</th>
                    <th>اسم التحليل</th>
                    <th>تاريخ التحليل</th>
                    <th>حالة التحليل</th>
                    <th>إجراءات</th>
                </tr>
            </thead>
            <tbody id="table-body">
                <!-- Table records will be loaded here -->
            </tbody>
        </table>
    </div>

    <script>
        // Fetch API to load table records
        const searchInput = document.getElementById('search-input');
        const searchBtn = document.getElementById('search-btn');
        const tableBody = document.getElementById('table-body');

        searchBtn.addEventListener('click', async () => {
            const searchQuery = searchInput.value.trim();
            const response = await fetch(`../backend/التحليلات.php?search=${searchQuery}`);
            const data = await response.json();
            const tableHtml = data.map((record) => {
                return `
                    <tr>
                        <td>${record.id}</td>
                        <td>${record.name}</td>
                        <td>${record.date}</td>
                        <td>${record.status}</td>
                        <td>
                            <a href="edit_التحليلات.php?id=${record.id}" class="btn">تعديل</a>
                            <button class="btn" onclick="deleteRecord(${record.id})">حذف</button>
                        </td>
                    </tr>
                `;
            }).join('');
            tableBody.innerHTML = tableHtml;
        });

        // Delete record using AJAX
        async function deleteRecord(id) {
            const response = await fetch(`../backend/التحليلات.php?action=delete&id=${id}`, {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json'
                }
            });
            if (response.ok) {
                alert('تم حذف التحليل بنجاح');
                window.location.reload();
            } else {
                alert('حدث خطأ أثناء حذف التحليل');
            }
        }
    </script>
</body>
</html>


**backend/التحليلات.php**

<?php
// Database connection
$conn = mysqli_connect('localhost', 'username', 'password', 'database');

// Search query
$searchQuery = $_GET['search'] ?? '';

// Fetch records
$query = "SELECT * FROM analysis";
if ($searchQuery) {
    $query .= " WHERE name LIKE '%$searchQuery%'";
}
$result = mysqli_query($conn, $query);

// Fetch data
$data = array();
while ($row = mysqli_fetch_assoc($result)) {
    $data[] = $row;
}

// Output data
echo json_encode($data);

// Delete record
if (isset($_GET['action']) && $_GET['action'] === 'delete') {
    $id = $_GET['id'];
    $query = "DELETE FROM analysis WHERE id = '$id'";
    mysqli_query($conn, $query);
    echo 'Record deleted successfully';
}
?>