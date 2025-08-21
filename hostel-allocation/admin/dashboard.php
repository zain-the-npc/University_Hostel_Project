<?php
session_start();
include("../db/connection.php");

if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <link rel="stylesheet" href="../css/admin_dashboard.css?v=1">
<link rel="preload" as="image" href="../images/adminpic.jpeg">


    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
</head>
<body>
    <div class="dashboard-container">
        <h1>Welcome Admin</h1>

        <div class="actions">
            <a href="all_users.php"><button>View All Users</button></a>
            <a href="applications.php"><button>View Applications</button></a>


        </div>

        <div class="logout-container">
            <a href="logout.php"><button>Logout</button></a>
        </div>
    </div>
</body>
</html>
