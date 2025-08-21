<?php
session_start();
include("db/connection.php");

// Redirect to login if not logged in
if (!isset($_SESSION['student_id'])) {
    header("Location: login.php");
    exit();
}

// Function to fetch rooms with real-time occupied count
function fetch_rooms_with_occupancy($conn, $gender) {
    $query = "
        SELECT r.*, COUNT(s.id) AS occupied 
        FROM rooms r 
        LEFT JOIN students s ON r.id = s.assigned_room 
        WHERE r.gender = '$gender'
        GROUP BY r.id
        ORDER BY r.room_number ASC
    ";
    return mysqli_query($conn, $query);
}

$boys_rooms_result = fetch_rooms_with_occupancy($conn, 'Male');
$girls_rooms_result = fetch_rooms_with_occupancy($conn, 'Female');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
<link rel="preload" as="image" href="images/hostelpic.jpeg">

    <title>Available Rooms</title>
    <link rel="stylesheet" href="css/available_rooms.css">
</head>
<body>
    <div class="container">
        <h1>Available Rooms</h1>
	<?php if (isset($_GET['error']) && $_GET['error'] == 'already_applied'): ?>
    <div class="alert-box">You have already applied for a room. Please wait for admin approval.</div>
<?php endif; ?>


        <div class="room-container">
            <!-- Boys' Rooms -->
            <div class="room-section">
                <h2>Boys' Rooms</h2>
                <table>
                    <thead>
                        <tr>
                            <th>Room Number</th>
                            <th>Capacity</th>
                            <th>Occupied</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($room = mysqli_fetch_assoc($boys_rooms_result)): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($room['room_number']); ?></td>
                                <td><?php echo $room['capacity']; ?></td>
                                <td><?php echo $room['occupied']; ?></td>
                                <td>
                                    <?php if ($room['occupied'] < $room['capacity']): ?>
                                        <a class="button" href="apply_room.php?room_id=<?php echo $room['id']; ?>">Apply</a>
                                    <?php else: ?>
                                        <span>Room Full</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>

            <!-- Girls' Rooms -->
            <div class="room-section">
                <h2>Girls' Rooms</h2>
                <table>
                    <thead>
                        <tr>
                            <th>Room Number</th>
                            <th>Capacity</th>
                            <th>Occupied</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($room = mysqli_fetch_assoc($girls_rooms_result)): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($room['room_number']); ?></td>
                                <td><?php echo $room['capacity']; ?></td>
                                <td><?php echo $room['occupied']; ?></td>
                                <td>
                                    <?php if ($room['occupied'] < $room['capacity']): ?>
                                        <a class="button" href="apply_room.php?room_id=<?php echo $room['id']; ?>">Apply</a>
                                    <?php else: ?>
                                        <span>Room Full</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <a href="dashboard.php" class="back-button">← Back to Dashboard</a>

    </div>
</body>
</html>
