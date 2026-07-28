<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 h-screen">
    <div class="container mx-auto p-4 pt-6 md:p-12 lg:p-24 h-full flex justify-center items-center">
        <div class="bg-white rounded-lg shadow-lg p-8 md:p-12 lg:p-24 w-full md:w-1/2 lg:w-1/3 xl:w-1/4">
            <h2 class="text-2xl font-bold text-slate-900 mb-4">Register</h2>
            <form id="register-form">
                <div class="mb-4">
                    <label for="username" class="block text-slate-900 text-sm font-bold mb-2">Username</label>
                    <input type="text" id="username" name="username" class="bg-gray-100 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-indigo-500 focus:border-indigo-500 block w-full p-2.5" required pattern="[A-Za-z\u0600-\u06FF0-9\s]+">
                    <p id="username-error" class="text-red-500 hidden"></p>
                </div>
                <div class="mb-4">
                    <label for="email" class="block text-slate-900 text-sm font-bold mb-2">Email</label>
                    <input type="email" id="email" name="email" class="bg-gray-100 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-indigo-500 focus:border-indigo-500 block w-full p-2.5" required>
                    <p id="email-error" class="text-red-500 hidden"></p>
                </div>
                <div class="mb-4">
                    <label for="password" class="block text-slate-900 text-sm font-bold mb-2">Password</label>
                    <input type="password" id="password" name="password" class="bg-gray-100 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-indigo-500 focus:border-indigo-500 block w-full p-2.5" required pattern="[A-Za-z0-9!@#$%^&*()_+=-{};:'<>,./?]+">
                    <p id="password-error" class="text-red-500 hidden"></p>
                </div>
                <button type="submit" class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">Register</button>
            </form>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#register-form').submit(function(e) {
                e.preventDefault();
                var username = $('#username').val();
                var email = $('#email').val();
                var password = $('#password').val();
                var usernameError = $('#username-error');
                var emailError = $('#email-error');
                var passwordError = $('#password-error');

                if (username.length < 3) {
                    usernameError.text('Username must be at least 3 characters long').show();
                    return false;
                } else {
                    usernameError.text('').hide();
                }

                if (!email.match(/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/)) {
                    emailError.text('Invalid email address').show();
                    return false;
                } else {
                    emailError.text('').hide();
                }

                if (password.length < 8) {
                    passwordError.text('Password must be at least 8 characters long').show();
                    return false;
                } else {
                    passwordError.text('').hide();
                }

                $.ajax({
                    type: 'POST',
                    url: '../backend/auth.php?action=register',
                    data: {
                        username: username,
                        email: email,
                        password: password
                    },
                    success: function(data) {
                        if (data == 'success') {
                            alert('Registration successful!');
                            window.location.href = 'login.php';
                        } else {
                            alert('Registration failed!');
                        }
                    }
                });
            });
        });
    </script>
</body>
</html>


This code uses the Tailwind CSS CDN to create a premium-looking registration form. It includes validation rules for the username, email, and password fields, and uses AJAX to submit the form to the `auth.php` script. The script checks for errors and displays them to the user. If the registration is successful, it redirects the user to the login page.