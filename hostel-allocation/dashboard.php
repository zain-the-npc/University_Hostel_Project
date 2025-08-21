<?php
session_start();
include("db/connection.php");

// Redirect to login page if not logged in
if (!isset($_SESSION['student_id'])) {
    header("Location: login.php");
    exit();
}

$student_id = $_SESSION['student_id'];

// Fetch student info
$query = "SELECT * FROM students WHERE id='$student_id'";
$result = mysqli_query($conn, $query);
$student = mysqli_fetch_assoc($result);

// Fetch unread notifications for the student
$notif_query = "SELECT * FROM notifications WHERE student_id = '$student_id' AND is_read = 0 ORDER BY created_at DESC";
$notif_result = mysqli_query($conn, $notif_query);

// Fetch assigned room info
$room = null;
if (!empty($student['assigned_room'])) {
    $room_query = "SELECT * FROM rooms WHERE id = '{$student['assigned_room']}'";
    $room_result = mysqli_query($conn, $room_query);
    $room = mysqli_fetch_assoc($room_result);
}

// If the student has an assigned room, fetch their roommates (excluding themselves)
$roommates = [];
if (!empty($student['assigned_room'])) {
    $roommates_query = "SELECT * FROM students WHERE assigned_room = '{$student['assigned_room']}' AND id <> '$student_id'";
    $roommates_result = mysqli_query($conn, $roommates_query);
    while ($mate = mysqli_fetch_assoc($roommates_result)) {
        $roommates[] = $mate;
    }
}

// Leave Room functionality
if (isset($_POST['leave_room'])) {
    if ($student['assigned_room']) {
        $update_room_query = "UPDATE rooms SET is_occupied = 0 WHERE id = '{$student['assigned_room']}'";
        $update_student_query = "UPDATE students SET assigned_room = NULL WHERE id = '$student_id'";
        if (mysqli_query($conn, $update_room_query) && mysqli_query($conn, $update_student_query)) {
            header("Location: dashboard.php");  // Reload dashboard after leaving the room
        } else {
            echo "Error leaving room: " . mysqli_error($conn);
        }
    } else {
        echo "You are not assigned to any room.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Dashboard</title>
    <link rel="stylesheet" href="css/dashboard.css">
<link rel="preload" as="image" href="images/hostelpic.jpeg">

</head>
<body>

<div class="container">
    <!-- Welcome Section -->
    <h1>Welcome, <?php echo htmlspecialchars($student['name']); ?>!</h1>

    <!-- Notifications Section -->
    <?php if (mysqli_num_rows($notif_result) > 0): ?>
        <div class="notifications">
            <h3>Notifications</h3>
            <?php while ($notif = mysqli_fetch_assoc($notif_result)): ?>
                <p><?php echo htmlspecialchars($notif['message']); ?></p>
            <?php endwhile; ?>
        </div>
        <?php 
        // Mark notifications as read
        $mark_read_query = "UPDATE notifications SET is_read = 1 WHERE student_id = '$student_id' AND is_read = 0";
        mysqli_query($conn, $mark_read_query);
        ?>
    <?php endif; ?>

    <!-- Room Info Section -->
    <div class="room-info">
        <h2>Your Room Info</h2>
        <?php if ($room): ?>
            <p>You are assigned to Room: <strong><?php echo htmlspecialchars($room['room_number']); ?></strong> (Capacity: <?php echo $room['capacity']; ?> people)</p>
            <p>Status: <?php echo $room['is_occupied'] ? "Occupied" : "Vacant"; ?></p>

            <!-- Leave Room Button -->
            <form method="POST">
                <button type="submit" name="leave_room">Leave Room</button>
            </form>

            <!-- Roommates Section -->
            <h3>Your Roommates</h3>
            <?php if (!empty($roommates)): ?>
                <ul class="roommates">
                    <?php foreach ($roommates as $mate): ?>
                        <li><?php echo htmlspecialchars($mate['name']); ?> (<?php echo htmlspecialchars($mate['roll_no']); ?>)</li>
                    <?php endforeach; ?>
                </ul>
            <?php else: ?>
                <p>You have no roommates in this room yet.</p>
            <?php endif; ?>
        <?php else: ?>
            <p>You are not assigned a room yet. You can apply for a room below.</p>
        <?php endif; ?>
    </div>

    <!-- Request Room Button -->
    <div class="button-container">
        <a href="available_rooms.php" class="btn">Request a Room</a>
        <button type="button" class="btn" onclick="window.location.href='logout.php'">Logout</button>
    </div>
</div>

</body>
</html>
