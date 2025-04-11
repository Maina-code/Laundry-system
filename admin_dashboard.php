<?php
session_start();
require_once '../database.php';

// Ensure admin is logged in
if (!isset($_SESSION['admin_id'])) {
    die("Unauthorized access. Please <a href='login.php'>log in</a> again.");
}

$db = new Database();
$conn = $db->conn;

// Fetch total providers
$query_providers = "SELECT COUNT(*) AS total FROM providers";
$total_providers = $conn->query($query_providers)->fetch_assoc()['total'] ?? 0;

// Fetch total customers/users
$query_customers = "SELECT COUNT(*) AS total FROM users";
$total_customers = $conn->query($query_customers)->fetch_assoc()['total'] ?? 0;

// Fetch pending applications
$query_pending = "SELECT COUNT(*) AS total FROM providers WHERE status = 'pending'";
$pending_applications = $conn->query($query_pending)->fetch_assoc()['total'] ?? 0;

// Fetch completed bookings
$query_completed = "SELECT COUNT(*) AS total FROM bookings WHERE status = 'completed'";
$completed_bookings = $conn->query($query_completed)->fetch_assoc()['total'] ?? 0;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
            background-color: #121212;
            color: white;
            display: flex;
        }

        .sidebar {
            width: 250px;
            height: 100vh;
            background: black;
            padding-top: 20px;
            position: fixed;
            left: 0;
            top: 0;
            text-align: center;
        }

        .sidebar h2 {
            color: #4CAF50;
            margin-bottom: 30px;
        }

        .sidebar ul {
            list-style: none;
            padding: 0;
        }

        .sidebar ul li {
            padding: 15px 0;
        }

        .sidebar ul li a {
            color: white;
            text-decoration: none;
            font-size: 18px;
            display: block;
            transition: 0.3s;
        }

        .sidebar ul li a:hover {
            background: #4CAF50;
            padding: 10px;
            border-radius: 5px;
        }

        .main-content {
            margin-left: 250px;
            padding: 20px;
            flex-grow: 1;
        }

        .topbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 20px;
            background: #1c1c1c;
            border-bottom: 2px solid #4CAF50;
        }

        .logout-btn {
            background: #4CAF50;
            color: white;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 5px;
            transition: 0.3s;
        }

        .logout-btn:hover {
            background: #388E3C;
        }

        .dashboard-stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-top: 20px;
        }

        .stat-card {
            background: #1c1c1c;
            padding: 20px;
            border-radius: 10px;
            text-align: center;
            box-shadow: 2px 2px 10px rgba(0, 255, 0, 0.3);
        }

        .stat-card h3 {
            margin: 0 0 10px;
            color: #4CAF50;
        }

        .management-section {
            margin-top: 30px;
            padding: 20px;
            background: #1c1c1c;
            border-radius: 10px;
        }

        .management-section h2 {
            color: #4CAF50;
        }

        .management-links a {
            display: inline-block;
            background: #4CAF50;
            color: white;
            padding: 10px 20px;
            margin: 10px;
            text-decoration: none;
            border-radius: 5px;
            transition: 0.3s;
        }

        .management-links a:hover {
            background: #388E3C;
        }
    </style>
</head>
<body>
    <div class="sidebar">
        <h2>Admin Panel</h2>
        <ul>
            <li><a href="#">Dashboard</a></li>
            <li><a href="manage_providers.php">Providers</a></li>
            <li><a href="manage_bookings.php">Bookings</a></li>
            <li><a href="manage_bookings.php">Services</a></li>
            <li><a href="manage_users.php">Users</a></li>
            <li><a href="analytics.php">Analytics</a></li>
            <li><a href="adminlogout.php">Logout</a></li>
        </ul>
    </div>
    
    <div class="main-content">
        <div class="topbar">
            <h1>Welcome, Admin</h1>
            <a href="logout.php" class="logout-btn">Logout</a>
        </div>
        
        <div class="dashboard-stats">
            <div class="stat-card">
                <h3>Total Providers</h3>
                <p><?php echo $total_providers; ?></p>
            </div>
            <div class="stat-card">
                <h3>Total Customers</h3>
                <p><?php echo $total_customers; ?></p>
            </div>
            <div class="stat-card">
                <h3>Pending Applications</h3>
                <p><?php echo $pending_applications; ?></p>
            </div>
            <div class="stat-card">
                <h3>Completed Bookings</h3>
                <p><?php echo $completed_bookings; ?></p>
            </div>
        </div>
        
        <div class="management-section">
            <h2>Manage System</h2>
            <p>Select a category to manage:</p>
            <div class="management-links">
                <a href="manage_providers.php">Manage Providers</a>
                <a href="manage_bookings.php">Manage Bookings</a>
                <a href="manage_bookings.php">Manage Services</a>
                <a href="manage_users.php">Manage Users</a>
            </div>
        </div>
    </div>
</body>
</html>
