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

$message = "";
$message_type = "";


// ==============================
// ADD ROOM
// ==============================

if (isset($_POST["add_room"])) {

    $room_number = trim($_POST["room_number"]);
    $room_type = trim($_POST["room_type"]);
    $price_per_night = floatval($_POST["price_per_night"]);
    $capacity = intval($_POST["capacity"]);
    $status = $_POST["status"];

    if (
        empty($room_number) ||
        empty($room_type) ||
        $price_per_night <= 0 ||
        $capacity <= 0
    ) {

        $message = "Please enter valid room information.";
        $message_type = "error";

    } else {

        $sql = "INSERT INTO rooms
                (room_number, room_type, price_per_night, capacity, status)
                VALUES (?, ?, ?, ?, ?)";

        $stmt = $conn->prepare($sql);

        if (!$stmt) {

            $message = "SQL Error: " . $conn->error;
            $message_type = "error";

        } else {

            $stmt->bind_param(
                "ssdis",
                $room_number,
                $room_type,
                $price_per_night,
                $capacity,
                $status
            );

            if ($stmt->execute()) {

                $message = "Room added successfully.";
                $message_type = "success";

            } else {

                if ($conn->errno == 1062) {
                    $message = "Room number already exists.";
                } else {
                    $message = "Failed to add room: " . $stmt->error;
                }

                $message_type = "error";
            }

            $stmt->close();
        }
    }
}


// ==============================
// DELETE ROOM
// ==============================

if (isset($_GET["delete"])) {

    $room_id = intval($_GET["delete"]);

    // Check whether the room has bookings
    $check_sql = "SELECT COUNT(*) AS total
                  FROM bookings
                  WHERE room_id = ?";

    $check_stmt = $conn->prepare($check_sql);

    if (!$check_stmt) {
        die("SQL Error: " . $conn->error);
    }

    $check_stmt->bind_param("i", $room_id);
    $check_stmt->execute();

    $check_result = $check_stmt->get_result();
    $booking_count = $check_result->fetch_assoc()["total"];

    $check_stmt->close();


    if ($booking_count > 0) {

        $message = "This room cannot be deleted because it has booking records.";
        $message_type = "error";

    } else {

        $delete_sql = "DELETE FROM rooms WHERE room_id = ?";

        $delete_stmt = $conn->prepare($delete_sql);

        if (!$delete_stmt) {
            die("SQL Error: " . $conn->error);
        }

        $delete_stmt->bind_param("i", $room_id);

        if ($delete_stmt->execute()) {

            $message = "Room deleted successfully.";
            $message_type = "success";

        } else {

            $message = "Failed to delete room.";
            $message_type = "error";
        }

        $delete_stmt->close();
    }
}


// ==============================
// EDIT ROOM
// ==============================

if (isset($_POST["edit_room"])) {

    $room_id = intval($_POST["room_id"]);
    $room_number = trim($_POST["room_number"]);
    $room_type = trim($_POST["room_type"]);
    $price_per_night = floatval($_POST["price_per_night"]);
    $capacity = intval($_POST["capacity"]);
    $status = $_POST["status"];


    if (
        empty($room_number) ||
        empty($room_type) ||
        $price_per_night <= 0 ||
        $capacity <= 0
    ) {

        $message = "Please enter valid room information.";
        $message_type = "error";

    } else {

        $sql = "UPDATE rooms
                SET room_number = ?,
                    room_type = ?,
                    price_per_night = ?,
                    capacity = ?,
                    status = ?
                WHERE room_id = ?";

        $stmt = $conn->prepare($sql);

        if (!$stmt) {

            $message = "SQL Error: " . $conn->error;
            $message_type = "error";

        } else {

            $stmt->bind_param(
                "ssdis",
                $room_number,
                $room_type,
                $price_per_night,
                $capacity,
                $status,
                $room_id
            );

            if ($stmt->execute()) {

                $message = "Room updated successfully.";
                $message_type = "success";

            } else {

                $message = "Failed to update room: " . $stmt->error;
                $message_type = "error";
            }

            $stmt->close();
        }
    }
}


