
<?php

session_start();
require_once "db.php";

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION["user_id"];

$message = "";
$message_type = "";


// Get room ID
if (!isset($_GET["room_id"]) && !isset($_POST["room_id"])) {
    die("Room not selected.");
}

$room_id = isset($_POST["room_id"])
    ? intval($_POST["room_id"])
    : intval($_GET["room_id"]);


// Get room information
$sql = "SELECT *
        FROM rooms
        WHERE room_id = ?";

$stmt = $conn->prepare($sql);

if (!$stmt) {
    die("SQL Error: " . $conn->error);
}

$stmt->bind_param("i", $room_id);
$stmt->execute();

$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die("Room not found.");
}

$room = $result->fetch_assoc();

$stmt->close();


// ==============================
// PROCESS BOOKING
// ==============================

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $check_in = $_POST["check_in"];
    $check_out = $_POST["check_out"];


    if (empty($check_in) || empty($check_out)) {

        $message = "Please select check-in and check-out dates.";
        $message_type = "error";

    } else {

        $today = date("Y-m-d");


        if ($check_in < $today) {

            $message = "Check-in date cannot be in the past.";
            $message_type = "error";

        } elseif ($check_out <= $check_in) {

            $message = "Check-out date must be after check-in date.";
            $message_type = "error";

        } elseif ($room["status"] !== "Available") {

            $message = "This room is currently not available.";
            $message_type = "error";

        } else {

            // Check overlapping bookings
            $check_sql = "SELECT COUNT(*) AS total
                          FROM bookings
                          WHERE room_id = ?
                          AND booking_status = 'Confirmed'
                          AND check_in < ?
                          AND check_out > ?";

            $check_stmt = $conn->prepare($check_sql);

            if (!$check_stmt) {
                die("SQL Error: " . $conn->error);
            }

            $check_stmt->bind_param(
                "iss",
                $room_id,
                $check_out,
                $check_in
            );

            $check_stmt->execute();

            $check_result = $check_stmt->get_result();

            $existing = $check_result->fetch_assoc()["total"];

            $check_stmt->close();


            if ($existing > 0) {

                $message = "This room is already booked for those dates.";
                $message_type = "error";

            } else {

                // Insert booking
                $insert_sql = "INSERT INTO bookings
                               (user_id, room_id, check_in, check_out, booking_status)
                               VALUES (?, ?, ?, ?, 'Confirmed')";

                $insert_stmt = $conn->prepare($insert_sql);

                if (!$insert_stmt) {
                    die("SQL Error: " . $conn->error);
                }

                $insert_stmt->bind_param(
                    "iiss",
                    $user_id,
                    $room_id,
                    $check_in,
                    $check_out
                );


                if ($insert_stmt->execute()) {

                    $insert_stmt->close();

                    header("Location: my_bookings.php");
                    exit();

                } else {

                    $message = "Booking failed: " . $insert_stmt->error;
                    $message_type = "error";

                    $insert_stmt->close();
                }
            }
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Book Room</title>

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
        }

        .header a {
            color: white;
            text-decoration: none;
            background: #3498db;
            padding: 10px 18px;
            border-radius: 5px;
        }

        .container {
            width: 500px;
            max-width: 90%;
            margin: 50px auto;
        }

        .card {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        .room-info {
            background: #f4f6f9;
            padding: 20px;
            margin-bottom: 20px;
            border-radius: 5px;
        }

        label {
            display: block;
            margin-top: 15px;
            font-weight: bold;
        }

        input {
            width: 100%;
            box-sizing: border-box;
            padding: 12px;
            margin-top: 7px;
        }

        button {
            width: 100%;
            margin-top: 25px;
            padding: 12px;
            background: #27ae60;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }

        .error {
            background: #f8d7da;
            color: #721c24;
            padding: 12px;
            margin-bottom: 15px;
        }

    </style>

</head>

<body>

<div class="header">

    <h2>Hotel Management System</h2>

    <a href="room.php">
        ← Rooms
    </a>

</div>


<div class="container">

    <div class="card">

        <h2>Book Room <?php echo htmlspecialchars($room["room_number"]); ?></h2>


        <?php if (!empty($message)): ?>

            <div class="error">
                <?php echo htmlspecialchars($message); ?>
            </div>

        <?php endif; ?>


        <div class="room-info">

            <p>
                <strong>Room Number:</strong>
                <?php echo htmlspecialchars($room["room_number"]); ?>
            </p>

            <p>
                <strong>Type:</strong>
                <?php echo htmlspecialchars($room["room_type"]); ?>
            </p>

            <p>
                <strong>Price:</strong>
                ৳<?php echo number_format($room["price_per_night"], 2); ?>
                / night
            </p>

            <p>
                <strong>Capacity:</strong>
                <?php echo htmlspecialchars($room["capacity"]); ?>
                persons
            </p>

        </div>


        <form method="POST">

            <input
                type="hidden"
                name="room_id"
                value="<?php echo $room_id; ?>"
            >


            <label>
                Check-in
            </label>

            <input
                type="date"
                name="check_in"
                min="<?php echo date('Y-m-d'); ?>"
                required
            >


            <label>
                Check-out
            </label>

            <input
                type="date"
                name="check_out"
                min="<?php echo date('Y-m-d'); ?>"
                required
            >


            <button type="submit">
                Confirm Booking
            </button>

        </form>

    </div>

</div>

</body>

</html>