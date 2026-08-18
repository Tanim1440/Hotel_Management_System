<?php

session_start();
require_once "db.php";

// Check if user is logged in
if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}

// Check if user is admin
if ($_SESSION["role"] !== "admin") {
    die("Access denied. Admins only.");
}


// Get total rooms
$sql = "SELECT COUNT(*) AS total FROM rooms";
$result = $conn->query($sql);
$total_rooms = $result->fetch_assoc()["total"];


// Get available rooms
$sql = "SELECT COUNT(*) AS total FROM rooms WHERE status = 'Available'";
$result = $conn->query($sql);
$available_rooms = $result->fetch_assoc()["total"];


// Get occupied rooms
$sql = "SELECT COUNT(*) AS total FROM rooms WHERE status = 'Occupied'";
$result = $conn->query($sql);
$occupied_rooms = $result->fetch_assoc()["total"];


// Get total bookings
$sql = "SELECT COUNT(*) AS total FROM bookings";
$result = $conn->query($sql);
$total_bookings = $result->fetch_assoc()["total"];

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Admin Dashboard - Hotel Management System</title>

    <script src="https://cdn.tailwindcss.com"></script>

</head>


<body class="bg-gray-100 min-h-screen">


    <!-- Header -->

    <div class="bg-[#2c3e50] text-white px-8 py-5 flex justify-between items-center">

        <h1 class="text-2xl font-bold">
            Hotel Management System
        </h1>

        <a
            href="logout.php"
            class="bg-red-500 hover:bg-red-600 px-5 py-2 rounded-lg"
        >
            Logout
        </a>

    </div>


    <!-- Main Content -->

    <div class="max-w-6xl mx-auto px-6 py-10">


        <h2 class="text-3xl font-bold text-gray-800 mb-2">
            Admin Dashboard
        </h2>

        <p class="text-gray-600 mb-8">
            Welcome,
            <?php echo htmlspecialchars($_SESSION["full_name"]); ?>
        </p>


        <!-- Statistics -->

        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-10">


            <!-- Total Rooms -->

            <div class="bg-white rounded-xl shadow p-6">

                <p class="text-gray-500">
                    Total Rooms
                </p>

                <p class="text-3xl font-bold text-blue-600 mt-2">
                    <?php echo $total_rooms; ?>
                </p>

            </div>


            <!-- Available -->

            <div class="bg-white rounded-xl shadow p-6">

                <p class="text-gray-500">
                    Available Rooms
                </p>

                <p class="text-3xl font-bold text-green-600 mt-2">
                    <?php echo $available_rooms; ?>
                </p>

            </div>


            <!-- Occupied -->

            <div class="bg-white rounded-xl shadow p-6">

                <p class="text-gray-500">
                    Occupied Rooms
                </p>

                <p class="text-3xl font-bold text-red-600 mt-2">
                    <?php echo $occupied_rooms; ?>
                </p>

            </div>


            <!-- Bookings -->

            <div class="bg-white rounded-xl shadow p-6">

                <p class="text-gray-500">
                    Total Bookings
                </p>

                <p class="text-3xl font-bold text-purple-600 mt-2">
                    <?php echo $total_bookings; ?>
                </p>

            </div>

        </div>


        <!-- Admin Actions -->

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">


            <!-- Manage Rooms -->

            <a
                href="manage_rooms.php"
                class="bg-white rounded-xl shadow p-6 hover:shadow-lg transition"
            >

                <h3 class="text-xl font-bold text-gray-800 mb-2">
                    Manage Rooms
                </h3>

                <p class="text-gray-600">
                    Add, edit and delete hotel rooms.
                </p>

            </a>


            <!-- Manage Bookings -->

            <a
                href="manage_bookings.php"
                class="bg-white rounded-xl shadow p-6 hover:shadow-lg transition"
            >

                <h3 class="text-xl font-bold text-gray-800 mb-2">
                    Manage Bookings
                </h3>

                <p class="text-gray-600">
                    View and manage all customer bookings.
                </p>

            </a>


            <!-- User Dashboard -->

            <a
                href="dashboard.php"
                class="bg-white rounded-xl shadow p-6 hover:shadow-lg transition"
            >

                <h3 class="text-xl font-bold text-gray-800 mb-2">
                    User Dashboard
                </h3>

                <p class="text-gray-600">
                    Go to the normal customer dashboard.
                </p>

            </a>

        </div>


    </div>

</body>

</html>