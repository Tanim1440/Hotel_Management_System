
<?php

session_start();
require_once "db.php";

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}

$sql = "SELECT * FROM rooms ORDER BY room_number";

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

    <title>Rooms - Hotel Management System</title>

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

        .container {
            width: 90%;
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

        .available {
            color: green;
            font-weight: bold;
        }

        .occupied {
            color: red;
            font-weight: bold;
        }

        .maintenance {
            color: orange;
            font-weight: bold;
        }

        .book-btn {
            background: #27ae60;
            color: white;
            text-decoration: none;
            padding: 8px 14px;
            border-radius: 5px;
        }

        .book-btn:hover {
            background: #219150;
        }

        .disabled-btn {
            background: #95a5a6;
            color: white;
            padding: 8px 14px;
            border-radius: 5px;
        }

    </style>

</head>

<body>

<div class="header">

    <h1>Hotel Management System</h1>

    <a href="dashboard.php" class="back-btn">
        ← Dashboard
    </a>

</div>


<div class="container">

    <h2>Hotel Rooms</h2>

    <table>

        <tr>

            <th>Room ID</th>
            <th>Room Number</th>
            <th>Room Type</th>
            <th>Price / Night</th>
            <th>Capacity</th>
            <th>Status</th>
            <th>Action</th>

        </tr>


        <?php if ($result->num_rows > 0): ?>

            <?php while ($room = $result->fetch_assoc()): ?>

                <?php
                $statusClass = strtolower($room["status"]);
                ?>

                <tr>

                    <td>
                        <?php echo htmlspecialchars($room["room_id"]); ?>
                    </td>

                    <td>
                        <?php echo htmlspecialchars($room["room_number"]); ?>
                    </td>

                    <td>
                        <?php echo htmlspecialchars($room["room_type"]); ?>
                    </td>

                    <td>
                        ৳<?php echo number_format($room["price_per_night"], 2); ?>
                    </td>

                    <td>
                        <?php echo htmlspecialchars($room["capacity"]); ?>
                        persons
                    </td>

                    <td class="<?php echo $statusClass; ?>">

                        <?php echo htmlspecialchars($room["status"]); ?>

                    </td>

                    <td>

                        <?php if ($room["status"] === "Available"): ?>

                            <a
                                href="booking.php?room_id=<?php echo $room["room_id"]; ?>"
                                class="book-btn"
                            >
                                Book Now
                            </a>

                        <?php else: ?>

                            <span class="disabled-btn">
                                Not Available
                            </span>

                        <?php endif; ?>

                    </td>

                </tr>

            <?php endwhile; ?>

        <?php else: ?>

            <tr>

                <td colspan="7">
                    No rooms found.
                </td>

            </tr>

        <?php endif; ?>

    </table>

</div>

</body>

</html>