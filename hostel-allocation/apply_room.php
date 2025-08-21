<?php
session_start();
include("db/connection.php");

// Redirect if not logged in
if (!isset($_SESSION['student_id'])) {
    header("Location: login.php");
    exit();
}

$student_id = $_SESSION['student_id'];
$room_id = $_GET['room_id'];

// Get student details (including gender)
$student_query = "SELECT * FROM students WHERE id = '$student_id'";
$student_result = mysqli_query($conn, $student_query);
$student = mysqli_fetch_assoc($student_result);

// Check if the student exists
if (!$student) {
    echo "Student not found.";
    exit();
}

$student_gender = $student['gender'];

// Get room details
$room_check_query = "SELECT * FROM rooms WHERE id = '$room_id'";
$room_check_result = mysqli_query($conn, $room_check_query);
$room = mysqli_fetch_assoc($room_check_result);

// Check if room exists
if (!$room) {
    echo "Room not found.";
    exit();
}

$room_gender = $room['gender'];

// ✅ Gender mismatch check
if (strtolower($student_gender) !== strtolower($room_gender)) {
    header("Location: error_message.php?error=gender_mismatch");
    exit();
}

// ✅ Already applied check
$check_query = "SELECT * FROM applications WHERE student_id = '$student_id' AND room_id = '$room_id' AND status = 'pending'";
$check_result = mysqli_query($conn, $check_query);
if (mysqli_num_rows($check_result) > 0) {
    header("Location: available_rooms.php?error=already_applied");
    exit();
}

// ✅ Room full check (considering pending applications too)
$pending_query = "SELECT COUNT(*) as pending FROM applications WHERE room_id = '$room_id' AND status = 'pending'";
$pending_result = mysqli_query($conn, $pending_query);
$pending_data = mysqli_fetch_assoc($pending_result);
$pending_count = $pending_data['pending'];

$total_expected_occupancy = $room['is_occupied'] + $pending_count;

if ($total_expected_occupancy >= $room['capacity']) {
    // ✅ Correct redirection to room_full_error.php inside /admin
    header("Location: room_full_error.php");
    exit();
}

// ✅ Insert new application
$query = "INSERT INTO applications (student_id, room_id, status) VALUES ('$student_id', '$room_id', 'pending')";
if (mysqli_query($conn, $query)) {
    header("Location: available_rooms.php?success=applied");
    exit();
} else {
    echo "Error submitting application: " . mysqli_error($conn);
}
?>
