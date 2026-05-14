<?php
include("db.php");

// Handle settings update
if(isset($_POST['update_settings'])){
    $setting_name = $_POST['setting_name'];
    $setting_value = $_POST['setting_value'];
    
    // Update logic here
    echo "<script>alert('Settings updated successfully');</script>";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Settings</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .sidebar-active {
            @apply border-l-4 border-blue-600 bg-blue-50;
        }
    </style>
</head>
<body class="bg-gray-100">
    <div class="flex h-screen">
        
        <!-- SIDEBAR -->
        <aside class="w-64 bg-white shadow-lg">
            <div class="p-6 border-b">
                <h1 class="text-2xl font-bold text-gray-800">Settings</h1>
            </div>
            
            <nav class="mt-6">
                <a href="#account" class="sidebar-active block px-6 py-3 text-gray-700 hover:bg-gray-100 transition">
                    <span class="font-semibold">👤 Account</span>
                </a>
                <a href="#security" class="block px-6 py-3 text-gray-700 hover:bg-gray-100 transition">
                    <span class="font-semibold">🔒 Security</span>
                </a>
                <a href="#notification" class="block px-6 py-3 text-gray-700 hover:bg-gray-100 transition">
                    <span class="font-semibold">🔔 Notifications</span>
                </a>
                <a href="#system" class="block px-6 py-3 text-gray-700 hover:bg-gray-100 transition">
                    <span class="font-semibold">⚙️ System</span>
                </a>
                <a href="#appearance" class="block px-6 py-3 text-gray-700 hover:bg-gray-100 transition">
                    <span class="font-semibold">🎨 Appearance</span>
                </a>
            </nav>
        </aside>

        <!-- MAIN CONTENT -->
        <main class="flex-1 overflow-auto">
            <div class="p-8">
                
                <!-- ACCOUNT SETTINGS -->
                <section id="account" class="bg-white rounded-lg shadow p-6 mb-6">
                    <h2 class="text-2xl font-bold text-gray-800 mb-4">Account Settings</h2>
                    <form method="POST" class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Full Name</label>
                            <input type="text" name="full_name" class="w-full p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" value="John Doe">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                            <input type="email" name="email" class="w-full p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" value="john@example.com">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Phone</label>
                            <input type="tel" name="phone" class="w-full p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" value="+1234567890">
                        </div>
                        <button type="submit" name="update_settings" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition">Save Changes</button>
                    </form>
                </section>

                <!-- SECURITY SETTINGS -->
                <section id="security" class="bg-white rounded-lg shadow p-6 mb-6">
                    <h2 class="text-2xl font-bold text-gray-800 mb-4">Security Settings</h2>
                    <form method="POST" class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Current Password</label>
                            <input type="password" name="current_password" class="w-full p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Enter current password">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">New Password</label>
                            <input type="password" name="new_password" class="w-full p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Enter new password">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Confirm Password</label>
                            <input type="password" name="confirm_password" class="w-full p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Confirm new password">
                        </div>
                        <button type="submit" name="update_settings" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition">Update Password</button>
                    </form>
                </section>

                <!-- NOTIFICATION SETTINGS -->
                <section id="notification" class="bg-white rounded-lg shadow p-6 mb-6">
                    <h2 class="text-2xl font-bold text-gray-800 mb-4">Notification Settings</h2>
                    <div class="space-y-4">
                        <label class="flex items-center space-x-3">
                            <input type="checkbox" checked class="w-5 h-5 text-blue-600 rounded">
                            <span class="text-gray-700">Email Notifications</span>
                        </label>
                        <label class="flex items-center space-x-3">
                            <input type="checkbox" checked class="w-5 h-5 text-blue-600 rounded">
                            <span class="text-gray-700">SMS Notifications</span>
                        </label>
                        <label class="flex items-center space-x-3">
                            <input type="checkbox" class="w-5 h-5 text-blue-600 rounded">
                            <span class="text-gray-700">Push Notifications</span>
                        </label>
                    </div>
                </section>

                <!-- SYSTEM SETTINGS -->
                <section id="system" class="bg-white rounded-lg shadow p-6 mb-6">
                    <h2 class="text-2xl font-bold text-gray-800 mb-4">System Settings</h2>
                    <form method="POST" class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Language</label>
                            <select name="language" class="w-full p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option>English</option>
                                <option>French</option>
                                <option>Spanish</option>
                                <option>Kinyarwanda</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Timezone</label>
                            <select name="timezone" class="w-full p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option>UTC</option>
                                <option>GMT+2</option>
                                <option>GMT+3</option>
                            </select>
                        </div>
                        <button type="submit" name="update_settings" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition">Save System Settings</button>
                    </form>
                </section>

                <!-- APPEARANCE SETTINGS -->
                <section id="appearance" class="bg-white rounded-lg shadow p-6 mb-6">
                    <h2 class="text-2xl font-bold text-gray-800 mb-4">Appearance Settings</h2>
                    <div class="space-y-4">
                        <label class="flex items-center space-x-3">
                            <input type="radio" name="theme" value="light" checked class="w-4 h-4 text-blue-600">
                            <span class="text-gray-700">Light Mode</span>
                        </label>
                        <label class="flex items-center space-x-3">
                            <input type="radio" name="theme" value="dark" class="w-4 h-4 text-blue-600">
                            <span class="text-gray-700">Dark Mode</span>
                        </label>
                        <label class="flex items-center space-x-3">
                            <input type="radio" name="theme" value="auto" class="w-4 h-4 text-blue-600">
                            <span class="text-gray-700">Auto (System Default)</span>
                        </label>
                    </div>
                </section>

            </div>
        </main>

    </div>

    <script>
        // Smooth scrolling for sidebar links
        document.querySelectorAll('a[href^="#"]').forEach(link => {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if(target) {
                    target.scrollIntoView({behavior: 'smooth'});
                    // Update active state
                    document.querySelectorAll('aside a').forEach(a => a.classList.remove('sidebar-active'));
                    this.classList.add('sidebar-active');
                }
            });
        });
    </script>
</body>
</html>
