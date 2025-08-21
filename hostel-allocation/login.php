<?php
session_start();
include("db/connection.php");

if (isset($_POST['login'])) {
    $roll_no = $_POST['roll_no'];
    $password = $_POST['password'];

    $query = "SELECT * FROM students WHERE roll_no='$roll_no'";
    $result = mysqli_query($conn, $query);

    if ($result && mysqli_num_rows($result) == 1) {
        $user = mysqli_fetch_assoc($result);

        if (password_verify($password, $user['password'])) {
            $_SESSION['student_id'] = $user['id'];
            $_SESSION['name'] = $user['name'];
            header("Location: dashboard.php");
            exit();
        } else {
            $error = "Invalid password!";
        }
    } else {
        $error = "Student not found!";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<link rel="preload" as="image" href="images/flowerspicbridge.jpeg">


    <title>Student Login</title>
    <link rel="stylesheet" href="css/login.css"> <!-- Link to CSS file -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
</head>
<body>

    <div class="login-container">
        <h2>Student Login</h2>

        <?php if (isset($error)) echo "<p class='error-message'>$error</p>"; ?>
        <img src="images/logo.png" alt="Hostel Logo" width="80" style="margin-bottom: 15px;">
        <h2>Welcome Back!</h2>

        <form action="" method="POST">
            <input type="text" name="roll_no" placeholder="Roll Number" required><br>
            <input type="password" name="password" placeholder="Password" required><br>
            <button type="submit" name="login">Login</button>
        </form>

        <form action="register.php" method="GET">
            <button type="submit">New Student? Register Here</button>
        </form>

        <form action="admin/login.php" method="GET">
            <button type="submit">Login as Admin</button>
        </form>
    </div>

</body>
</html>
