**list_الإبلاغات.php**

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
    <title>الإبلاغات</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f9f9f9;
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
        .search-bar input {
            width: 100%;
            padding: 1rem;
            border: none;
            border-radius: 0.5rem;
        }
        .search-bar button {
            background-color: #2d3748;
            color: #fff;
            border: none;
            padding: 1rem 2rem;
            border-radius: 0.5rem;
            cursor: pointer;
        }
        .search-bar button:hover {
            background-color: #3b4453;
        }
    </style>
</head>
<body>
    <div class="header">
        <a href="index.php">الرئيسية</a>
        <span class="text-lg font-bold">مركز الإبلاغات</span>
        <span class="float-right">
            <a href="profile.php"><?= $_SESSION['username'] ?></a>
            <a href="logout.php">تسجيل خروج</a>
        </span>
    </div>
    <div class="container mx-auto p-4">
        <h1 class="text-3xl font-bold mb-4">قائمة الإبلاغات</h1>
        <button class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded" onclick="location.href='create_الإبلاغات.php'">إضافة جديد</button>
        <div class="search-bar mt-4">
            <input type="search" id="search" placeholder="بحث...">
            <button onclick="searchRecords()">بحث</button>
        </div>
        <table class="table mt-4">
            <thead>
                <tr>
                    <th>رقم الإبلاغ</th>
                    <th>اسم المبلغ</th>
                    <th>تاريخ الإبلاغ</th>
                    <th>حالة الإبلاغ</th>
                    <th>إجراءات</th>
                </tr>
            </thead>
            <tbody id="records">
                <!-- Records will be loaded here -->
            </tbody>
        </table>
    </div>

    <script>
        // Fetch API to load records
        async function loadRecords() {
            try {
                const response = await fetch('../backend/الإبلاغات.php', {
                    method: 'GET',
                    headers: {
                        'Content-Type': 'application/json'
                    }
                });
                const data = await response.json();
                const records = document.getElementById('records');
                records.innerHTML = '';
                data.forEach(record => {
                    const row = document.createElement('tr');
                    row.innerHTML = `
                        <td>${record.id}</td>
                        <td>${record.name}</td>
                        <td>${record.date}</td>
                        <td>${record.status}</td>
                        <td>
                            <a href="edit_الإبلاغات.php?id=${record.id}" class="text-blue-500 hover:text-blue-700">تعديل</a>
                            <button class="text-red-500 hover:text-red-700" onclick="deleteRecord(${record.id})">حذف</button>
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
            const searchValue = searchInput.value.trim();
            if (searchValue !== '') {
                fetch('../backend/الإبلاغات.php', {
                    method: 'GET',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    params: {
                        search: searchValue
                    }
                })
                .then(response => response.json())
                .then(data => {
                    const records = document.getElementById('records');
                    records.innerHTML = '';
                    data.forEach(record => {
                        const row = document.createElement('tr');
                        row.innerHTML = `
                            <td>${record.id}</td>
                            <td>${record.name}</td>
                            <td>${record.date}</td>
                            <td>${record.status}</td>
                            <td>
                                <a href="edit_الإبلاغات.php?id=${record.id}" class="text-blue-500 hover:text-blue-700">تعديل</a>
                                <button class="text-red-500 hover:text-red-700" onclick="deleteRecord(${record.id})">حذف</button>
                            </td>
                        `;
                        records.appendChild(row);
                    });
                })
                .catch(error => console.error(error));
            } else {
                loadRecords();
            }
        }

        // Delete record
        function deleteRecord(id) {
            if (confirm('هل أنت متأكد من حذف هذا الإبلاغ؟')) {
                fetch('../backend/الإبلاغات.php', {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ id })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        loadRecords();
                    } else {
                        alert(data.message);
                    }
                })
                .catch(error => console.error(error));
            }
        }

        // Load records on page load
        loadRecords();
    </script>
</body>
</html>

Note: This code assumes that you have a backend API set up to handle the GET and DELETE requests. The `../backend/الإبلاغات.php` file should handle these requests and return the data in JSON format.