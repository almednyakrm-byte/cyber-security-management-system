**list_التصحيحات.php**

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
    <title>التصحيفات</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f9f9f9;
        }
        .header {
            background-color: #2d3748;
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
            width: 100%;
            border-collapse: collapse;
        }
        .table th, .table td {
            border: 1px solid #ddd;
            padding: 1rem;
            text-align: left;
        }
        .table th {
            background-color: #2d3748;
            color: #fff;
        }
        .search-bar {
            width: 50%;
            padding: 1rem;
            border: 1px solid #ccc;
            border-radius: 0.5rem;
        }
        .search-bar input[type="search"] {
            width: 100%;
            padding: 1rem;
            border: none;
            border-radius: 0.5rem;
        }
        .search-bar input[type="search"]:focus {
            outline: none;
            box-shadow: 0 0 0 0.25rem rgba(13, 130, 184, 0.5);
        }
    </style>
</head>
<body>
    <div class="header">
        <a href="index.php">الصفحة الرئيسية</a>
        <span class="text-indigo-500 font-bold">مرحباً, <?php echo $_SESSION['username']; ?></span>
        <a href="logout.php" class="text-red-500">تسجيل الخروج</a>
    </div>
    <div class="container mx-auto p-4">
        <h1 class="text-3xl font-bold mb-4">التصحيفات</h1>
        <button class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded" onclick="location.href='create_التصحيحات.php'">إضافة جديد</button>
        <div class="flex justify-between mb-4">
            <input type="search" class="search-bar" id="search-input" placeholder="بحث...">
            <button class="bg-slate-900 hover:bg-slate-800 text-white font-bold py-2 px-4 rounded" onclick="searchRecords()">بحث</button>
        </div>
        <table class="table">
            <thead>
                <tr>
                    <th>العنوان</th>
                    <th>التاريخ</th>
                    <th>الإجراءات</th>
                </tr>
            </thead>
            <tbody id="records-table">
                <!-- Records will be populated here -->
            </tbody>
        </table>
    </div>

    <script>
        const searchInput = document.getElementById('search-input');
        const recordsTable = document.getElementById('records-table');

        function searchRecords() {
            const searchTerm = searchInput.value.trim();
            if (searchTerm) {
                fetch('../backend/التصحيحات.php?search=' + searchTerm)
                    .then(response => response.json())
                    .then(data => {
                        const records = data.records;
                        recordsTable.innerHTML = '';
                        records.forEach(record => {
                            const row = document.createElement('tr');
                            row.innerHTML = `
                                <td>${record.title}</td>
                                <td>${record.date}</td>
                                <td>
                                    <a href="edit_التصحيحات.php?id=${record.id}" class="text-indigo-500 hover:text-indigo-700">تعديل</a>
                                    <button class="text-red-500 hover:text-red-700" onclick="deleteRecord(${record.id})">حذف</button>
                                </td>
                            `;
                            recordsTable.appendChild(row);
                        });
                    })
                    .catch(error => console.error(error));
            } else {
                fetch('../backend/التصحيحات.php')
                    .then(response => response.json())
                    .then(data => {
                        const records = data.records;
                        recordsTable.innerHTML = '';
                        records.forEach(record => {
                            const row = document.createElement('tr');
                            row.innerHTML = `
                                <td>${record.title}</td>
                                <td>${record.date}</td>
                                <td>
                                    <a href="edit_التصحيحات.php?id=${record.id}" class="text-indigo-500 hover:text-indigo-700">تعديل</a>
                                    <button class="text-red-500 hover:text-red-700" onclick="deleteRecord(${record.id})">حذف</button>
                                </td>
                            `;
                            recordsTable.appendChild(row);
                        });
                    })
                    .catch(error => console.error(error));
            }
        }

        function deleteRecord(id) {
            if (confirm('هل تريد حذف هذا السجل؟')) {
                fetch('../backend/التصحيحات.php', {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ id: id })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        searchRecords();
                    } else {
                        alert(data.message);
                    }
                })
                .catch(error => console.error(error));
            }
        }

        searchRecords();
    </script>
</body>
</html>

**backend/التصحيحات.php**

<?php
// Database connection
require_once 'db.php';

// Search query
if (isset($_GET['search'])) {
    $searchTerm = $_GET['search'];
    $query = "SELECT * FROM تصحيفات WHERE title LIKE '%$searchTerm%' OR date LIKE '%$searchTerm%'";
} else {
    $query = "SELECT * FROM تصحيفات";
}

// Fetch records
$records = array();
$result = mysqli_query($conn, $query);
while ($row = mysqli_fetch_assoc($result)) {
    $records[] = $row;
}

// JSON response
$response = array();
$response['records'] = $records;
echo json_encode($response);

// Delete record
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $query = "DELETE FROM تصحيفات WHERE id = '$id'";
    if (mysqli_query($conn, $query)) {
        $response = array('success' => true, 'message' => 'تم حذف السجل بنجاح');
    } else {
        $response = array('success' => false, 'message' => 'حدث خطأ أثناء حذف السجل');
    }
    echo json_encode($response);
}
?>