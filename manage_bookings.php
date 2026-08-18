<?php

session_start();
require_once "db.php";


// Check login
if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}


// Check admin
if ($_SESSION["role"] !== "admin") {
    die("Access denied. Admins only.");
}


// ==============================
// UPDATE BOOKING STATUS
// ==============================

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    if (isset($_POST["booking_id"]) && isset($_POST["booking_status"])) {

        $booking_id = intval($_POST["booking_id"]);
        $booking_status = $_POST["booking_status"];


        if (
            $booking_status === "Confirmed" ||
            $booking_status === "Cancelled"
        ) {

            $sql = "UPDATE bookings
                    SET booking_status = ?
                    WHERE booking_id = ?";

            $stmt = $conn->prepare($sql);

            if (!$stmt) {
                die("SQL Error: " . $conn->error);
            }

            $stmt->bind_param(
                "si",
                $booking_status,
                $booking_id
            );

            $stmt->execute();

            $stmt->close();
        }
    }

    header("Location: manage_bookings.php");
    exit();
}


// ==============================
// GET ALL BOOKINGS
// ==============================

$sql = "SELECT
            b.booking_id,
            b.user_id,
            b.room_id,
            b.check_in,
            b.check_out,
            b.booking_status,
            r.room_number,
            r.room_type,
            r.price_per_night,
            u.full_name,
            u.email
        FROM bookings b
        INNER JOIN rooms r
            ON b.room_id = r.room_id
        INNER JOIN users u
            ON b.user_id = u.user_id
        ORDER BY b.booking_id DESC";


$result = $conn->query($sql);

if (!$result) {
    die("SQL Error: " . $conn->error);
}

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Manage Bookings</title>

    <style>

        body {
            font-family: Arial, sans-serif;
            background: #f4f6f9;
            margin: 0;
        }

        .header {
            background: #2c3e50;
            color: white;
            padding: 20px 40px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .header a {
            background: #3498db;
            color: white;
            text-decoration: none;
            padding: 10px 16px;
            border-radius: 5px;
            margin-left: 8px;
        }

        .container {
            width: 95%;
            margin: 40px auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background: white;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        th {
            background: #34495e;
            color: white;
            padding: 12px;
            text-align: left;
        }

        td {
            padding: 12px;
            border-bottom: 1px solid #ddd;
        }

        tr:hover {
            background: #f5f5f5;
        }

        .confirmed {
            color: green;
            font-weight: bold;
        }

        .cancelled {
            color: red;
            font-weight: bold;
        }

        select {
            padding: 7px;
        }

        button {
            padding: 7px 12px;
            background: #3498db;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        button:hover {
            background: #2980b9;
        }

        .empty {
            background: white;
            padding: 30px;
            text-align: center;
        }

    </style>

</head>

<body>


<div class="header">

    <h1>Manage Bookings</h1>

    <div>

        <a href="admin_dashboard.php">
            Admin Dashboard
        </a>

        <a href="logout.php">
            Logout
        </a>

    </div>

</div>


<div class="container">

    <h2>All Customer Bookings</h2>


    <?php if ($result->num_rows > 0): ?>

        <table>

            <tr>

                <th>Booking ID</th>
                <th>Customer</th>
                <th>Email</th>
                <th>Room</th>
                <th>Type</th>
                <th>Price</th>
                <th>Check-in</th>
                <th>Check-out</th>
                <th>Status</th>
                <th>Action</th>

            </tr>


            <?php while ($booking = $result->fetch_assoc()): ?>

                <tr>

                    <td>
                        <?php echo htmlspecialchars($booking["booking_id"]); ?>
                    </td>


                    <td>
                        <?php echo htmlspecialchars($booking["full_name"]); ?>
                    </td>


                    <td>
                        <?php echo htmlspecialchars($booking["email"]); ?>
                    </td>


                    <td>
                        <?php echo htmlspecialchars($booking["room_number"]); ?>
                    </td>


                    <td>
                        <?php echo htmlspecialchars($booking["room_type"]); ?>
                    </td>


                    <td>
                        ৳<?php
                        echo number_format(
                            $booking["price_per_night"],
                            2
                        );
                        ?>
                    </td>


                    <td>
                        <?php echo htmlspecialchars($booking["check_in"]); ?>
                    </td>


                    <td>
                        <?php echo htmlspecialchars($booking["check_out"]); ?>
                    </td>


                    <td>

                        <?php
                        $statusClass =
                            strtolower($booking["booking_status"]);
                        ?>

                        <span class="<?php echo $statusClass; ?>">

                            <?php
                            echo htmlspecialchars(
                                $booking["booking_status"]
                            );
                            ?>

                        </span>

                    </td>


                    <td>

                        <form method="POST">

                            <input
                                type="hidden"
                                name="booking_id"
                                value="<?php echo $booking["booking_id"]; ?>"
                            >


                            <select name="booking_status">

                                <option
                                    value="Confirmed"
                                    <?php
                                    if ($booking["booking_status"] === "Confirmed") {
                                        echo "selected";
                                    }
                                    ?>
                                >
                                    Confirmed
                                </option>


                                <option
                                    value="Cancelled"
                                    <?php
                                    if ($booking["booking_status"] === "Cancelled") {
                                        echo "selected";
                                    }
                                    ?>
                                >
                                    Cancelled
                                </option>

                            </select>


                            <button type="submit">
                                Update
                            </button>

                        </form>

                    </td>

                </tr>

            <?php endwhile; ?>

        </table>

    <?php else: ?>

        <div class="empty">

            <h3>No bookings found.</h3>

        </div>

    <?php endif; ?>

</div>

</body>

</html>