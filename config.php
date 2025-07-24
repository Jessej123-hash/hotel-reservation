<?php
// Database Configuration
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'skms_hotel');

// Base URL
define('BASE_URL', 'http://localhost/skms/');

// App Name
define('APP_NAME', 'Skyview Hotel Management System');

// Security Settings
session_start([
    'cookie_lifetime' => 86400,
    'cookie_secure'   => false, // Enable in production with HTTPS
    'cookie_httponly' => true,
    'cookie_samesite' => 'Lax'
]);

// CSRF Protection
function csrf_token() {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

// Redirect function
function redirect($url) {
    header("Location: " . BASE_URL . $url);
    exit();
}
?>
