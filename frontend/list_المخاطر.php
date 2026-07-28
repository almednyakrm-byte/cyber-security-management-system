**list_المخاطر.php**

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
    <title>المخاطر</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f7f7f7;
        }
        .header {
            background-color: #1f2937;
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
            background-color: #1f2937;
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
            box-shadow: 0 0 0 0.25rem rgba(13, 30, 41, 0.25);
        }
    </style>
</head>
<body>
    <div class="header">
        <a href="index.php">الرئيسية</a>
        <span class="text-lg font-bold text-slate-900">المخاطر</span>
        <a href="profile.php">حسابي</a>
        <a href="logout.php">تسجيل الخروج</a>
    </div>
    <div class="container mx-auto p-4">
        <h1 class="text-3xl font-bold text-slate-900 mb-4">قائمة المخاطر</h1>
        <div class="flex justify-between mb-4">
            <button class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded" onclick="location.href='create_المخاطر.php'">إضافة جديد</button>
            <div class="search-bar">
                <input type="search" id="search" placeholder="بحث...">
                <button class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded" onclick="searchRecords()">بحث</button>
            </div>
        </div>
        <table class="table">
            <thead>
                <tr>
                    <th>اسم المخاطر</th>
                    <th>وصف المخاطر</th>
                    <th>حذف</th>
                </tr>
            </thead>
            <tbody id="records">
                <!-- Records will be loaded here -->
            </tbody>
        </table>
    </div>

    <script>
        // Fetch records from backend
        async function fetchRecords() {
            try {
                const response = await fetch('../backend/المخاطر.php', { method: 'GET' });
                const data = await response.json();
                const records = document.getElementById('records');
                records.innerHTML = '';
                data.forEach(record => {
                    const row = document.createElement('tr');
                    row.innerHTML = `
                        <td>${record.اسم_المخاطر}</td>
                        <td>${record.وصف_المخاطر}</td>
                        <td>
                            <button class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded" onclick="deleteRecord(${record.id})">حذف</button>
                            <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded" onclick="location.href='edit_المخاطر.php?id=${record.id}'">تعديل</button>
                        </td>
                    `;
                    records.appendChild(row);
                });
            } catch (error) {
                console.error(error);
            }
        }

        // Search records
        function searchRecords() {
            const searchInput = document.getElementById('search');
            const searchQuery = searchInput.value.trim();
            if (searchQuery) {
                fetchRecords(searchQuery);
            } else {
                fetchRecords();
            }
        }

        // Delete record
        async function deleteRecord(id) {
            try {
                const response = await fetch('../backend/المخاطر.php', { method: 'DELETE', headers: { 'Content-Type': 'application/json' }, body: JSON.stringify({ id }) });
                const data = await response.json();
                if (data.success) {
                    fetchRecords();
                } else {
                    alert('حذف المخاطر غير موفق');
                }
            } catch (error) {
                console.error(error);
            }
        }

        // Fetch records on page load
        fetchRecords();
    </script>
</body>
</html>

This code includes a premium Tailwind UI layout, session validation, and a list of records with actions. The search bar filters elements in real-time, and the AJAX Javascript uses Fetch API to fetch records from the backend. The delete button sends a DELETE request to the backend to delete the record.