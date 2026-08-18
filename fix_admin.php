<?php
require_once "db.php";

// This securely encrypts the password "admin123"
$hashed_password = password_hash("admin123", PASSWORD_DEFAULT);

// Update the placeholder hash in the database with the real one
$sql = "UPDATE users SET password = ? WHERE email = 'admin@explore.com'";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $hashed_password);

if ($stmt->execute()) {
    echo "<h1>Success!</h1><p>The admin password has been correctly hashed and updated. You can now log in.</p>";
} else {
    echo "Error updating password: " . $conn->error;
}

$stmt->close();
$conn->close();
?>