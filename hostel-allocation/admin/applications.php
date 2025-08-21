<?php
session_start();
include("../db/connection.php");

if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

// Join applications with students and rooms
$query = "SELECT 
            a.id AS application_id,
            a.status,
            s.name AS student_name,
            s.roll_no,
            r.room_number
          FROM applications AS a
          JOIN students AS s ON a.student_id = s.id
          JOIN rooms AS r ON a.room_id = r.id
          WHERE a.status = 'pending'";

$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<link rel="preload" as="image" href="../images/adminpic.jpeg">

    <link rel="stylesheet" type="text/css" href="../css/applications.css">
    <meta charset="UTF-8">
    <title>Pending Applications</title>
    <style>
        .message-box {
            background-color: rgba(0, 0, 0, 0.6);
            padding: 30px 40px;
            border-radius: 12px;
            box-shadow: 0px 10px 25px rgba(0, 0, 0, 0.7);
            max-width: 600px;
            margin: 40px auto;
        }

        .message-box h2 {
            font-size: 26px;
            font-weight: 600;
            color: #ffffff;
            margin: 0;
            text-shadow: 1px 1px 3px rgba(0, 0, 0, 0.5);
        }
    </style>
</head>
<body>

    <h1>Room Allocation Applications (Pending)</h1>

    <!-- Back Button -->
    <a href="dashboard.php"><button>Back to Dashboard</button></a>

    <?php if (mysqli_num_rows($result) > 0): ?>
        <table border="1" cellpadding="10">
            <thead>
                <tr>
                    <th>Student Name</th>
                    <th>Roll Number</th>
                    <th>Requested Room</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = mysqli_fetch_assoc($result)): ?>
                    <tr>
                        <td><?php echo $row['student_name']; ?></td>
                        <td><?php echo $row['roll_no']; ?></td>
                        <td><?php echo $row['room_number']; ?></td>
                        <td><?php echo ucfirst($row['status']); ?></td>
                        <td>
                            <a href="approve_reject.php?id=<?php echo $row['application_id']; ?>&action=approve">Approve</a> |
                            <a href="approve_reject.php?id=<?php echo $row['application_id']; ?>&action=reject">Reject</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php else: ?>
        <div class="message-box">
            <h2>No pending applications found.</h2>
        </div>
    <?php endif; ?>
</body>
</html>
