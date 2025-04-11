<?php
session_start();

// Check if provider is logged in and approved
if (isset($_SESSION['provider_status']) && $_SESSION['provider_status'] == "Approved") {
    header("Location: provider_dashboard.php");
    exit();
}

// Check if the user is logged in and retrieve their name
$userName = isset($_SESSION['user_name']) ? $_SESSION['user_name'] : "Guest";

// Check if a message exists (e.g., "Application submitted")
$message = isset($_GET['message']) ? $_GET['message'] : "";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home - Laundry Service</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: url('backgroundimg.jpg') no-repeat center center/cover;
            height: 100vh;
            margin: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            color: white;
            text-align: center;
        }

        /* Navigation Bar */
        .navbar {
            position: absolute;
            top: 0;
            width: 100%;
            display: flex;
            justify-content: space-between;
            padding: 15px 50px;
            background: rgba(0, 0, 0, 0.6);
            backdrop-filter: blur(10px);
        }

        .navbar .logo {
            font-size: 1.8rem;
            font-weight: bold;
            color: white;
        }

        .navbar ul {
            list-style: none;
            padding: 0;
            margin: 0;
            display: flex;
        }

        .navbar ul li {
            margin: 0 15px;
        }

        .navbar ul li a {
            color: white;
            text-decoration: none;
            font-size: 1.2rem;
            transition: color 0.3s ease;
        }

        .navbar ul li a:hover {
            color: #00aaff;
        }

        /* Welcome Message */
        .welcome {
            font-size: 2.5rem;
            text-shadow: 2px 2px 5px rgba(0, 0, 0, 0.5);
        }

        /* Notification Message */
        .message-box {
            background: rgba(0, 123, 255, 0.8);
            padding: 15px;
            border-radius: 8px;
            margin-top: 20px;
            font-size: 1.2rem;
            display: inline-block;
        }

        /* Hero Section */
        .hero {
            margin-top: 50px;
            max-width: 600px;
        }

        .hero p {
            font-size: 1.2rem;
            line-height: 1.6;
        }

        .btn {
            display: inline-block;
            background: #007bff;
            color: white;
            padding: 12px 24px;
            text-decoration: none;
            border-radius: 8px;
            margin-top: 20px;
            font-size: 1.2rem;
            transition: background 0.3s ease;
            box-shadow: 2px 2px 10px rgba(0, 0, 0, 0.3);
        }

        .btn:hover {
            background: #0056b3;
        }
    </style>
</head>
<body>

    <!-- Navigation Bar -->
    <div class="navbar">
        <div class="logo">Laundry Service</div>
        <ul>
            <li><a href="portal.php">My Portal</a></li>
            <li><a href="about.php">About</a></li>
            <li><a href="contact.php">Contact</a></li>
            <li><a href="provider_login.php">login</a></li>
        </ul>
    </div>

    <!-- Welcome Message -->
    <h1 class="welcome">Welcome, <?php echo htmlspecialchars($userName); ?>!</h1>

    <!-- Message from provider signup (only if provider is not approved) -->
    <?php if (!empty($message) && (!isset($_SESSION['provider_status']) || $_SESSION['provider_status'] != "Approved")): ?>
        <div class="message-box"><?php echo htmlspecialchars($message); ?></div>
    <?php endif; ?>

    <!-- Hero Section -->
    <div class="hero">
        <p>Experience the most reliable and efficient laundry service. We connect you with top-rated service providers to handle your laundry needs with ease.</p>
        <a href="services.php" class="btn">Explore Services</a>
    </div>

</body>
</html>
