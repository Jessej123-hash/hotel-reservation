<nav class="bg-gray-800 text-white shadow-lg">
    <div class="container mx-auto px-4">
        <div class="flex justify-between items-center py-4">
            <div class="flex items-center space-x-4">
                <a href="<?php echo BASE_URL; ?>" class="text-2xl font-bold">Skyview Hotel</a>
            </div>
            <div class="hidden md:flex items-center space-x-8">
                <a href="<?php echo BASE_URL; ?>" class="hover:text-blue-300">Home</a>
                <a href="<?php echo BASE_URL; ?>pages/rooms.php" class="hover:text-blue-300">Rooms</a>
                <a href="<?php echo BASE_URL; ?>pages/bookings.php" class="hover:text-blue-300">Bookings</a>
                <?php if(isset($_SESSION['user_id'])): ?>
                    <a href="<?php echo BASE_URL; ?>pages/dashboard.php" class="hover:text-blue-300">Dashboard</a>
                    <a href="<?php echo BASE_URL; ?>api/auth.php?action=logout" class="bg-red-600 hover:bg-red-700 px-4 py-2 rounded">Logout</a>
                <?php else: ?>
                    <a href="<?php echo BASE_URL; ?>pages/login.php" class="hover:text-blue-300">Login</a>
                    <a href="<?php echo BASE_URL; ?>pages/register.php" class="bg-blue-600 hover:bg-blue-700 px-4 py-2 rounded">Register</a>
                <?php endif; ?>
            </div>
            <div class="md:hidden">
                <button class="mobile-menu-button p-2 focus:outline-none">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                    </svg>
                </button>
            </div>
        </div>
    </div>
    <!-- Mobile menu -->
    <div class="mobile-menu hidden md:hidden bg-gray-700">
        <div class="container mx-auto px-4 py-2 flex flex-col space-y-3">
            <a href="<?php echo BASE_URL; ?>" class="block py-2 hover:text-blue-300">Home</a>
            <a href="<?php echo BASE_URL; ?>pages/rooms.php" class="block py-2 hover:text-blue-300">Rooms</a>
            <a href="<?php echo BASE_URL; ?>pages/bookings.php" class="block py-2 hover:text-blue-300">Bookings</a>
            <?php if(isset($_SESSION['user_id'])): ?>
                <a href="<?php echo BASE_URL; ?>pages/dashboard.php" class="block py-2 hover:text-blue-300">Dashboard</a>
                <a href="<?php echo BASE_URL; ?>api/auth.php?action=logout" class="block py-2 text-red-400 hover:text-red-300">Logout</a>
            <?php else: ?>
                <a href="<?php echo BASE_URL; ?>pages/login.php" class="block py-2 hover:text-blue-300">Login</a>
                <a href="<?php echo BASE_URL; ?>pages/register.php" class="block py-2 text-blue-400 hover:text-blue-300">Register</a>
            <?php endif; ?>
        </div>
    </div>
</nav>

<script>
    // Mobile menu toggle
    document.querySelector('.mobile-menu-button').addEventListener('click', function() {
        document.querySelector('.mobile-menu').classList.toggle('hidden');
    });
</script>