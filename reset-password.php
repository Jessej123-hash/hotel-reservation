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
    <title>Reset Password - Skyview Hotel</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="../assets/css/style.css" rel="stylesheet">
</head>
<body class="bg-gray-50">
    <!-- Hero Section -->
    <section class="hero text-white py-20">
        <div class="container mx-auto px-4 text-center">
            <h1 class="text-4xl md:text-5xl font-bold mb-4">Reset Password</h1>
            <p class="text-xl mb-8">Enter your email to receive a password reset link</p>
        </div>
    </section>
    
    <!-- Reset Password Form -->
    <section class="py-16">
        <div class="container mx-auto px-4">
            <div class="max-w-md mx-auto bg-white rounded-lg shadow-lg overflow-hidden">
                <div class="p-8">
                    <div id="reset-alert" class="hidden mb-6 p-4 rounded-lg"></div>
                    
                    <form id="reset-form" class="space-y-6">
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email Address</label>
                            <input type="email" id="email" name="email" class="form-input w-full" placeholder="your@email.com" required>
                        </div>
                        
                        <div>
                            <label for="new-password" class="block text-sm font-medium text-gray-700 mb-2">New Password</label>
                            <input type="password" id="new-password" name="new_password" class="form-input w-full" placeholder="••••••••" required minlength="6">
                        </div>
                        
                        <div>
                            <label for="confirm-password" class="block text-sm font-medium text-gray-700 mb-2">Confirm New Password</label>
                            <input type="password" id="confirm-password" name="confirm_password" class="form-input w-full" placeholder="••••••••" required minlength="6">
                        </div>
                        
                        <div>
                            <button type="submit" class="btn bg-blue-600 hover:bg-blue-700 w-full">
                                Reset Password
                            </button>
                        </div>
                    </form>
                    
                    <div class="mt-6">
                        <p class="text-center text-sm text-gray-600">
                            Remember your password? 
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
            document.getElementById('reset-form').addEventListener('submit', function(e) {
                e.preventDefault();
                resetPassword();
            });
        });
        
        function resetPassword() {
            const email = document.getElementById('email').value;
            const newPassword = document.getElementById('new-password').value;
            const confirmPassword = document.getElementById('confirm-password').value;
            
            // Validate password match
            if (newPassword !== confirmPassword) {
                showAlert('error', 'Passwords do not match');
                return;
            }
            
            // Show loading state
            const submitBtn = document.querySelector('#reset-form button[type="submit"]');
            submitBtn.disabled = true;
            submitBtn.innerHTML = 'Resetting...';
            
            fetch('../api/auth.php?action=reset-password', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    email: email,
                    new_password: newPassword,
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
                    submitBtn.innerHTML = 'Reset Password';
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showAlert('error', 'An error occurred during password reset');
                submitBtn.disabled = false;
                submitBtn.innerHTML = 'Reset Password';
            });
        }
        
        function showAlert(type, message) {
            const alertDiv = document.getElementById('reset-alert');
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