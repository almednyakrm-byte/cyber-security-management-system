<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        body {
            background-image: linear-gradient(to bottom, #1a1d23, #2c2f36);
            background-size: 100% 300px;
            background-position: 0% 100%;
            transition: background-position 1s;
            background-attachment: fixed;
        }
        
        .glassmorphic {
            background: linear-gradient(90deg, #1a1d23, #2c2f36);
            backdrop-filter: blur(20px);
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        
        .glassmorphic::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, #1a1d23, #2c2f36);
            backdrop-filter: blur(20px);
            border-radius: 10px;
            z-index: -1;
        }
        
        .glassmorphic::after {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, #1a1d23, #2c2f36);
            backdrop-filter: blur(20px);
            border-radius: 10px;
            z-index: -2;
        }
        
        .gradient {
            background: linear-gradient(to bottom, #1a1d23, #2c2f36);
            background-size: 100% 300px;
            background-position: 0% 100%;
            transition: background-position 1s;
            background-attachment: fixed;
        }
    </style>
</head>
<body class="bg-gray-900">
    <div class="flex justify-center items-center h-screen">
        <div class="glassmorphic max-w-md mx-auto p-8 bg-white rounded-lg shadow-md">
            <h2 class="text-3xl font-bold text-slate-900 mb-4">Login</h2>
            <form id="login-form">
                <div class="mb-4">
                    <label for="username" class="block text-sm font-medium text-slate-900">Username</label>
                    <input type="text" id="username" name="username" class="block w-full px-4 py-2 mt-2 text-sm text-gray-700 placeholder:text-gray-400 border border-gray-300 rounded-md focus:outline-none focus:ring focus:ring-indigo-500 focus:border-indigo-500" placeholder="Username" pattern="[A-Za-z\u0600-\u06FF0-9\s]+">
                </div>
                <div class="mb-4">
                    <label for="password" class="block text-sm font-medium text-slate-900">Password</label>
                    <input type="password" id="password" name="password" class="block w-full px-4 py-2 mt-2 text-sm text-gray-700 placeholder:text-gray-400 border border-gray-300 rounded-md focus:outline-none focus:ring focus:ring-indigo-500 focus:border-indigo-500" placeholder="Password">
                </div>
                <button type="submit" class="w-full px-4 py-2 text-sm font-medium text-white bg-indigo-500 border border-transparent rounded-md hover:bg-indigo-600 focus:outline-none focus:ring focus:ring-indigo-500 focus:border-indigo-500">Login</button>
            </form>
            <p class="text-sm text-gray-500 mt-4">Don't have an account? <a href="register.php" class="text-indigo-500 hover:text-indigo-600">Register</a></p>
        </div>
    </div>

    <script>
        const form = document.getElementById('login-form');
        form.addEventListener('submit', async (e) => {
            e.preventDefault();
            const username = document.getElementById('username').value;
            const password = document.getElementById('password').value;
            try {
                const response = await fetch('../backend/auth.php?action=login', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ username, password })
                });
                const data = await response.json();
                if (data.success) {
                    alert('Login successful!');
                    window.location.href = 'dashboard.php';
                } else {
                    alert(data.message);
                }
            } catch (error) {
                alert('Error: ' + error.message);
            }
        });
    </script>
</body>
</html>


This code creates a premium-looking login page with a glassmorphic layout, gradients, and a form for username and password input. It uses the Tailwind CSS CDN and includes a beautiful glassmorphic layout with gradients. The form includes validation rules using standard HTML input pattern validators to support Arabic and Latin characters. The AJAX JavaScript code uses the Fetch API to submit the credentials to the `../backend/auth.php?action=login` endpoint and handles the response or error alerts dynamically. The direct link to the `register.php` page is also included.