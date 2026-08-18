<?php
session_start();

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Dashboard - Hotel Management System</title>

    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100">

    <!-- Sidebar -->
    <aside class="fixed left-0 top-0 h-screen w-64 bg-white shadow-lg">

        <div class="p-6">
            <h1 class="text-2xl font-bold text-[#ff385c]">
                Hotel Manager
            </h1>
        </div>

        <nav class="px-4">

            <a href="index.php"
               class="block px-4 py-3 rounded-lg bg-[#ff385c] text-white font-semibold mb-2">
                Dashboard
            </a>

            <a href="#"
               class="block px-4 py-3 rounded-lg hover:bg-gray-100 mb-2">
                Rooms
            </a>

            <a href="#"
               class="block px-4 py-3 rounded-lg hover:bg-gray-100 mb-2">
                Customers
            </a>

            <a href="#"
               class="block px-4 py-3 rounded-lg hover:bg-gray-100 mb-2">
                Reservations
            </a>

            <a href="#"
               class="block px-4 py-3 rounded-lg hover:bg-gray-100 mb-2">
                Payments
            </a>

            <a href="#"
               class="block px-4 py-3 rounded-lg hover:bg-gray-100 mb-2">
                Staff
            </a>

            <a href="logout.php"
               class="block px-4 py-3 rounded-lg text-red-500 hover:bg-red-50 font-semibold mt-6">
                Logout
            </a>

        </nav>

    </aside>


    <!-- Main Content -->
    <main class="ml-64 p-8">

        <!-- Header -->
        <div class="flex justify-between items-center mb-8">

            <div>
                <h2 class="text-3xl font-bold text-gray-800">
                    Dashboard
                </h2>

                <p class="text-gray-500 mt-1">
                    Welcome back,
                    <?php echo htmlspecialchars($_SESSION["full_name"]); ?>!
                </p>
            </div>

            <div class="text-right">
                <p class="font-semibold">
                    <?php echo htmlspecialchars($_SESSION["full_name"]); ?>
                </p>

                <p class="text-sm text-gray-500">
                    <?php echo htmlspecialchars($_SESSION["role"]); ?>
                </p>
            </div>

        </div>


        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">

            <!-- Rooms -->
            <div class="bg-white p-6 rounded-xl shadow">

                <h3 class="text-gray-500 font-semibold">
                    Total Rooms
                </h3>

                <p class="text-3xl font-bold mt-2">
                    0
                </p>

            </div>


            <!-- Customers -->
            <div class="bg-white p-6 rounded-xl shadow">

                <h3 class="text-gray-500 font-semibold">
                    Customers
                </h3>

                <p class="text-3xl font-bold mt-2">
                    0
                </p>

            </div>


            <!-- Reservations -->
            <div class="bg-white p-6 rounded-xl shadow">

                <h3 class="text-gray-500 font-semibold">
                    Reservations
                </h3>

                <p class="text-3xl font-bold mt-2">
                    0
                </p>

            </div>


            <!-- Revenue -->
            <div class="bg-white p-6 rounded-xl shadow">

                <h3 class="text-gray-500 font-semibold">
                    Total Revenue
                </h3>

                <p class="text-3xl font-bold mt-2">
                    ৳0
                </p>

            </div>

        </div>


        <!-- Recent Activity -->
        <div class="bg-white rounded-xl shadow mt-8 p-6">

            <h3 class="text-xl font-bold mb-4">
                Recent Activity
            </h3>

            <p class="text-gray-500">
                No recent activity.
            </p>

        </div>

    </main>

</body>

</html>