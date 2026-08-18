-- 1. Create the Database
CREATE DATABASE IF NOT EXISTS hotel_management_db;
USE hotel_management_db;

-- 2. Create the Users Table
CREATE TABLE users (
    user_id INT AUTO_INCREMENT PRIMARY KEY,
    full_name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin', 'Guest') DEFAULT 'Guest'
);

-- 3. Create the Rooms Table
CREATE TABLE rooms (
    room_id INT AUTO_INCREMENT PRIMARY KEY,
    room_number VARCHAR(20) NOT NULL UNIQUE,
    room_type VARCHAR(50) NOT NULL,
    price_per_night DECIMAL(10, 2) NOT NULL,
    capacity INT NOT NULL,
    status ENUM('Available', 'Occupied', 'Maintenance') DEFAULT 'Available'
);

-- 4. Create the Bookings Table
CREATE TABLE bookings (
    booking_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    room_id INT NOT NULL,
    check_in DATE NOT NULL,
    check_out DATE NOT NULL,
    booking_status ENUM('Confirmed', 'Cancelled', 'Pending') DEFAULT 'Confirmed',
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE,
    FOREIGN KEY (room_id) REFERENCES rooms(room_id) ON DELETE CASCADE
);

-- 5. Insert a Default Admin Account
-- Password is 'admin123' (hashed using PHP's password_hash)
INSERT INTO users (full_name, email, password, role) 
VALUES ('System Admin', 'admin@explore.com', '$2y$10$e.w2.b6Z8M2j7iJ4Y9j.O.8X8b5/zT/4X9z9o/6r2o.7V9t8k.2', 'admin');