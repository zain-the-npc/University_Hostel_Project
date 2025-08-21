<?php
session_start();
include("../db/connection.php");

if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

$query = "
    SELECT 
        s.id,
        s.name,
        s.roll_no,
        s.email,
        s.gender,
        s.assigned_room,
        r.room_number AS assigned_room_name
    FROM students s
    LEFT JOIN rooms r ON s.assigned_room = r.id
";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>All Users</title>
    <link rel="stylesheet" type="text/css" href="../css/all_users.css">
    <link rel="preload" as="image" href="../images/adminpic.jpeg">
</head>
<body>
    <div class="container">
        <h1>All Registered Students</h1>

        <a href="dashboard.php"><button>Back to Dashboard</button></a>
        <br><br>

        <?php if (mysqli_num_rows($result) > 0): ?>
            <table border="1" cellpadding="10">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Roll No</th>
                        <th>Email</th>
                        <th>Gender</th>
                        <th>Assigned Room</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($student = mysqli_fetch_assoc($result)): ?>
                        <tr>
                            <td><?php echo $student['id']; ?></td>
                            <td><?php echo $student['name']; ?></td>
                            <td><?php echo $student['roll_no']; ?></td>
                            <td><?php echo $student['email']; ?></td>
                            <td><?php echo ucfirst($student['gender']); ?></td>
                            <td><?php echo $student['assigned_room_name'] ? $student['assigned_room_name'] : "None"; ?></td>
                            <td>
                                <?php if (!empty($student['assigned_room'])): ?>
                                    <a href="kick_user.php?student_id=<?php echo $student['id']; ?>" 
                                       onclick="return confirm('Are you sure you want to kick this user out of their room?');">
                                        <button>Kick</button>
                                    </a>
                                <?php else: ?>
                                    <span>N/A</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No students found.</p>
        <?php endif; ?>
    </div>
</body>
</html>
