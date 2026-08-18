<?php

session_start();
require_once "db.php";

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION["user_id"];

if (!isset($_GET["booking_id"])) {
    die("Booking ID is required.");
}

$booking_id = intval($_GET["booking_id"]);


// Make sure this booking belongs to the logged-in user
$sql = "UPDATE bookings
        SET booking_status = 'Cancelled'
        WHERE booking_id = ?
        AND user_id = ?
        AND booking_status = 'Confirmed'";

$stmt = $conn->prepare($sql);

if (!$stmt) {
    die("SQL Error: " . $conn->error);
}

$stmt->bind_param(
    "ii",
    $booking_id,
    $user_id
);

$stmt->execute();

$stmt->close();

header("Location: my_bookings.php");
exit();

?>