<?php
session_start();
require_once '../includes/config.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

if (!isset($_GET['booking_id'])) {
    header('Location: bookings.php');
    exit;
}

$booking_id = $_GET['booking_id'];

require_once '../includes/navbar.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Make Payment - Skyview Hotel</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="../assets/css/style.css" rel="stylesheet">
</head>
<body class="bg-gray-50">
    <!-- Hero Section -->
    <section class="hero text-white py-20">
        <div class="container mx-auto px-4 text-center">
            <h1 class="text-4xl md:text-5xl font-bold mb-4">Make Payment</h1>
            <p class="text-xl mb-8">Complete your booking by making a secure payment</p>
        </div>
    </section>
    
    <!-- Payment Section -->
    <section class="py-16">
        <div class="container mx-auto px-4">
            <div class="max-w-3xl mx-auto bg-white rounded-lg shadow-lg overflow-hidden">
                <div class="p-8">
                    <div id="payment-alert" class="hidden mb-6 p-4 rounded-lg"></div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div>
                            <h2 class="text-2xl font-bold mb-6">Booking Summary</h2>
                            <div id="booking-summary">
                                <!-- Booking summary will be loaded here via JavaScript -->
                            </div>
                        </div>
                        
                        <div>
                            <h2 class="text-2xl font-bold mb-6">Payment Method</h2>
                            <form id="payment-form" class="space-y-6">
                                <input type="hidden" id="booking-id" value="<?= $booking_id ?>">
                                
                                <div>
                                    <label for="payment-method" class="block text-sm font-medium text-gray-700 mb-2">Select Payment Method</label>
                                    <select id="payment-method" class="form-input w-full" required>
                                        <option value="">-- Select --</option>
                                        <option value="credit_card">Credit Card</option>
                                        <option value="mpesa">M-Pesa</option>
                                        <option value="paypal">PayPal</option>
                                        <option value="bank_transfer">Bank Transfer</option>
                                    </select>
                                </div>
                                
                                <div id="credit-card-fields" class="hidden space-y-4">
                                    <div>
                                        <label for="card-number" class="block text-sm font-medium text-gray-700 mb-2">Card Number</label>
                                        <input type="text" id="card-number" class="form-input w-full" placeholder="1234 5678 9012 3456">
                                    </div>
                                    
                                    <div class="grid grid-cols-2 gap-4">
                                        <div>
                                            <label for="expiry-date" class="block text-sm font-medium text-gray-700 mb-2">Expiry Date</label>
                                            <input type="text" id="expiry-date" class="form-input w-full" placeholder="MM/YY">
                                        </div>
                                        <div>
                                            <label for="cvv" class="block text-sm font-medium text-gray-700 mb-2">CVV</label>
                                            <input type="text" id="cvv" class="form-input w-full" placeholder="123">
                                        </div>
                                    </div>
                                    
                                    <div>
                                        <label for="card-name" class="block text-sm font-medium text-gray-700 mb-2">Name on Card</label>
                                        <input type="text" id="card-name" class="form-input w-full" placeholder="John Doe">
                                    </div>
                                </div>
                                
                                <div id="mpesa-fields" class="hidden space-y-4">
                                    <div>
                                        <label for="phone-number" class="block text-sm font-medium text-gray-700 mb-2">M-Pesa Phone Number</label>
                                        <input type="text" id="phone-number" class="form-input w-full" placeholder="07XX XXX XXX">
                                    </div>
                                    <p class="text-sm text-gray-500">You will receive a payment request on your phone. Please enter your M-Pesa PIN to complete the payment.</p>
                                </div>
                                
                                <div id="paypal-fields" class="hidden">
                                    <p class="text-sm text-gray-500">You will be redirected to PayPal to complete your payment.</p>
                                </div>
                                
                                <div id="bank-transfer-fields" class="hidden space-y-4">
                                    <div>
                                        <p class="text-sm font-medium text-gray-700 mb-2">Bank Details:</p>
                                        <p class="text-sm">
                                            Bank Name: Skyview Bank<br>
                                            Account Name: Skyview Hotel<br>
                                            Account Number: 1234567890<br>
                                            Branch: Nairobi CBD<br>
                                            Swift Code: SVHKENNA
                                        </p>
                                    </div>
                                    <div>
                                        <label for="transaction-reference" class="block text-sm font-medium text-gray-700 mb-2">Transaction Reference</label>
                                        <input type="text" id="transaction-reference" class="form-input w-full" placeholder="Enter your transaction reference">
                                    </div>
                                </div>
                                
                                <div class="border-t border-gray-200 pt-6">
                                    <div class="flex justify-between items-center mb-4">
                                        <span class="font-bold">Total Amount:</span>
                                        <span id="total-amount" class="font-bold text-xl">$0.00</span>
                                    </div>
                                    
                                    <button type="submit" id="submit-payment" class="btn bg-blue-600 hover:bg-blue-700 w-full">
                                        Complete Payment
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    
    <script src="../assets/js/main.js"></script>
    <script>
        // Load booking details
        document.addEventListener('DOMContentLoaded', function() {
            fetchBookingDetails();
            
            // Payment method change
            document.getElementById('payment-method').addEventListener('change', function() {
                const method = this.value;
                
                // Hide all fields
                document.getElementById('credit-card-fields').classList.add('hidden');
                document.getElementById('mpesa-fields').classList.add('hidden');
                document.getElementById('paypal-fields').classList.add('hidden');
                document.getElementById('bank-transfer-fields').classList.add('hidden');
                
                // Show selected fields
                if (method === 'credit_card') {
                    document.getElementById('credit-card-fields').classList.remove('hidden');
                } else if (method === 'mpesa') {
                    document.getElementById('mpesa-fields').classList.remove('hidden');
                } else if (method === 'paypal') {
                    document.getElementById('paypal-fields').classList.remove('hidden');
                } else if (method === 'bank_transfer') {
                    document.getElementById('bank-transfer-fields').classList.remove('hidden');
                }
            });
            
            // Payment form submission
            document.getElementById('payment-form').addEventListener('submit', function(e) {
                e.preventDefault();
                processPayment();
            });
        });
        
        function fetchBookingDetails() {
            const bookingId = document.getElementById('booking-id').value;
            
            fetch(`../api/bookings.php?action=get_by_id&id=${bookingId}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const booking = data.data;
                        
                        // Format dates
                        const checkIn = new Date(booking.check_in);
                        const checkOut = new Date(booking.check_out);
                        const nights = Math.ceil((checkOut - checkIn) / (1000 * 60 * 60 * 24));
                        
                        // Calculate total
                        const total = booking.price * nights;
                        
                        // Populate booking summary
                        const summaryDiv = document.getElementById('booking-summary');
                        summaryDiv.innerHTML = `
                            <div class="space-y-4">
                                <div>
                                    <span class="text-gray-600">Booking ID:</span>
                                    <span class="font-bold">${booking.id}</span>
                                </div>
                                <div>
                                    <span class="text-gray-600">Room Type:</span>
                                    <span class="font-bold">${booking.room_type}</span>
                                </div>
                                <div>
                                    <span class="text-gray-600">Check-In:</span>
                                    <span class="font-bold">${checkIn.toLocaleDateString()}</span>
                                </div>
                                <div>
                                    <span class="text-gray-600">Check-Out:</span>
                                    <span class="font-bold">${checkOut.toLocaleDateString()}</span>
                                </div>
                                <div>
                                    <span class="text-gray-600">Nights:</span>
                                    <span class="font-bold">${nights}</span>
                                </div>
                                <div>
                                    <span class="text-gray-600">Guests:</span>
                                    <span class="font-bold">${booking.adults} adult${booking.adults > 1 ? 's' : ''}${booking.children > 0 ? `, ${booking.children} child${booking.children > 1 ? 'ren' : ''}` : ''}</span>
                                </div>
                                <div class="border-t border-gray-200 pt-4">
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">Room Price:</span>
                                        <span>$${booking.price.toFixed(2)}/night</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">Nights:</span>
                                        <span>${nights}</span>
                                    </div>
                                    <div class="border-t border-gray-200 my-2"></div>
                                    <div class="flex justify-between font-bold">
                                        <span>Total:</span>
                                        <span>$${total.toFixed(2)}</span>
                                    </div>
                                </div>
                            </div>
                        `;
                        
                        // Update total amount
                        document.getElementById('total-amount').textContent = `$${total.toFixed(2)}`;
                    } else {
                        showAlert('error', 'Failed to load booking details: ' + data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showAlert('error', 'An error occurred while loading booking details');
                });
        }
        
        function processPayment() {
            const bookingId = document.getElementById('booking-id').value;
            const paymentMethod = document.getElementById('payment-method').value;
            
            if (!paymentMethod) {
                showAlert('error', 'Please select a payment method');
                return;
            }
            
            // Get payment details based on method
            let paymentDetails = {};
            let transactionId = '';
            
            if (paymentMethod === 'credit_card') {
                const cardNumber = document.getElementById('card-number').value;
                const expiryDate = document.getElementById('expiry-date').value;
                const cvv = document.getElementById('cvv').value;
                const cardName = document.getElementById('card-name').value;
                
                if (!cardNumber || !expiryDate || !cvv || !cardName) {
                    showAlert('error', 'Please fill all credit card details');
                    return;
                }
                
                transactionId = 'CARD-' + Math.random().toString(36).substr(2, 10).toUpperCase();
                paymentDetails = {
                    card_number: cardNumber.replace(/\s/g, ''),
                    expiry_date: expiryDate,
                    cvv: cvv,
                    card_name: cardName
                };
            } 
            else if (paymentMethod === 'mpesa') {
                const phoneNumber = document.getElementById('phone-number').value;
                
                if (!phoneNumber) {
                    showAlert('error', 'Please enter your M-Pesa phone number');
                    return;
                }
                
                transactionId = 'MPESA-' + Math.random().toString(36).substr(2, 10).toUpperCase();
                paymentDetails = {
                    phone_number: phoneNumber
                };
            } 
            else if (paymentMethod === 'bank_transfer') {
                const transactionReference = document.getElementById('transaction-reference').value;
                
                if (!transactionReference) {
                    showAlert('error', 'Please enter your transaction reference');
                    return;
                }
                
                transactionId = transactionReference;
                paymentDetails = {
                    bank_name: 'Skyview Bank',
                    account_name: 'Skyview Hotel',
                    account_number: '1234567890'
                };
            } 
            else if (paymentMethod === 'paypal') {
                transactionId = 'PAYPAL-' + Math.random().toString(36).substr(2, 10).toUpperCase();
                paymentDetails = {};
            }
            
            // Get total amount
            const totalAmount = parseFloat(document.getElementById('total-amount').textContent.replace('$', ''));
            
            // Prepare payment data
            const paymentData = {
                booking_id: bookingId,
                amount: totalAmount,
                payment_method: paymentMethod,
                transaction_id: transactionId,
                payment_details: paymentDetails
            };
            
            // Disable submit button
            const submitBtn = document.getElementById('submit-payment');
            submitBtn.disabled = true;
            submitBtn.innerHTML = 'Processing...';
            
            // Send payment request
            fetch('../api/payments.php?action=process', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(paymentData)
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showAlert('success', 'Payment processed successfully! Redirecting to bookings...');
                    
                    // Redirect to bookings page after 2 seconds
                    setTimeout(() => {
                        window.location.href = 'bookings.php';
                    }, 2000);
                } else {
                    showAlert('error', 'Payment failed: ' + data.message);
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = 'Complete Payment';
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showAlert('error', 'An error occurred while processing payment');
                submitBtn.disabled = false;
                submitBtn.innerHTML = 'Complete Payment';
            });
        }
        
        function showAlert(type, message) {
            const alertDiv = document.getElementById('payment-alert');
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