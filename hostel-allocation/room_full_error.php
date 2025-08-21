<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Room Full</title>

    <!-- Preload for optimization -->
    <link rel="preload" as="image" href="images/hostelpic.jpeg">

    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: url('images/hostelpic.jpeg') no-repeat center center fixed;
            background-size: cover;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
            position: relative;
            color: white;
        }

        body::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            height: 100%;
            width: 100%;
            background: rgba(0, 0, 0, 0.6);
            z-index: 0;
        }

        .message-box {
            background: rgba(0, 0, 0, 0.75);
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.5);
            text-align: center;
            z-index: 1;
            max-width: 600px;
            width: 90%;
        }

        .message-box p {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 30px;
        }

        .back-btn {
            display: inline-block;
            padding: 15px 30px;
            background-color: #4e73df;
            color: white;
            text-decoration: none;
            border-radius: 8px;
            font-size: 18px;
            transition: 0.3s ease;
        }

        .back-btn:hover {
            background-color: #2e59d9;
            transform: scale(1.05);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
        }
    </style>
</head>
<body>

    <!-- 🔥 Force preload by rendering image invisibly -->
    <img src="images/hostelpic.jpeg" alt="preload" style="display:none;" loading="eager">

    <div class="message-box">
        <p>Sorry! This room is already full. Please choose another room.</p>
        <a href="dashboard.php" class="back-btn">Back to Dashboard</a>
    </div>
</body>
</html>
