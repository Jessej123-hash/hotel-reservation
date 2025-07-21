<?php
session_start();
require_once '../includes/config.php';
require_once '../includes/navbar.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Our Rooms - Skyview Hotel</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="../assets/css/style.css" rel="stylesheet">
</head>
<body class="bg-gray-50">
    <!-- Hero Section -->
    <section class="hero text-white py-20">
        <div class="container mx-auto px-4 text-center">
            <h1 class="text-4xl md:text-5xl font-bold mb-4">Our Rooms</h1>
            <p class="text-xl mb-8">Luxury accommodations for every type of traveler</p>
        </div>
    </section>
    
    <!-- Rooms Section -->
    <section class="py-16">
        <div class="container mx-auto px-4">
            <div class="flex justify-between items-center mb-8">
                <h2 class="text-3xl font-bold">Available Rooms</h2>
                <div class="flex space-x-4">
                    <button id="filter-all" class="px-4 py-2 bg-blue-600 text-white rounded-lg">All</button>
                    <button id="filter-single" class="px-4 py-2 bg-gray-200 rounded-lg">Single</button>
                    <button id="filter-double" class="px-4 py-2 bg-gray-200 rounded-lg">Double</button>
                    <button id="filter-suite" class="px-4 py-2 bg-gray-200 rounded-lg">Suite</button>
                    <button id="filter-family" class="px-4 py-2 bg-gray-200 rounded-lg">Family</button>
                </div>
            </div>
            
            <div id="rooms-container" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <!-- Rooms will be loaded here via JavaScript -->
            </div>
        </div>
    </section>
    
    <!-- Room Details Modal -->
    <div id="room-modal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex items-center justify-center p-4">
        <div class="bg-white rounded-lg w-full max-w-4xl max-h-[90vh] overflow-y-auto">
            <div class="p-6">
                <div class="flex justify-between items-start mb-4">
                    <h3 id="modal-room-type" class="text-2xl font-bold"></h3>
                    <button id="close-modal" class="text-gray-500 hover:text-gray-700">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div>
                        <img id="modal-room-image" src="" alt="" class="w-full h-64 object-cover rounded-lg">
                        <p id="modal-room-description" class="mt-4 text-gray-600"></p>
                    </div>
                    
                    <div>
                        <div class="bg-gray-50 p-4 rounded-lg mb-6">
                            <h4 class="font-bold mb-2">Room Details</h4>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <span class="text-gray-500">Price:</span>
                                    <span id="modal-room-price" class="font-bold block"></span>
                                </div>
                                <div>
                                    <span class="text-gray-500">Capacity:</span>
                                    <span id="modal-room-capacity" class="font-bold block"></span>
                                </div>
                                <div>
                                    <span class="text-gray-500">Availability:</span>
                                    <span id="modal-room-availability" class="font-bold block"></span>
                                </div>
                            </div>
                        </div>
                        
                        <form id="booking-form" class="space-y-4">
                            <input type="hidden" id="room-id" name="room_id">
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label for="check-in" class="block text-sm font-medium text-gray-700">Check-in</label>
                                    <input type="date" id="check-in" name="check_in" class="form-input mt-1 block w-full" required min="<?= date('Y-m-d') ?>">
                                </div>
                                <div>
                                    <label for="check-out" class="block text-sm font-medium text-gray-700">Check-out</label>
                                    <input type="date" id="check-out" name="check_out" class="form-input mt-1 block w-full" required>
                                </div>
                            </div>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label for="adults" class="block text-sm font-medium text-gray-700">Adults</label>
                                    <select id="adults" name="adults" class="form-input mt-1 block w-full" required>
                                        <option value="1">1</option>
                                        <option value="2" selected>2</option>
                                        <option value="3">3</option>
                                        <option value="4">4</option>
                                    </select>
                                </div>
                                <div>
                                    <label for="children" class="block text-sm font-medium text-gray-700">Children</label>
                                    <select id="children" name="children" class="form-input mt-1 block w-full">
                                        <option value="0" selected>0</option>
                                        <option value="1">1</option>
                                        <option value="2">2</option>
                                        <option value="3">3</option>
                                    </select>
                                </div>
                            </div>
                            
                            <div>
                                <label for="special-requests" class="block text-sm font-medium text-gray-700">Special Requests</label>
                                <textarea id="special-requests" name="special_requests" rows="3" class="form-input mt-1 block w-full"></textarea>
                            </div>
                            
                            <div id="availability-message" class="hidden p-3 rounded-lg mb-4"></div>
                            
                            <div id="booking-summary" class="hidden bg-blue-50 p-4 rounded-lg mb-4">
                                <h4 class="font-bold mb-2">Booking Summary</h4>
                                <div class="flex justify-between mb-1">
                                    <span>Room Price:</span>
                                    <span id="summary-room-price"></span>
                                </div>
                                <div class="flex justify-between mb-1">
                                    <span>Nights:</span>
                                    <span id="summary-nights"></span>
                                </div>
                                <div class="flex justify-between font-bold">
                                    <span>Total:</span>
                                    <span id="summary-total"></span>
                                </div>
                            </div>
                            
                            <div class="flex justify-end space-x-4">
                                <button type="button" id="check-availability" class="btn bg-gray-600 hover:bg-gray-700">
                                    Check Availability
                                </button>
                                <button type="submit" id="book-now" class="btn bg-blue-600 hover:bg-blue-700 hidden">
                                    Book Now
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script src="../assets/js/main.js"></script>
    <script>
        // Load rooms
        document.addEventListener('DOMContentLoaded', function() {
            fetchRooms();
            
            // Filter buttons
            document.querySelectorAll('[id^="filter-"]').forEach(button => {
                button.addEventListener('click', function() {
                    const filter = this.id.replace('filter-', '');
                    
                    // Update active button
                    document.querySelectorAll('[id^="filter-"]').forEach(btn => {
                        btn.classList.remove('bg-blue-600', 'text-white');
                        btn.classList.add('bg-gray-200');
                    });
                    
                    if (filter === 'all') {
                        this.classList.add('bg-blue-600', 'text-white');
                        this.classList.remove('bg-gray-200');
                        fetchRooms();
                    } else {
                        this.classList.add('bg-blue-600', 'text-white');
                        this.classList.remove('bg-gray-200');
                        fetchRooms(filter);
                    }
                });
            });
            
            // Close modal
            document.getElementById('close-modal').addEventListener('click', function() {
                document.getElementById('room-modal').classList.add('hidden');
            });
            
            // Check availability
            document.getElementById('check-availability').addEventListener('click', function() {
                checkAvailability();
            });
            
            // Booking form submission
            document.getElementById('booking-form').addEventListener('submit', function(e) {
                e.preventDefault();
                createBooking();
            });
        });
        
        function fetchRooms(filter = null) {
            let url = '../api/rooms.php?action=get_all';
            if (filter) {
                url += `&type=${filter}`;
            }
            
            fetch(url)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const container = document.getElementById('rooms-container');
                        container.innerHTML = '';
                        
                        data.data.forEach(room => {
                            const roomCard = document.createElement('div');
                            roomCard.className = 'room-card bg-white rounded-lg overflow-hidden shadow-lg';
                            roomCard.innerHTML = `
                                <img src="../assets/images/${room.image_url || 'default-room.jpg'}" alt="${room.room_type}" class="w-full h-48 object-cover">
                                <div class="p-6">
                                    <h3 class="text-xl font-bold mb-2">${room.room_type}</h3>
                                    <p class="text-gray-600 mb-4">${room.description.substring(0, 100)}...</p>
                                    <div class="flex justify-between items-center">
                                        <span class="font-bold text-lg">$${room.price}/night</span>
                                        <button onclick="openRoomModal(${room.id})" class="text-blue-600 hover:underline">View Details</button>
                                    </div>
                                </div>
                            `;
                            container.appendChild(roomCard);
                        });
                    } else {
                        alert('Failed to load rooms: ' + data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred while loading rooms');
                });
        }
        
        function openRoomModal(roomId) {
            fetch(`../api/rooms.php?action=get_by_id&id=${roomId}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const room = data.data;
                        
                        // Populate modal
                        document.getElementById('modal-room-type').textContent = room.room_type;
                        document.getElementById('modal-room-image').src = `../assets/images/${room.image_url || 'default-room.jpg'}`;
                        document.getElementById('modal-room-image').alt = room.room_type;
                        document.getElementById('modal-room-description').textContent = room.description;
                        document.getElementById('modal-room-price').textContent = `$${room.price}/night`;
                        document.getElementById('modal-room-capacity').textContent = `${room.capacity} ${room.capacity > 1 ? 'people' : 'person'}`;
                        document.getElementById('modal-room-availability').textContent = room.available ? 'Available' : 'Not Available';
                        document.getElementById('room-id').value = room.id;
                        
                        // Reset form
                        document.getElementById('booking-form').reset();
                        document.getElementById('availability-message').classList.add('hidden');
                        document.getElementById('booking-summary').classList.add('hidden');
                        document.getElementById('book-now').classList.add('hidden');
                        
                        // Show modal
                        document.getElementById('room-modal').classList.remove('hidden');
                    } else {
                        alert('Failed to load room details: ' + data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred while loading room details');
                });
        }
        
        function checkAvailability() {
            const roomId = document.getElementById('room-id').value;
            const checkIn = document.getElementById('check-in').value;
            const checkOut = document.getElementById('check-out').value;
            
            if (!checkIn || !checkOut) {
                alert('Please select check-in and check-out dates');
                return;
            }
            
            fetch(`../api/rooms.php?action=check_availability&room_id=${roomId}&check_in=${checkIn}&check_out=${checkOut}`)
                .then(response => response.json())
                .then(data => {
                    const message = document.getElementById('availability-message');
                    
                    if (data.success) {
                        if (data.available) {
                            message.innerHTML = '<p class="text-green-700">Room is available for the selected dates!</p>';
                            message.classList.remove('hidden', 'bg-red-100');
                            message.classList.add('bg-green-100');
                            
                            // Calculate and show booking summary
                            calculateBookingSummary();
                        } else {
                            message.innerHTML = '<p class="text-red-700">Room is not available for the selected dates. Please try different dates.</p>';
                            message.classList.remove('hidden', 'bg-green-100');
                            message.classList.add('bg-red-100');
                            
                            document.getElementById('booking-summary').classList.add('hidden');
                            document.getElementById('book-now').classList.add('hidden');
                        }
                    } else {
                        message.innerHTML = `<p class="text-red-700">Error: ${data.message}</p>`;
                        message.classList.remove('hidden', 'bg-green-100');
                        message.classList.add('bg-red-100');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred while checking availability');
                });
        }
        
        function calculateBookingSummary() {
            const checkIn = new Date(document.getElementById('check-in').value);
            const checkOut = new Date(document.getElementById('check-out').value);
            const nights = Math.ceil((checkOut - checkIn) / (1000 * 60 * 60 * 24));
            
            const roomPrice = parseFloat(document.getElementById('modal-room-price').textContent.replace('$', '').replace('/night', ''));
            const total = roomPrice * nights;
            
            document.getElementById('summary-room-price').textContent = `$${roomPrice.toFixed(2)}/night`;
            document.getElementById('summary-nights').textContent = `${nights} night${nights > 1 ? 's' : ''}`;
            document.getElementById('summary-total').textContent = `$${total.toFixed(2)}`;
            
            document.getElementById('booking-summary').classList.remove('hidden');
            document.getElementById('book-now').classList.remove('hidden');
        }
        
        function createBooking() {
            const formData = {
                room_id: document.getElementById('room-id').value,
                check_in: document.getElementById('check-in').value,
                check_out: document.getElementById('check-out').value,
                adults: document.getElementById('adults').value,
                children: document.getElementById('children').value,
                special_requests: document.getElementById('special-requests').value
            };
            
            fetch('../api/bookings.php?action=create', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(formData)
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Booking created successfully!');
                    document.getElementById('room-modal').classList.add('hidden');
                    
                    // Redirect to bookings page if user is logged in
                    <?php if(isset($_SESSION['user_id'])): ?>
                        window.location.href = 'payment.php?booking_id=' + data.booking_id;
                    <?php else: ?>
                        window.location.href = 'login.php';
                    <?php endif; ?>
                } else {
                    alert('Failed to create booking: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred while creating booking');
            });
        }
    </script>
    
    <?php include '../includes/footer.php'; ?>
</body>
</html>