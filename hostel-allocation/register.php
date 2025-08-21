<?php
session_start();
include("db/connection.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    $roll_no = mysqli_real_escape_string($conn, $_POST['roll_no']);
    $gender = mysqli_real_escape_string($conn, $_POST['gender']);

    // Password hashing for security
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Check if the email or roll number already exists in the database
    $check_query = "SELECT * FROM students WHERE email = '$email' OR roll_no = '$roll_no'";
    $check_result = mysqli_query($conn, $check_query);



   if (mysqli_num_rows($check_result) > 0) {
    $warning = "⚠️ Email or Roll Number already exists. Please try again.";
} else {
    // ...

        // Insert new student record into the database
        $insert_query = "INSERT INTO students (name, email, password, roll_no, gender) VALUES ('$name', '$email', '$hashed_password', '$roll_no', '$gender')";
        
        if (mysqli_query($conn, $insert_query)) {
    $success = "✅ Registration successful! You can now log in.";
} else {
    $error = "❌ Error: " . mysqli_error($conn);
}

    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Registration</title>
</head>
<link rel="stylesheet" href="css/register.css">
<link rel="preload" as="image" href="images/flowerspicbridge.jpeg">

</head>
<body>
    <div class="register-container">
        <h1>Student Registration</h1>

<?php if (isset($success)) echo "<div class='success-message'>$success</div>"; ?>
<?php if (isset($error)) echo "<div class='error-message'>$error</div>"; ?>
<?php if (isset($warning)) echo "<div class='warning-message'>$warning</div>"; ?>

        <form action="register.php" method="POST">
            <label for="name">Full Name:</label>
            <input type="text" id="name" name="name" required>

            <label for="roll_no">Roll Number:</label>
            <input type="text" id="roll_no" name="roll_no" required>

            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>

            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>

            <label for="gender">Gender:</label>
            <select id="gender" name="gender" required>
                <option value="">-- Select Gender --</option>
                <option value="male">Male</option>
                <option value="female">Female</option>
            </select>

            <button type="submit">Register</button>
        </form>

        <a href="login.php">Already have an account? Login here</a>
    </div>
</body>
</html>
