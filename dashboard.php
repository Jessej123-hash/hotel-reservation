<?php
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
    <title>Dashboard - Skyview Hotel</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="../assets/css/style.css" rel="stylesheet">
</head>
<body class="bg-gray-50">
    <!-- Hero Section -->
    <section class="hero text-white py-20">
        <div class="container mx-auto px-4 text-center">
            <h1 class="text-4xl md:text-5xl font-bold mb-4">Welcome, <?php echo $_SESSION['user_name']; ?></h1>
            <p class="text-xl mb-8">Manage your bookings and account details</p>
        </div>
    </section>
    
    <!-- Dashboard Section -->
    <section class="py-16">
        <div class="container mx-auto px-4">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-12">
                <!-- Upcoming Bookings Card -->
                <div class="dashboard-card bg-white">
                    <h3 class="text-xl font-bold mb-4">Upcoming Bookings</h3>
                    <p class="text-4xl font-bold text-blue-600" id="upcoming-bookings">0</p>
                </div>
                
                <!-- Total Spent Card -->
                <div class="dashboard-card bg-white">
                    <h3 class="text-xl font-bold mb-4">Total Spent</h3>
                    <p class="text-4xl font-bold text-green-600" id="total-spent">$0</p>
                </div>
                
                <!-- Account Details Card -->
                <div class="dashboard-card bg-white">
                    <h3 class="text-xl font-bold mb-4">Account Details</h3>
                    <p class="text-gray-600 mb-2">Email: <?php echo $_SESSION['user_email']; ?></p>
                    <a href="reset-password.php" class="text-blue-600 hover:underline">Change Password</a>
                </div>
            </div>
            
            <!-- Recent Bookings Table -->
            <h2 class="text-2xl font-bold mb-6">Recent Bookings</h2>
            <div class="bg-white rounded-lg shadow-lg overflow-hidden">
                <table class="responsive-table w-full">
                    <thead>
                        <tr class="bg-gray-100">
                            <th class="py-3 px-4 text-left">Room Type</th>
                            <th class="py-3 px-4 text-left">Check-In</th>
                            <th class="py-3 px-4 text-left">Check-Out</th>
                            <th class="py-3 px-4 text-left">Status</th>
                            <th class="py-3 px-4 text-left">Actions</th>
                        </tr>
                    </thead>
                    <tbody id="bookings-table">
                        <!-- Bookings will be loaded here via JavaScript -->
                    </tbody>
                </table>
            </div>
        </div>
    </section>
    
    <script src="../assets/js/main.js"></script>
    <script>
        // Load dashboard data
        document.addEventListener('DOMContentLoaded', function() {
            fetchDashboardData();
        });
        
        function fetchDashboardData() {
            fetch('../api/bookings.php?action=get_user_bookings')
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Update stats
                        const upcoming = data.data.filter(b => 
                            new Date(b.check_in) >= new Date() && 
                            b.status !== 'cancelled'
                        ).length;
                        
                        const totalSpent = data.data
                            .filter(b => b.status === 'completed' || b.status === 'confirmed')
                            .reduce((sum, booking) => {
                                const nights = Math.ceil(
                                    (new Date(booking.check_out) - new Date(booking.check_in)) / (1000 * 60 * 60 * 24)
                                );
                                return sum + (booking.price * nights);
                            }, 0);
                        
                        document.getElementById('upcoming-bookings').textContent = upcoming;
                        document.getElementById('total-spent').textContent = '$' + totalSpent.toFixed(2);
                        
                        // Populate bookings table
                        const tableBody = document.getElementById('bookings-table');
                        tableBody.innerHTML = '';
                        
                        data.data.slice(0, 5).forEach(booking => {
                            const row = document.createElement('tr');
                            row.className = 'border-b border-gray-200 hover:bg-gray-50';
                            
                            // Format dates
                            const checkIn = new Date(booking.check_in).toLocaleDateString();
                            const checkOut = new Date(booking.check_out).toLocaleDateString();
                            
                            // Status badge
                            let statusClass = '';
                            switch(booking.status) {
                                case 'pending': statusClass = 'bg-yellow-100 text-yellow-800'; break;
                                case 'confirmed': statusClass = 'bg-green-100 text-green-800'; break;
                                case 'cancelled': statusClass = 'bg-red-100 text-red-800'; break;
                                case 'completed': statusClass = 'bg-blue-100 text-blue-800'; break;
                                default: statusClass = 'bg-gray-100 text-gray-800';
                            }
                            
                            row.innerHTML = `
                                <td class="py-3 px-4">${booking.room_type}</td>
                                <td class="py-3 px-4">${checkIn}</td>
                                <td class="py-3 px-4">${checkOut}</td>
                                <td class="py-3 px-4">
                                    <span class="px-2 py-1 rounded-full text-xs font-semibold ${statusClass}">
                                        ${booking.status.charAt(0).toUpperCase() + booking.status.slice(1)}
                                    </span>
                                </td>
                                <td class="py-3 px-4">
                                    <a href="bookings.php?id=${booking.id}" class="text-blue-600 hover:underline">View</a>
                                </td>
                            `;
                            tableBody.appendChild(row);
                        });
                    }
                });
        }
    </script>
    
    <?php include '../includes/footer.php'; ?>
</body>
</html>