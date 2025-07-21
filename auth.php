<?php
require_once '../includes/config.php';

header('Content-Type: application/json');

$action = $_GET['action'] ?? '';

// Register user
if ($action === 'register') {
    $data = json_decode(file_get_contents('php://input'), true);
    
    // Validate and process registration
    // ... (similar to previous implementation)
}

// Login user
elseif ($action === 'login') {
    $data = json_decode(file_get_contents('php://input'), true);
    
    $email = trim($data['email']);
    $password = $data['password'];
    
    // Check if user exists
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$user || !password_verify($password, $user['password'])) {
        echo json_encode(['success' => false, 'message' => 'Invalid email or password']);
        exit;
    }
    
    // Set session
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['user_email'] = $user['email'];
    $_SESSION['user_role'] = $user['role'];
    $_SESSION['user_name'] = $user['first_name'] . ' ' . $user['last_name'];
    
    echo json_encode(['success' => true, 'message' => 'Login successful', 'user' => [
        'id' => $user['id'],
        'name' => $user['first_name'] . ' ' . $user['last_name'],
        'email' => $user['email'],
        'role' => $user['role']
    ]]);
}

// Logout
elseif ($action === 'logout') {
    session_unset();
    session_destroy();
    echo json_encode(['success' => true, 'message' => 'Logged out successfully']);
    exit;
}

// Reset password
elseif ($action === 'reset-password') {
    $data = json_decode(file_get_contents('php://input'), true);
    
    // Validate and process password reset
    // ... (similar to previous implementation)
}

else {
    echo json_encode(['success' => false, 'message' => 'Invalid action']);
}
?>