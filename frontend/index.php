<?php
session_start();

// Check if user is authenticated
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
    <title>نظام إدارة الأمن السيبراني</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        .glassmorphism-card {
            background-color: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(20px);
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>
<body class="bg-slate-900 text-white h-screen">
    <div class="flex flex-col h-screen">
        <header class="bg-slate-900 py-4">
            <div class="container mx-auto px-4 flex justify-between items-center">
                <h1 class="text-3xl font-bold">نظام إدارة الأمن السيبراني</h1>
                <button class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded" onclick="location.href='logout.php'">تسجيل خروج</button>
            </div>
        </header>
        <main class="flex-1 p-4">
            <div class="container mx-auto px-4">
                <div class="glassmorphism-card p-4 mb-4">
                    <h2 class="text-2xl font-bold mb-2">مرحباً</h2>
                    <p class="text-lg">إدارة الأمن السيبراني</p>
                </div>
                <div class="glassmorphism-card p-4 mb-4">
                    <h2 class="text-2xl font-bold mb-2">إحصائيات</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="bg-slate-800 p-4 rounded">
                            <h3 class="text-lg font-bold mb-2">الإبلاغات</h3>
                            <p class="text-lg" id="reports-count"></p>
                        </div>
                        <div class="bg-slate-800 p-4 rounded">
                            <h3 class="text-lg font-bold mb-2">التحليلات</h3>
                            <p class="text-lg" id="analyses-count"></p>
                        </div>
                        <div class="bg-slate-800 p-4 rounded">
                            <h3 class="text-lg font-bold mb-2">المخاطر</h3>
                            <p class="text-lg" id="risks-count"></p>
                        </div>
                        <div class="bg-slate-800 p-4 rounded">
                            <h3 class="text-lg font-bold mb-2">التصحيحات</h3>
                            <p class="text-lg" id="patches-count"></p>
                        </div>
                    </div>
                </div>
                <div class="glassmorphism-card p-4 mb-4">
                    <h2 class="text-2xl font-bold mb-2">الرابط السريع</h2>
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <a href="reports.php" class="bg-slate-800 p-4 rounded hover:bg-slate-700">
                            <h3 class="text-lg font-bold mb-2">الإبلاغات</h3>
                        </a>
                        <a href="analyses.php" class="bg-slate-800 p-4 rounded hover:bg-slate-700">
                            <h3 class="text-lg font-bold mb-2">التحليلات</h3>
                        </a>
                        <a href="risks.php" class="bg-slate-800 p-4 rounded hover:bg-slate-700">
                            <h3 class="text-lg font-bold mb-2">المخاطر</h3>
                        </a>
                        <a href="patches.php" class="bg-slate-800 p-4 rounded hover:bg-slate-700">
                            <h3 class="text-lg font-bold mb-2">التصحيحات</h3>
                        </a>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <script>
        fetch('/api/stats')
            .then(response => response.json())
            .then(data => {
                document.getElementById('reports-count').textContent = data.reports_count;
                document.getElementById('analyses-count').textContent = data.analyses_count;
                document.getElementById('risks-count').textContent = data.risks_count;
                document.getElementById('patches-count').textContent = data.patches_count;
            })
            .catch(error => console.error(error));
    </script>
</body>
</html>


Note: You need to replace `/api/stats` with the actual API endpoint that returns the stats data. Also, make sure to handle the API response and errors accordingly.

This code uses Tailwind CSS for styling and a simple JavaScript API call to fetch the stats data. The dashboard layout is designed to look premium with a glassmorphism card layout. The color palette is set to slate-900 and indigo-500 as per your requirements.