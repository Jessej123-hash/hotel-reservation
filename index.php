<?php
// Start the session
session_start();

// Set the page title
$pageTitle = "Skyview Hotel - Luxury Accommodations";

// Include configuration and navbar
require_once 'includes/config.php';
require_once 'includes/navbar.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $pageTitle; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="assets/css/style.css" rel="stylesheet">
    <style>
        .hero {
            background-image: linear-gradient(rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.5)), url('assets/images/hotel-hero.jpg');
            background-size: cover;
            background-position: center;
        }
        .room-card:hover {
            transform: translateY(-5px);
            transition: transform 0.3s ease;
        }
        .payment-icon {
            width: 60px;
            height: 40px;
            object-fit: contain;
            margin: 0 5px;
        }
    </style>
</head>
<body class="font-sans">
    <!-- Hero Section -->
    <section class="hero text-white py-20">
        <div class="container mx-auto px-4 text-center">
            <h1 class="text-4xl md:text-5xl font-bold mb-4">Experience Luxury at Skyview Hotel</h1>
            <p class="text-xl mb-8">Your perfect getaway with stunning views and world-class amenities</p>
            <a href="pages/rooms.php" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-8 rounded-lg inline-block">
                Book Your Stay
            </a>
        </div>
    </section>
    
    <!-- Rooms Section -->
    <section class="py-16 bg-gray-50">
        <div class="container mx-auto px-4">
            <h2 class="text-3xl font-bold text-center mb-12">Our Rooms</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                <!-- Single Room -->
                <div class="room-card bg-white rounded-lg overflow-hidden shadow-lg">
                    <img src="assets/images/single-room.jpg" alt="Single Room" class="w-full h-48 object-cover">
                    <div class="p-6">
                        <h3 class="text-xl font-bold mb-2">Single Room</h3>
                        <p class="text-gray-600 mb-4">Perfect for solo travelers with all essential amenities.</p>
                        <div class="flex justify-between items-center">
                            <span class="font-bold text-lg">$80/night</span>
                            <a href="pages/rooms.php?type=Single" class="text-blue-600 hover:underline">View</a>
                        </div>
                    </div>
                </div>
                
                <!-- Double Room -->
                <div class="room-card bg-white rounded-lg overflow-hidden shadow-lg">
                    <img src="assets/images/double-room.jpg" alt="Double Room" class="w-full h-48 object-cover">
                    <div class="p-6">
                        <h3 class="text-xl font-bold mb-2">Double Room</h3>
                        <p class="text-gray-600 mb-4">Spacious room with a comfortable double bed.</p>
                        <div class="flex justify-between items-center">
                            <span class="font-bold text-lg">$120/night</span>
                            <a href="pages/rooms.php?type=Double" class="text-blue-600 hover:underline">View</a>
                        </div>
                    </div>
                </div>
                
                <!-- Suite -->
                <div class="room-card bg-white rounded-lg overflow-hidden shadow-lg">
                    <img src="assets/images/suite.jpg" alt="Suite" class="w-full h-48 object-cover">
                    <div class="p-6">
                        <h3 class="text-xl font-bold mb-2">Suite</h3>
                        <p class="text-gray-600 mb-4">Luxury accommodation with separate living area.</p>
                        <div class="flex justify-between items-center">
                            <span class="font-bold text-lg">$200/night</span>
                            <a href="pages/rooms.php?type=Suite" class="text-blue-600 hover:underline">View</a>
                        </div>
                    </div>
                </div>
                
                <!-- Family Room -->
                <div class="room-card bg-white rounded-lg overflow-hidden shadow-lg">
                    <img src="assets/images/family-room.jpg" alt="Family Room" class="w-full h-48 object-cover">
                    <div class="p-6">
                        <h3 class="text-xl font-bold mb-2">Family Room</h3>
                        <p class="text-gray-600 mb-4">Spacious room perfect for families with children.</p>
                        <div class="flex justify-between items-center">
                            <span class="font-bold text-lg">$180/night</span>
                            <a href="pages/rooms.php?type=Family" class="text-blue-600 hover:underline">View</a>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="text-center mt-12">
                <a href="pages/rooms.php" class="inline-block bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-8 rounded-lg">
                    View All Rooms
                </a>
            </div>
        </div>
    </section>
    
    <!-- Payment Options Section -->
    <section class="py-16 bg-blue-50">
        <div class="container mx-auto px-4">
            <h2 class="text-3xl font-bold text-center mb-12">Payment Options</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 max-w-4xl mx-auto">
                <div class="bg-white p-8 rounded-lg shadow-lg">
                    <h3 class="text-xl font-bold mb-4 text-center">Secure Online Payments</h3>
                    <p class="text-gray-600 mb-6">We accept all major credit and debit cards for your convenience. All transactions are encrypted for your security.</p>
                    
                    <div class="flex flex-wrap justify-center items-center mb-6">
                        <img src="https://cdn.jsdelivr.net/gh/devicons/devicon/icons/visa/visa-original.svg" class="payment-icon" alt="Visa">
                        <img src="https://cdn.jsdelivr.net/gh/devicons/devicon/icons/mastercard/mastercard-original.svg" class="payment-icon" alt="Mastercard">
                        <img src="https://cdn.jsdelivr.net/gh/devicons/devicon/icons/apple/apple-original.svg" class="payment-icon" alt="Apple Pay">
                        <img src="https://cdn.jsdelivr.net/gh/devicons/devicon/icons/paypal/paypal-original.svg" class="payment-icon" alt="PayPal">
                        <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/b/b7/M-PESA_LOGO-01.svg/1200px-M-PESA_LOGO-01.svg.png" class="payment-icon" alt="M-Pesa">
                    </div>
                    
                    <p class="text-sm text-gray-500 text-center">Your payment information is processed securely. We do not store your credit card details.</p>
                </div>
                
                <div class="bg-white p-8 rounded-lg shadow-lg">
                    <h3 class="text-xl font-bold mb-4 text-center">Other Payment Methods</h3>
                    <ul class="space-y-4">
                        <li class="flex items-start">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-green-500 mr-2 mt-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            <div>
                                <h4 class="font-semibold">Bank Transfer</h4>
                                <p class="text-gray-600 text-sm">Direct bank transfers to our corporate account</p>
                            </div>
                        </li>
                        <li class="flex items-start">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-green-500 mr-2 mt-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            <div>
                                <h4 class="font-semibold">Mobile Money (M-Pesa)</h4>
                                <p class="text-gray-600 text-sm">Pay via M-Pesa for quick and easy transactions</p>
                            </div>
                        </li>
                        <li class="flex items-start">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-green-500 mr-2 mt-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            <div>
                                <h4 class="font-semibold">Pay on Arrival</h4>
                                <p class="text-gray-600 text-sm">Cash payments accepted at the reception</p>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </section>
    
    <!-- Amenities Section -->
    <section class="py-16">
        <div class="container mx-auto px-4">
            <h2 class="text-3xl font-bold text-center mb-12">Our Amenities</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="text-center p-6">
                    <div class="bg-blue-100 rounded-full w-16 h-16 flex items-center justify-center mx-auto mb-4">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold mb-2">Luxury Rooms</h3>
                    <p class="text-gray-600">Spacious and elegantly designed rooms with modern amenities.</p>
                </div>
                
                <div class="text-center p-6">
                    <div class="bg-blue-100 rounded-full w-16 h-16 flex items-center justify-center mx-auto mb-4">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold mb-2">24/7 Service</h3>
                    <p class="text-gray-600">Round-the-clock concierge and room service for your convenience.</p>
                </div>
                
                <div class="text-center p-6">
                    <div class="bg-blue-100 rounded-full w-16 h-16 flex items-center justify-center mx-auto mb-4">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 15a4 4 0 004 4h9a5 5 0 10-.1-9.999 5.002 5.002 0 10-9.78 2.096A4.001 4.001 0 003 15z" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold mb-2">Free WiFi</h3>
                    <p class="text-gray-600">High-speed internet access throughout the hotel premises.</p>
                </div>
            </div>
        </div>
    </section>
    
    <?php 
    // Include footer
    require_once 'includes/footer.php'; 
    ?>
</body>
</html>