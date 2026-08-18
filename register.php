<?php

require_once "db.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    http_response_code(405);
    echo "Invalid request method.";
    exit;
}

$full_name = $_POST["full_name"] ?? "";
$email = $_POST["email"] ?? "";
$password = $_POST["password"] ?? "";

if (empty($full_name) || empty($email) || empty($password)) {
    echo "All required fields must be filled.";
    exit;
}

$hashed_password = password_hash($password, PASSWORD_DEFAULT);

$sql = "INSERT INTO users (full_name, email, password, role)
        VALUES (?, ?, ?, 'Guest')";

$stmt = $conn->prepare($sql);

if (!$stmt) {
    echo "Failed to prepare query.";
    exit;
}

$stmt->bind_param("sss", $full_name, $email, $hashed_password);

if ($stmt->execute()) {
    echo "Registration successful!";
} else {
    if ($conn->errno === 1062) {
        echo "Email already exists.";
    } else {
        echo "Registration failed.";
    }
}

$stmt->close();
$conn->close();

?>