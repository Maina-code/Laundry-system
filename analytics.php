<?php
session_start();
require_once '../database.php';

// Fetch total providers
$providers_result = $conn->query("SELECT COUNT(*) AS total FROM providers");
$providers_count = $providers_result->fetch_assoc()['total'];

// Fetch total customers
$customers_result = $conn->query("SELECT COUNT(*) AS total FROM users");
$customers_count = $customers_result->fetch_assoc()['total'];

// Fetch completed bookings
$completed_bookings_result = $conn->query("SELECT COUNT(*) AS total FROM bookings WHERE status='Completed'");
$completed_bookings = $completed_bookings_result->fetch_assoc()['total'];

// Fetch pending applications
$pending_providers_result = $conn->query("SELECT COUNT(*) AS total FROM providers WHERE status='Pending'");
$pending_providers = $pending_providers_result->fetch_assoc()['total'];

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Analytics Dashboard</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #111;
            color: white;
            text-align: center;
            margin: 0;
            padding: 0;
        }
        .container {
            width: 90%;
            margin: auto;
            padding: 20px;
        }
        h2 {
            color: #32CD32;
        }
        .stats-container {
            display: flex;
            justify-content: space-around;
            flex-wrap: wrap;
            margin-top: 20px;
        }
        .stat-box {
            background: #222;
            padding: 20px;
            border-radius: 8px;
            width: 22%;
            text-align: center;
            box-shadow: 0 0 10px rgba(50, 205, 50, 0.5);
        }
        .stat-box h3 {
            color: #32CD32;
            margin-bottom: 10px;
        }
        .stat-box p {
            font-size: 24px;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>System Analytics</h2>

        <div class="stats-container">
            <div class="stat-box">
                <h3>Total Providers</h3>
                <p><?php echo $providers_count; ?></p>
            </div>
            <div class="stat-box">
                <h3>Total Customers</h3>
                <p><?php echo $customers_count; ?></p>
            </div>
            <div class="stat-box">
                <h3>Completed Bookings</h3>
                <p><?php echo $completed_bookings; ?></p>
            </div>
            <div class="stat-box">
                <h3>Pending Applications</h3>
                <p><?php echo $pending_providers; ?></p>
            </div>
        </div>
    </div>
</body>
</html>
