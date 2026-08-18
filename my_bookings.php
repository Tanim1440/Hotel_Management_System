<?php
session_start();
require_once "db.php";

// Check if user is logged in
if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION["user_id"];

// Get bookings for the logged-in user
$sql = "SELECT 
            b.booking_id,
            b.room_id,
            b.check_in,
            b.check_out,
            b.booking_status,
            r.room_number,
            r.room_type,
            r.price_per_night
        FROM bookings b
        INNER JOIN rooms r ON b.room_id = r.room_id
        WHERE b.user_id = ?
        ORDER BY b.booking_id DESC";

$stmt = $conn->prepare($sql);

if (!$stmt) {
    die("SQL Error: " . $conn->error);
}

$stmt->bind_param("i", $user_id);
$stmt->execute();

$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>My Bookings - Hotel Management System</title>

    <style>

        body {
            font-family: Arial, sans-serif;
            background: #f4f6f9;
            margin: 0;
            padding: 0;
        }

        .header {
            background: #2c3e50;
            color: white;
            padding: 20px 40px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .header h1 {
            margin: 0;
        }

        .back-btn {
            background: #3498db;
            color: white;
            text-decoration: none;
            padding: 10px 18px;
            border-radius: 5px;
        }

        .back-btn:hover {
            background: #2980b9;
        }

        .container {
            width: 90%;
            margin: 40px auto;
        }

        h2 {
            color: #2c3e50;
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background: white;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        th {
            background: #34495e;
            color: white;
            padding: 15px;
            text-align: left;
        }

        td {
            padding: 15px;
            border-bottom: 1px solid #ddd;
        }

        tr:hover {
            background: #f1f1f1;
        }

        .confirmed {
            color: green;
            font-weight: bold;
        }

        .cancelled {
            color: red;
            font-weight: bold;
        }

        .pending {
            color: orange;
            font-weight: bold;
        }

        .no-bookings {
            background: white;
            padding: 30px;
            text-align: center;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .book-room-btn {
            display: inline-block;
            margin-top: 15px;
            background: #27ae60;
            color: white;
            text-decoration: none;
            padding: 10px 18px;
            border-radius: 5px;
        }

        .book-room-btn:hover {
            background: #219150;
        }

        .cancel-btn {
            background: #e74c3c;
            color: white;
            text-decoration: none;
            padding: 8px 12px;
            border-radius: 5px;
            display: inline-block;
        }

        .cancel-btn:hover {
            background: #c0392b;
        }

        .cancelled-text {
            color: #999;
            font-weight: bold;
        }

    </style>

</head>

<body>

    <div class="header">

        <h1>Hotel Management System</h1>

        <a href="dashboard.php" class="back-btn">
            ← Back to Dashboard
        </a>

    </div>


    <div class="container">

        <h2>My Bookings</h2>


        <?php if ($result->num_rows > 0): ?>

            <table>

                <tr>
                    <th>Booking ID</th>
                    <th>Room Number</th>
                    <th>Room Type</th>
                    <th>Price / Night</th>
                    <th>Check-in</th>
                    <th>Check-out</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>


                <?php while ($booking = $result->fetch_assoc()): ?>

                    <?php
                    $statusClass = strtolower($booking["booking_status"]);
                    ?>


                    <tr>

                        <td>
                            <?php
                            echo htmlspecialchars($booking["booking_id"]);
                            ?>
                        </td>


                        <td>
                            <?php
                            echo htmlspecialchars($booking["room_number"]);
                            ?>
                        </td>


                        <td>
                            <?php
                            echo htmlspecialchars($booking["room_type"]);
                            ?>
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
                            <?php
                            echo htmlspecialchars($booking["check_in"]);
                            ?>
                        </td>


                        <td>
                            <?php
                            echo htmlspecialchars($booking["check_out"]);
                            ?>
                        </td>


                        <td class="<?php echo $statusClass; ?>">

                            <?php
                            echo htmlspecialchars(
                                $booking["booking_status"]
                            );
                            ?>

                        </td>


                        <td>

                            <?php if ($booking["booking_status"] === "Confirmed"): ?>

                                <a
                                    href="cancel_booking.php?booking_id=<?php echo $booking["booking_id"]; ?>"
                                    class="cancel-btn"
                                    onclick="return confirm('Are you sure you want to cancel this booking?');"
                                >
                                    Cancel
                                </a>

                            <?php else: ?>

                                <span class="cancelled-text">
                                    Cancelled
                                </span>

                            <?php endif; ?>

                        </td>

                    </tr>

                <?php endwhile; ?>

            </table>


        <?php else: ?>

            <div class="no-bookings">

                <h3>You don't have any bookings yet.</h3>

                <p>
                    Browse our available rooms and make your first booking.
                </p>

                <a href="rooms.php" class="book-room-btn">
                    Browse Rooms
                </a>

            </div>

        <?php endif; ?>


    </div>

</body>

</html>


<?php
$stmt->close();
?>