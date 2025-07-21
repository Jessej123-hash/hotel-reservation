<?php
session_start();
require_once '../includes/config.php';

if (isset($_SESSION['user_id'])) {
    header('Location: ../index.php');
    exit;
}

require_once '../includes/navbar.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Skyview Hotel</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="../assets/css/style.css" rel="stylesheet">
</head>
<body class="bg-gray-50">
    <!-- Hero Section -->
    <section class="hero text-white py-20">
        <div class="container mx-auto px-4 text-center">
            <h1 class="text-4xl md:text-5xl font-bold mb-4">Login</h1>
            <p class="text-xl mb-8">Access your account to manage bookings and payments</p>
        </div>
    </section>
    
    <!-- Login Form -->
    <section class="py-16">
        <div class="container mx-auto px-4">
            <div class="max-w-md mx-auto bg-white rounded-lg shadow-lg overflow-hidden">
                <div class="p-8">
                    <div id="login-alert" class="hidden mb-6 p-4 rounded-lg"></div>
                    
                    <form id="login-form" class="space-y-6">
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email Address</label>
                            <input type="email" id="email" name="email" class="form-input w-full" placeholder="your@email.com" required>
                        </div>
                        
                        <div>
                            <label for="password" class="block text-sm font-medium text-gray-700 mb-2">Password</label>
                            <input type="password" id="password" name="password" class="form-input w-full" placeholder="••••••••" required>
                        </div>
                        
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <input type="checkbox" id="remember-me" name="remember_me" class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                <label for="remember-me" class="ml-2 block text-sm text-gray-700">Remember me</label>
                            </div>
                            
                            <div class="text-sm">
                                <a href="reset-password.php" class="font-medium text-blue-600 hover:text-blue-500">Forgot password?</a>
                            </div>
                        </div>
                        
                        <div>
                            <button type="submit" class="btn bg-blue-600 hover:bg-blue-700 w-full">
                                Login
                            </button>
                        </div>
                    </form>
                    
                    <div class="mt-6">
                        <p class="text-center text-sm text-gray-600">
                            Don't have an account? 
                            <a href="register.php" class="font-medium text-blue-600 hover:text-blue-500">Register here</a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>
    
    <script src="../assets/js/main.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('login-form').addEventListener('submit', function(e) {
                e.preventDefault();
                loginUser();
            });
        });
        
        function loginUser() {
            const email = document.getElementById('email').value;
            const password = document.getElementById('password').value;
            const rememberMe = document.getElementById('remember-me').checked;
            
            // Show loading state
            const submitBtn = document.querySelector('#login-form button[type="submit"]');
            submitBtn.disabled = true;
            submitBtn.innerHTML = 'Logging in...';
            
            fetch('../api/auth.php?action=login', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    email: email,
                    password: password,
                    remember_me: rememberMe
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showAlert('success', 'Login successful! Redirecting...');
                    
                    // Redirect to homepage after 1 second
                    setTimeout(() => {
                        window.location.href = '../index.php';
                    }, 1000);
                } else {
                    showAlert('error', data.message);
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = 'Login';
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showAlert('error', 'An error occurred during login');
                submitBtn.disabled = false;
                submitBtn.innerHTML = 'Login';
            });
        }
        
        function showAlert(type, message) {
            const alertDiv = document.getElementById('login-alert');
            alertDiv.innerHTML = `<p>${message}</p>`;
            alertDiv.classList.remove('hidden', 'bg-red-100', 'text-red-700', 'bg-green-100', 'text-green-700');
            
            if (type === 'error') {
                alertDiv.classList.add('bg-red-100', 'text-red-700');
            } else {
                alertDiv.classList.add('bg-green-100', 'text-green-700');
            }
            
            alertDiv.classList.remove('hidden');
        }
    </script>
    
    <?php include '../includes/footer.php'; ?>
</body>
</html>