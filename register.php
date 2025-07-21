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
    <title>Register - Skyview Hotel</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="../assets/css/style.css" rel="stylesheet">
</head>
<body class="bg-gray-50">
    <!-- Hero Section -->
    <section class="hero text-white py-20">
        <div class="container mx-auto px-4 text-center">
            <h1 class="text-4xl md:text-5xl font-bold mb-4">Register</h1>
            <p class="text-xl mb-8">Create an account to book rooms and manage your reservations</p>
        </div>
    </section>
    
    <!-- Register Form -->
    <section class="py-16">
        <div class="container mx-auto px-4">
            <div class="max-w-md mx-auto bg-white rounded-lg shadow-lg overflow-hidden">
                <div class="p-8">
                    <div id="register-alert" class="hidden mb-6 p-4 rounded-lg"></div>
                    
                    <form id="register-form" class="space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="first-name" class="block text-sm font-medium text-gray-700 mb-2">First Name</label>
                                <input type="text" id="first-name" name="first_name" class="form-input w-full" placeholder="John" required>
                            </div>
                            <div>
                                <label for="last-name" class="block text-sm font-medium text-gray-700 mb-2">Last Name</label>
                                <input type="text" id="last-name" name="last_name" class="form-input w-full" placeholder="Doe" required>
                            </div>
                        </div>
                        
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email Address</label>
                            <input type="email" id="email" name="email" class="form-input w-full" placeholder="your@email.com" required>
                        </div>
                        
                        <div>
                            <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">Phone Number</label>
                            <input type="tel" id="phone" name="phone" class="form-input w-full" placeholder="07XX XXX XXX" required>
                        </div>
                        
                        <div>
                            <label for="password" class="block text-sm font-medium text-gray-700 mb-2">Password</label>
                            <input type="password" id="password" name="password" class="form-input w-full" placeholder="••••••••" required minlength="6">
                        </div>
                        
                        <div>
                            <label for="confirm-password" class="block text-sm font-medium text-gray-700 mb-2">Confirm Password</label>
                            <input type="password" id="confirm-password" name="confirm_password" class="form-input w-full" placeholder="••••••••" required minlength="6">
                        </div>
                        
                        <div>
                            <button type="submit" class="btn bg-blue-600 hover:bg-blue-700 w-full">
                                Register
                            </button>
                        </div>
                    </form>
                    
                    <div class="mt-6">
                        <p class="text-center text-sm text-gray-600">
                            Already have an account? 
                            <a href="login.php" class="font-medium text-blue-600 hover:text-blue-500">Login here</a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>
    
    <script src="../assets/js/main.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('register-form').addEventListener('submit', function(e) {
                e.preventDefault();
                registerUser();
            });
        });
        
        function registerUser() {
            const firstName = document.getElementById('first-name').value;
            const lastName = document.getElementById('last-name').value;
            const email = document.getElementById('email').value;
            const phone = document.getElementById('phone').value;
            const password = document.getElementById('password').value;
            const confirmPassword = document.getElementById('confirm-password').value;
            
            // Validate password match
            if (password !== confirmPassword) {
                showAlert('error', 'Passwords do not match');
                return;
            }
            
            // Show loading state
            const submitBtn = document.querySelector('#register-form button[type="submit"]');
            submitBtn.disabled = true;
            submitBtn.innerHTML = 'Registering...';
            
            fetch('../api/auth.php?action=register', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    first_name: firstName,
                    last_name: lastName,
                    email: email,
                    phone: phone,
                    password: password,
                    confirm_password: confirmPassword
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showAlert('success', data.message + ' Redirecting to login...');
                    
                    // Redirect to login page after 2 seconds
                    setTimeout(() => {
                        window.location.href = 'login.php';
                    }, 2000);
                } else {
                    showAlert('error', data.message);
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = 'Register';
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showAlert('error', 'An error occurred during registration');
                submitBtn.disabled = false;
                submitBtn.innerHTML = 'Register';
            });
        }
        
        function showAlert(type, message) {
            const alertDiv = document.getElementById('register-alert');
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