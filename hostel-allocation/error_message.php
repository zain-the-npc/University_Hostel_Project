<?php
// This will check if the error is set in the query string
$error_message = '';
if (isset($_GET['error']) && $_GET['error'] == 'gender_mismatch') {
    $error_message = "You can only apply for rooms that match your gender.";
} elseif (isset($_GET['error']) && $_GET['error'] == 'room_full') {
    $error_message = "This room is full. Please choose another.";
}
?>
<!DOCTYPE html>
<link rel="preload" as="image" href="images/daxaab.jpeg">

<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Error - Room Application</title>
    <style>
        body {
            margin: 0;
            height: 100vh;
            background: linear-gradient(rgba(0, 0, 0, 0.6), rgba(0, 0, 0, 0.6)), 
                        url('images/daxaab.jpeg') no-repeat center center fixed;
            background-size: 110%; /* Zoomed out a little */
            display: flex;
            justify-content: flex-start;
            align-items: center;
            color: white;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .alert-box {
            background-color: rgba(20, 20, 20, 0.9); /* Black theme box */
            padding: 35px 40px;
            border-radius: 20px;
            margin-left: 15vw; /* Pushes it ~3-4 inches from center */
            max-width: 500px;
            width: 90%;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.6);
            animation: slideIn 0.6s ease;
        }

        .alert-box p {
            margin: 0 0 20px;
            font-size: 20px;
            line-height: 1.5;
            color: #ff6b6b;
            font-weight: bold;
        }

        .back-button {
            display: inline-block;
            background-color: #1f8ef1;
            color: white;
            padding: 12px 24px;
            text-decoration: none;
            border-radius: 8px;
            font-size: 16px;
            transition: background-color 0.3s ease;
        }

        .back-button:hover {
            background-color: #0f71c4;
        }

        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateX(-50px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }
    </style>
</head>
<body>
    <div class="alert-box">
        <p><?php echo $error_message; ?></p>
        <a href="available_rooms.php" class="back-button">Back to Room Selection</a>
    </div>
</body>
</html>
