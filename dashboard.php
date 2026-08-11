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

<body class="bg-gray-100 min-h-screen">


<div class="max-w-5xl mx-auto py-12 px-6">


    <div class="bg-white rounded-xl shadow-lg p-8">


        <div class="flex justify-between items-center mb-8">

            <h1 class="text-3xl font-bold text-[#ff385c]">
                Hotel Management System
            </h1>

            <a
                href="logout.php"
                class="bg-red-500 text-white px-5 py-2 rounded-lg hover:bg-red-600"
            >
                Logout
            </a>

        </div>


        <h2 class="text-2xl font-bold mb-2">
            Welcome,
            <?php echo htmlspecialchars($_SESSION["full_name"]); ?>
        </h2>


        <p class="text-gray-600 mb-8">
            Manage your hotel bookings from here.
        </p>


        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">


            <a
                href="rooms.php"
                class="bg-blue-500 hover:bg-blue-600 text-white p-6 rounded-xl"
            >

                <h3 class="text-xl font-bold">
                    Browse Rooms
                </h3>

                <p class="mt-2">
                    View available hotel rooms and make a booking.
                </p>

            </a>


            <a
                href="my_bookings.php"
                class="bg-green-500 hover:bg-green-600 text-white p-6 rounded-xl"
            >

                <h3 class="text-xl font-bold">
                    My Bookings
                </h3>

                <p class="mt-2">
                    View and manage your bookings.
                </p>

            </a>


            <?php if ($_SESSION["role"] === "admin"): ?>

                <a
                    href="admin_dashboard.php"
                    class="bg-purple-500 hover:bg-purple-600 text-white p-6 rounded-xl"
                >

                    <h3 class="text-xl font-bold">
                        Admin Dashboard
                    </h3>

                    <p class="mt-2">
                        Manage rooms and customer bookings.
                    </p>

                </a>

            <?php endif; ?>


        </div>


        <div class="mt-8 border-t pt-6 text-gray-700">

            <p>
                <strong>User ID:</strong>
                <?php echo htmlspecialchars($_SESSION["user_id"]); ?>
            </p>

            <p>
                <strong>Email:</strong>
                <?php echo htmlspecialchars($_SESSION["email"]); ?>
            </p>

            <p>
                <strong>Role:</strong>
                <?php echo htmlspecialchars($_SESSION["role"]); ?>
            </p>

        </div>


    </div>

</div>

</body>

</html>