// ==============================
// GET ALL ROOMS
// ==============================

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

    <title>Manage Rooms - Hotel Management System</title>

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

        .header-links {
            display: flex;
            gap: 10px;
        }

        .header-btn {
            background: #3498db;
            color: white;
            text-decoration: none;
            padding: 10px 16px;
            border-radius: 5px;
        }

        .header-btn:hover {
            background: #2980b9;
        }

        .container {
            width: 92%;
            margin: 35px auto;
        }

        h2 {
            color: #2c3e50;
            margin-bottom: 20px;
        }

        .card {
            background: white;
            padding: 25px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            margin-bottom: 30px;
        }

        .form-grid {
            display: grid;
            grid-template-columns: repeat(5, 1fr);
            gap: 15px;
        }

        .form-group label {
            display: block;
            font-weight: bold;
            margin-bottom: 7px;
        }

        .form-group input,
        .form-group select {
            width: 100%;
            padding: 10px;
            box-sizing: border-box;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .add-btn {
            margin-top: 20px;
            background: #27ae60;
            color: white;
            border: none;
            padding: 11px 20px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 15px;
        }

        .add-btn:hover {
            background: #219150;
        }

        .message {
            padding: 12px 15px;
            border-radius: 5px;
            margin-bottom: 20px;
        }

        .success {
            background: #d4edda;
            color: #155724;
        }

        .error {
            background: #f8d7da;
            color: #721c24;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background: white;
        }

        th {
            background: #34495e;
            color: white;
            padding: 14px;
            text-align: left;
        }

        td {
            padding: 14px;
            border-bottom: 1px solid #ddd;
        }

        tr:hover {
            background: #f5f5f5;
        }

        .status-available {
            color: green;
            font-weight: bold;
        }

        .status-occupied {
            color: red;
            font-weight: bold;
        }

        .status-maintenance {
            color: orange;
            font-weight: bold;
        }

        .edit-btn {
            background: #3498db;
            color: white;
            text-decoration: none;
            padding: 7px 11px;
            border-radius: 4px;
        }

        .edit-btn:hover {
            background: #2980b9;
        }

        .delete-btn {
            background: #e74c3c;
            color: white;
            text-decoration: none;
            padding: 7px 11px;
            border-radius: 4px;
        }

        .delete-btn:hover {
            background: #c0392b;
        }

        .edit-form {
            display: grid;
            grid-template-columns: repeat(6, 1fr);
            gap: 10px;
            align-items: center;
        }

        .edit-form input,
        .edit-form select {
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        .save-btn {
            background: #27ae60;
            color: white;
            border: none;
            padding: 8px 12px;
            border-radius: 4px;
            cursor: pointer;
        }

        .cancel-edit-btn {
            background: #7f8c8d;
            color: white;
            text-decoration: none;
            padding: 8px 12px;
            border-radius: 4px;
            text-align: center;
        }

        @media (max-width: 1000px) {

            .form-grid {
                grid-template-columns: repeat(2, 1fr);
            }

            .edit-form {
                grid-template-columns: repeat(2, 1fr);
            }

            table {
                display: block;
                overflow-x: auto;
            }
        }

    </style>

</head>


<body>


<div class="header">

    <h1>Hotel Management System</h1>

    <div class="header-links">

        <a href="admin_dashboard.php" class="header-btn">
            Admin Dashboard
        </a>

        <a href="logout.php" class="header-btn">
            Logout
        </a>

    </div>

</div>


<div class="container">


    <h2>Manage Rooms</h2>


    <?php if (!empty($message)): ?>

        <div class="message <?php echo $message_type; ?>">

            <?php echo htmlspecialchars($message); ?>

        </div>

    <?php endif; ?>


    <!-- ==============================
         ADD ROOM
    =============================== -->

    <div class="card">

        <h3>Add New Room</h3>

        <form method="POST">

            <div class="form-grid">

                <div class="form-group">

                    <label>Room Number</label>

                    <input
                        type="text"
                        name="room_number"
                        placeholder="Example: 301"
                        required
                    >

                </div>


                <div class="form-group">

                    <label>Room Type</label>

                    <select name="room_type" required>

                        <option value="">
                            Select Type
                        </option>

                        <option value="Standard">
                            Standard
                        </option>

                        <option value="Deluxe">
                            Deluxe
                        </option>

                        <option value="Suite">
                            Suite
                        </option>

                        <option value="Luxury">
                            Luxury
                        </option>

                    </select>

                </div>


                <div class="form-group">

                    <label>Price / Night</label>

                    <input
                        type="number"
                        name="price_per_night"
                        step="0.01"
                        min="1"
                        placeholder="3000"
                        required
                    >

                </div>


                <div class="form-group">

                    <label>Capacity</label>

                    <input
                        type="number"
                        name="capacity"
                        min="1"
                        placeholder="2"
                        required
                    >

                </div>


                <div class="form-group">

                    <label>Status</label>

                    <select name="status" required>

                        <option value="Available">
                            Available
                        </option>

                        <option value="Occupied">
                            Occupied
                        </option>

                        <option value="Maintenance">
                            Maintenance
                        </option>

                    </select>

                </div>

            </div>


            <button
                type="submit"
                name="add_room"
                class="add-btn"
            >
                + Add Room
            </button>

        </form>

    </div>


    <!-- ==============================
         ROOM LIST
    =============================== -->

    <div class="card">

        <h3>All Rooms</h3>

        <table>

            <tr>

                <th>ID</th>

                <th>Room Number</th>

                <th>Room Type</th>

                <th>Price / Night</th>

                <th>Capacity</th>

                <th>Status</th>

                <th>Action</th>

            </tr>


            <?php while ($room = $result->fetch_assoc()): ?>

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
                        ৳<?php
                        echo number_format(
                            $room["price_per_night"],
                            2
                        );
                        ?>
                    </td>


                    <td>
                        <?php echo htmlspecialchars($room["capacity"]); ?>
                        persons
                    </td>


                    <td>

                        <?php
                        $status_class =
                            "status-" .
                            strtolower($room["status"]);
                        ?>

                        <span class="<?php echo $status_class; ?>">

                            <?php
                            echo htmlspecialchars(
                                $room["status"]
                            );
                            ?>

                        </span>

                    </td>


                    <td>

                        <a
                            href="?edit=<?php echo $room["room_id"]; ?>"
                            class="edit-btn"
                        >
                            Edit
                        </a>

                        <a
                            href="?delete=<?php echo $room["room_id"]; ?>"
                            class="delete-btn"
                            onclick="return confirm('Are you sure you want to delete this room?');"
                        >
                            Delete
                        </a>

                    </td>

                </tr>


                <?php if (
                    isset($_GET["edit"]) &&
                    intval($_GET["edit"]) == $room["room_id"]
                ): ?>

                    <tr>

                        <td colspan="7">

                            <form method="POST" class="edit-form">

                                <input
                                    type="hidden"
                                    name="room_id"
                                    value="<?php echo $room["room_id"]; ?>"
                                >


                                <input
                                    type="text"
                                    name="room_number"
                                    value="<?php echo htmlspecialchars($room["room_number"]); ?>"
                                    required
                                >


                                <input
                                    type="text"
                                    name="room_type"
                                    value="<?php echo htmlspecialchars($room["room_type"]); ?>"
                                    required
                                >


                                <input
                                    type="number"
                                    name="price_per_night"
                                    step="0.01"
                                    value="<?php echo $room["price_per_night"]; ?>"
                                    required
                                >


                                <input
                                    type="number"
                                    name="capacity"
                                    min="1"
                                    value="<?php echo $room["capacity"]; ?>"
                                    required
                                >


                                <select name="status">

                                    <option
                                        value="Available"
                                        <?php
                                        if ($room["status"] === "Available") {
                                            echo "selected";
                                        }
                                        ?>
                                    >
                                        Available
                                    </option>

                                    <option
                                        value="Occupied"
                                        <?php
                                        if ($room["status"] === "Occupied") {
                                            echo "selected";
                                        }
                                        ?>
                                    >
                                        Occupied
                                    </option>

                                    <option
                                        value="Maintenance"
                                        <?php
                                        if ($room["status"] === "Maintenance") {
                                            echo "selected";
                                        }
                                        ?>
                                    >
                                        Maintenance
                                    </option>

                                </select>


                                <button
                                    type="submit"
                                    name="edit_room"
                                    class="save-btn"
                                >
                                    Save
                                </button>


                                <a
                                    href="manage_rooms.php"
                                    class="cancel-edit-btn"
                                >
                                    Cancel
                                </a>

                            </form>

                        </td>

                    </tr>

                <?php endif; ?>

            <?php endwhile; ?>

        </table>

    </div>

</div>


</body>

</html>