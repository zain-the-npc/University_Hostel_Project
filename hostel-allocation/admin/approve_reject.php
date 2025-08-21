<?php
session_start();
include("../db/connection.php");

if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

if (isset($_GET['id']) && isset($_GET['action'])) {
    $application_id = $_GET['id'];
    $action = $_GET['action'];

    if ($action == 'approve' || $action == 'reject') {
        $status = $action == 'approve' ? 'approved' : 'rejected';

        // Retrieve application details
        $application_query = "SELECT * FROM applications WHERE id = '$application_id'";
        $application_result = mysqli_query($conn, $application_query);
        $application = mysqli_fetch_assoc($application_result);
        $room_id = $application['room_id'];
        $student_id = $application['student_id'];

        // If approving, check room capacity first
      if ($status == 'approved') {
    $room_query = "SELECT is_occupied, capacity FROM rooms WHERE id = '$room_id'";
    $room_result = mysqli_query($conn, $room_query);
    $room = mysqli_fetch_assoc($room_result);

    // Count all students already assigned to this room
    $assigned_query = "SELECT COUNT(*) as assigned FROM students WHERE assigned_room = '$room_id'";
    $assigned_result = mysqli_query($conn, $assigned_query);
    $assigned_data = mysqli_fetch_assoc($assigned_result);
    $currently_assigned = $assigned_data['assigned'];

    if ($currently_assigned >= $room['capacity']) {
        header("Location: room_full_error.php");
        exit();
    }


            // Check if student already had a room
            $student_query = "SELECT assigned_room FROM students WHERE id = '$student_id'";
            $student_result = mysqli_query($conn, $student_query);
            $student = mysqli_fetch_assoc($student_result);
            $old_room_id = $student['assigned_room'];

            if ($old_room_id && $old_room_id != $room_id) {
                // Unassign and decrement previous room
                mysqli_query($conn, "UPDATE rooms SET is_occupied = is_occupied - 1 WHERE id = '$old_room_id' AND is_occupied > 0");
                mysqli_query($conn, "UPDATE students SET assigned_room = NULL WHERE id = '$student_id'");
            }

            // Assign new room
            mysqli_query($conn, "UPDATE rooms SET is_occupied = is_occupied + 1 WHERE id = '$room_id'");
            mysqli_query($conn, "UPDATE students SET assigned_room = '$room_id' WHERE id = '$student_id'");
        }

        // Update application status
        $update_query = "UPDATE applications SET status = '$status' WHERE id = '$application_id'";
        mysqli_query($conn, $update_query);

        // Redirect back to applications page
        header("Location: applications.php");
        exit();
    }
}
?>
