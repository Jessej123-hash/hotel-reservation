<?php
session_start();
require_once '../includes/config.php';

header('Content-Type: application/json');

$action = $_GET['action'] ?? '';

// Process payment
if ($action === 'process' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_SESSION['user_id'])) {
        echo json_encode(['success' => false, 'message' => 'You must be logged in to make a payment']);
        exit;
    }
    
    $data = json_decode(file_get_contents('php://input'), true);
    
    $booking_id = $data['booking_id'];
    $amount = $data['amount'];
    $payment_method = $data['payment_method'];
    $transaction_id = $data['transaction_id'] ?? '';
    
    // Validate inputs
    if (empty($booking_id) || empty($amount) || empty($payment_method)) {
        echo json_encode(['success' => false, 'message' => 'Required fields are missing']);
        exit;
    }
    
    // Verify booking exists and belongs to user
    $stmt = $pdo->prepare("
        SELECT b.*, r.price
        FROM bookings b
        JOIN rooms r ON b.room_id = r.id
        WHERE b.id = ?
    ");
    $stmt->execute([$booking_id]);
    $booking = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$booking) {
        echo json_encode(['success' => false, 'message' => 'Booking not found']);
        exit;
    }
    
    if ($booking['user_id'] != $_SESSION['user_id'] && ($_SESSION['user_role'] ?? '') !== 'admin') {
        echo json_encode(['success' => false, 'message' => 'Unauthorized access']);
        exit;
    }
    
    // Calculate expected amount
    $check_in_date = new DateTime($booking['check_in']);
    $check_out_date = new DateTime($booking['check_out']);
    $nights = $check_out_date->diff($check_in_date)->days;
    $expected_amount = $booking['price'] * $nights;
    
    if ($amount != $expected_amount) {
        echo json_encode(['success' => false, 'message' => 'Payment amount does not match booking total']);
        exit;
    }
    
    // Create payment
    $stmt = $pdo->prepare("
        INSERT INTO payments (booking_id, amount, payment_method, transaction_id, status)
        VALUES (?, ?, ?, ?, 'completed')
    ");
    
    if ($stmt->execute([$booking_id, $amount, $payment_method, $transaction_id])) {
        // Update booking status
        $stmt = $pdo->prepare("UPDATE bookings SET status = 'confirmed' WHERE id = ?");
        $stmt->execute([$booking_id]);
        
        echo json_encode(['success' => true, 'message' => 'Payment processed successfully']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to process payment']);
    }
}

// Get payments for booking
elseif ($action === 'get_for_booking' && isset($_GET['booking_id'])) {
    $booking_id = $_GET['booking_id'];
    
    // Verify booking exists and belongs to user
    $stmt = $pdo->prepare("SELECT user_id FROM bookings WHERE id = ?");
    $stmt->execute([$booking_id]);
    $booking = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$booking) {
        echo json_encode(['success' => false, 'message' => 'Booking not found']);
        exit;
    }
    
    if ($booking['user_id'] != $_SESSION['user_id'] && ($_SESSION['user_role'] ?? '') !== 'admin') {
        echo json_encode(['success' => false, 'message' => 'Unauthorized access']);
        exit;
    }
    
    // Get payments
    $stmt = $pdo->prepare("SELECT * FROM payments WHERE booking_id = ? ORDER BY payment_date DESC");
    $stmt->execute([$booking_id]);
    $payments = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo json_encode(['success' => true, 'data' => $payments]);
}

// Get user payments
elseif ($action === 'get_user_payments' && isset($_SESSION['user_id'])) {
    $stmt = $pdo->prepare("
        SELECT p.*, b.check_in, b.check_out, r.room_type
        FROM payments p
        JOIN bookings b ON p.booking_id = b.id
        JOIN rooms r ON b.room_id = r.id
        WHERE b.user_id = ?
        ORDER BY p.payment_date DESC
    ");
    $stmt->execute([$_SESSION['user_id']]);
    $payments = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo json_encode(['success' => true, 'data' => $payments]);
}

else {
    echo json_encode(['success' => false, 'message' => 'Invalid action']);
}
?>