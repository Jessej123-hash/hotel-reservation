<?php
session_start();
require_once '../includes/config.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

require_once '../includes/navbar.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Bookings - Skyview Hotel</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="../assets/css/style.css" rel="stylesheet">
</head>
<body class="bg-gray-50">
    <!-- Hero Section -->
    <section class="hero text-white py-20">
        <div class="container mx-auto px-4 text-center">
            <h1 class="text-4xl md:text-5xl font-bold mb-4">My Bookings</h1>
            <p class="text-xl mb-8">View and manage your upcoming and past reservations</p>
        </div>
    </section>
    
    <!-- Bookings Section -->
    <section class="py-16">
        <div class="container mx-auto px-4">
            <div class="mb-8">
                <h2 class="text-3xl font-bold mb-4">Your Reservations</h2>
                <p class="text-gray-600">All your current and past bookings with Skyview Hotel</p>
            </div>
            
            <div class="bg-white rounded-lg shadow-lg overflow-hidden">
                <table class="responsive-table w-full">
                    <thead>
                        <tr class="bg-gray-100">
                            <th class="py-3 px-4 text-left">Room Type</th>
                            <th class="py-3 px-4 text-left">Check-In</th>
                            <th class="py-3 px-4 text-left">Check-Out</th>
                            <th class="py-3 px-4 text-left">Guests</th>
                            <th class="py-3 px-4 text-left">Status</th>
                            <th class="py-3 px-4 text-left">Actions</th>
                        </tr>
                    </thead>
                    <tbody id="bookings-table-body">
                        <!-- Bookings will be loaded here via JavaScript -->
                    </tbody>
                </table>
            </div>
        </div>
    </section>
    
    <!-- Booking Details Modal -->
    <div id="booking-modal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex items-center justify-center p-4">
        <div class="bg-white rounded-lg w-full max-w-3xl max-h-[90vh] overflow-y-auto">
            <div class="p-6">
                <div class="flex justify-between items-start mb-4">
                    <h3 id="modal-booking-title" class="text-2xl font-bold"></h3>
                    <button id="close-booking-modal" class="text-gray-500 hover:text-gray-700">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                
                <div id="booking-details" class="space-y-4">
                    <!-- Booking details will be loaded here -->
                </div>
            </div>
        </div>
    </div>
    
    <script src="../assets/js/main.js"></script>
    <script>
        // Load bookings
        document.addEventListener('DOMContentLoaded', function() {
            fetchBookings();
            
            // Close modal
            document.getElementById('close-booking-modal').addEventListener('click', function() {
                document.getElementById('booking-modal').classList.add('hidden');
            });
        });
        
        function fetchBookings() {
            fetch('../api/bookings.php?action=get_user_bookings')
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const tableBody = document.getElementById('bookings-table-body');
                        tableBody.innerHTML = '';
                        
                        if (data.data.length === 0) {
                            const row = document.createElement('tr');
                            row.innerHTML = `
                                <td colspan="6" class="py-4 px-4 text-center text-gray-500">
                                    You don't have any bookings yet. <a href="rooms.php" class="text-blue-600 hover:underline">Book a room now</a>
                                </td>
                            `;
                            tableBody.appendChild(row);
                            return;
                        }
                        
                        data.data.forEach(booking => {
                            const row = document.createElement('tr');
                            row.className = 'border-b border-gray-200 hover:bg-gray-50';
                            
                            // Format dates
                            const checkIn = new Date(booking.check_in).toLocaleDateString();
                            const checkOut = new Date(booking.check_out).toLocaleDateString();
                            
                            // Status badge
                            let statusClass = '';
                            switch(booking.status) {
                                case 'pending':
                                    statusClass = 'bg-yellow-100 text-yellow-800';
                                    break;
                                case 'confirmed':
                                    statusClass = 'bg-green-100 text-green-800';
                                    break;
                                case 'cancelled':
                                    statusClass = 'bg-red-100 text-red-800';
                                    break;
                                case 'completed':
                                    statusClass = 'bg-blue-100 text-blue-800';
                                    break;
                                default:
                                    statusClass = 'bg-gray-100 text-gray-800';
                            }
                            
                            row.innerHTML = `
                                <td class="py-3 px-4">${booking.room_type}</td>
                                <td class="py-3 px-4">${checkIn}</td>
                                <td class="py-3 px-4">${checkOut}</td>
                                <td class="py-3 px-4">${booking.adults} adult${booking.adults > 1 ? 's' : ''}${booking.children > 0 ? `, ${booking.children} child${booking.children > 1 ? 'ren' : ''}` : ''}</td>
                                <td class="py-3 px-4"><span class="px-2 py-1 rounded-full text-xs font-semibold ${statusClass}">${booking.status.charAt(0).toUpperCase() + booking.status.slice(1)}</span></td>
                                <td class="py-3 px-4">
                                    <button onclick="viewBookingDetails(${booking.id})" class="text-blue-600 hover:underline mr-2">View</button>
                                    ${booking.status === 'pending' || booking.status === 'confirmed' ? `<button onclick="cancelBooking(${booking.id})" class="text-red-600 hover:underline">Cancel</button>` : ''}
                                </td>
                            `;
                            tableBody.appendChild(row);
                        });
                    } else {
                        alert('Failed to load bookings: ' + data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred while loading bookings');
                });
        }
        
        function viewBookingDetails(bookingId) {
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
                        
                        // Status badge
                        let statusClass = '';
                        switch(booking.status) {
                            case 'pending':
                                statusClass = 'bg-yellow-100 text-yellow-800';
                                break;
                            case 'confirmed':
                                statusClass = 'bg-green-100 text-green-800';
                                break;
                            case 'cancelled':
                                statusClass = 'bg-red-100 text-red-800';
                                break;
                            case 'completed':
                                statusClass = 'bg-blue-100 text-blue-800';
                                break;
                            default:
                                statusClass = 'bg-gray-100 text-gray-800';
                        }
                        
                        // Populate modal
                        document.getElementById('modal-booking-title').textContent = `Booking #${booking.id}`;
                        
                        const detailsDiv = document.getElementById('booking-details');
                        detailsDiv.innerHTML = `
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <h4 class="font-bold mb-2">Booking Information</h4>
                                    <div class="space-y-2">
                                        <p><span class="text-gray-600">Room Type:</span> ${booking.room_type}</p>
                                        <p><span class="text-gray-600">Check-In:</span> ${checkIn.toLocaleDateString()}</p>
                                        <p><span class="text-gray-600">Check-Out:</span> ${checkOut.toLocaleDateString()}</p>
                                        <p><span class="text-gray-600">Nights:</span> ${nights}</p>
                                        <p><span class="text-gray-600">Guests:</span> ${booking.adults} adult${booking.adults > 1 ? 's' : ''}${booking.children > 0 ? `, ${booking.children} child${booking.children > 1 ? 'ren' : ''}` : ''}</p>
                                        <p><span class="text-gray-600">Status:</span> <span class="px-2 py-1 rounded-full text-xs font-semibold ${statusClass}">${booking.status.charAt(0).toUpperCase() + booking.status.slice(1)}</span></p>
                                    </div>
                                    
                                    ${booking.special_requests ? `
                                    <div class="mt-4">
                                        <h4 class="font-bold mb-2">Special Requests</h4>
                                        <p class="text-gray-600">${booking.special_requests}</p>
                                    </div>
                                    ` : ''}
                                </div>
                                
                                <div>
                                    <h4 class="font-bold mb-2">Payment Summary</h4>
                                    <div class="bg-gray-50 p-4 rounded-lg">
                                        <div class="flex justify-between mb-2">
                                            <span class="text-gray-600">Room Price:</span>
                                            <span>$${booking.price.toFixed(2)}/night</span>
                                        </div>
                                        <div class="flex justify-between mb-2">
                                            <span class="text-gray-600">Nights:</span>
                                            <span>${nights}</span>
                                        </div>
                                        <div class="border-t border-gray-200 my-2"></div>
                                        <div class="flex justify-between font-bold">
                                            <span>Total:</span>
                                            <span>$${total.toFixed(2)}</span>
                                        </div>
                                    </div>
                                    
                                    ${(booking.status === 'pending' || booking.status === 'confirmed') ? `
                                    <div class="mt-4">
                                        <button onclick="cancelBooking(${booking.id}, true)" class="btn bg-red-600 hover:bg-red-700 w-full">
                                            Cancel Booking
                                        </button>
                                    </div>
                                    ` : ''}
                                    
                                    ${booking.status === 'pending' ? `
                                    <div class="mt-4">
                                        <a href="payment.php?booking_id=${booking.id}" class="btn bg-blue-600 hover:bg-blue-700 w-full block text-center">
                                            Make Payment
                                        </a>
                                    </div>
                                    ` : ''}
                                </div>
                            </div>
                        `;
                        
                        // Show modal
                        document.getElementById('booking-modal').classList.remove('hidden');
                    } else {
                        alert('Failed to load booking details: ' + data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred while loading booking details');
                });
        }
        
        function cancelBooking(bookingId, fromModal = false) {
            if (!confirm('Are you sure you want to cancel this booking?')) {
                return;
            }
            
            fetch(`../api/bookings.php?action=cancel&id=${bookingId}`, {
                method: 'POST'
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Booking cancelled successfully');
                    if (fromModal) {
                        document.getElementById('booking-modal').classList.add('hidden');
                    }
                    fetchBookings();
                } else {
                    alert('Failed to cancel booking: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred while cancelling booking');
            });
        }
    </script>
    
    <?php include '../includes/footer.php'; ?>
</body>
</html>