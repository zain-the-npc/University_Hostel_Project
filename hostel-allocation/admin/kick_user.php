<?php
session_start();
include("../db/connection.php");

// Redirect if admin not logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

// Check if student_id is provided
if (isset($_GET['student_id'])) {
    $student_id = $_GET['student_id'];
    
    // Get the student information to check room assignment
    $student_query = "SELECT * FROM students WHERE id = '$student_id'";
    $student_result = mysqli_query($conn, $student_query);
    
    if (mysqli_num_rows($student_result) > 0) {
        $student = mysqli_fetch_assoc($student_result);
        
        // Check if user is assigned to any room
        if (empty($student['assigned_room'])) {
            echo "User is not assigned to any room.";
            exit();
        }
        
        $room_id = $student['assigned_room'];
        
        // Remove the room assignment for the student
        $update_student_query = "UPDATE students SET assigned_room = NULL WHERE id = '$student_id'";
        if (mysqli_query($conn, $update_student_query)) {
            // Decrement the room occupancy
            $update_room_query = "UPDATE rooms SET is_occupied = (CASE WHEN is_occupied > 0 THEN is_occupied - 1 ELSE 0 END) WHERE id = '$room_id'";
            mysqli_query($conn, $update_room_query);
            
            // Insert a notification for the student regarding being kicked
            $notification_message = "You have been kicked from your room by the admin. Please contact administration for details.";
            $insert_notification_query = "INSERT INTO notifications (student_id, message) VALUES ('$student_id', '$notification_message')";
            mysqli_query($conn, $insert_notification_query);
            
            echo "User has been kicked out of the room successfully.";
            // Redirect back to the admin users page (e.g., all_users.php)
            header("Location: all_users.php");
            exit();
        } else {
            echo "Error updating student record: " . mysqli_error($conn);
            exit();
        }
    } else {
        echo "User not found.";
        exit();
    }
} else {
    echo "Invalid request.";
    exit();
}
?>
