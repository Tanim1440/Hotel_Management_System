<?php
session_start();

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Booking Successful</title>
</head>

<body>

    <h1>Booking Successful!</h1>

    <p>Your room has been booked successfully.</p>

    <a href="room.php">Back to Rooms</a>

</body>
</html>