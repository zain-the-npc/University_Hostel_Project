
CREATE DATABASE IF NOT EXISTS hostel_db;

USE hostel_db;

CREATE TABLE IF NOT EXISTS admins (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE,
    password VARCHAR(255)
);

CREATE TABLE IF NOT EXISTS rooms (
    id INT AUTO_INCREMENT PRIMARY KEY,
    room_number VARCHAR(10) UNIQUE,
    capacity INT,
    gender ENUM('Male', 'Female') NOT NULL,
    is_occupied INT DEFAULT 0,
    current_occupants INT DEFAULT 0
);

CREATE TABLE IF NOT EXISTS students (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100),
    email VARCHAR(100) UNIQUE,
    password VARCHAR(255),
    roll_no VARCHAR(20) UNIQUE,
    assigned_room INT,
    gender ENUM('male', 'female') NOT NULL,
    FOREIGN KEY (assigned_room) REFERENCES rooms(id) ON DELETE SET NULL
);

CREATE TABLE IF NOT EXISTS applications (
    id INT AUTO_INCREMENT PRIMARY KEY,
    student_id INT,
    student_name VARCHAR(100),
    roll_no VARCHAR(20),
    requested_room INT,
    status ENUM('pending', 'approved', 'rejected') DEFAULT 'pending',
    FOREIGN KEY (student_id) REFERENCES students(id) ON DELETE SET NULL,
    FOREIGN KEY (requested_room) REFERENCES rooms(id) ON DELETE SET NULL
);


CREATE TABLE IF NOT EXISTS notifications (
    id INT AUTO_INCREMENT PRIMARY KEY,
    student_id INT NOT NULL,
    message TEXT NOT NULL,
    is_read TINYINT(1) DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (student_id) REFERENCES students(id)
);



INSERT INTO admins (username, password)
VALUES ('admin', 'qwerty'), ('warden', 'qwerty');

-- Insert initial male rooms.
INSERT INTO rooms (room_number, capacity, gender) VALUES
('B-101', 2, 'Male'),
('B-102', 3, 'Male'),
('B-103', 2, 'Male'),
('B-104', 3, 'Male'),
('B-105', 2, 'Male');

-- Insert initial female rooms.
INSERT INTO rooms (room_number, capacity, gender) VALUES
('G-101', 3, 'Female'),
('G-102', 2, 'Female'),
('G-103', 3, 'Female'),
('G-104', 2, 'Female'),
('G-105', 3, 'Female');

