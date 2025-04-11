<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once 'database.php';

if (!isset($_SESSION['client_id'])) {
    die("Unauthorized access. Please <a href='login.php'>log in</a> again.");
}

$db = new Database();
$conn = $db->conn;

$client_id = $_SESSION['client_id'] ?? null;

if (!$client_id) {
    die("Invalid session data. Please <a href='login.php'>log in</a> again.");
}

// Fetch client details
$query_client = "SELECT * FROM users WHERE id = ?";
$stmt_client = $conn->prepare($query_client);
$stmt_client->bind_param("i", $client_id);
$stmt_client->execute();
$client = $stmt_client->get_result()->fetch_assoc();

if (!$client) {
    die("Client not found in database.");
}

// Fetch client bookings with provider and service details
$query = "SELECT 
    b.id AS booking_id, 
    b.client_id, 
    b.provider_id, 
    b.booking_date AS date,  
    b.status, 
    p.name AS provider_name, 
    p.services AS service
FROM bookings b
JOIN providers p ON b.provider_id = p.id
WHERE b.client_id = ?
ORDER BY FIELD(b.status, 'pending', 'accepted', 'completed'), b.created_at DESC";

$stmt = $conn->prepare($query);
$stmt->bind_param("i", $client_id);
$stmt->execute();
$result = $stmt->get_result();
$bookings = $result->fetch_all(MYSQLI_ASSOC);

// Check for unread booking notifications
$unread_notifications = 0;
foreach ($bookings as $booking) {
    if ($booking['status'] == 'pending' || $booking['status'] == 'accepted') {
        $unread_notifications++;
    }
}

// Handle cancel request
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['cancel_booking'])) {
    $booking_id = $_POST['booking_id'];

    $cancel_query = "DELETE FROM bookings WHERE id = ? AND client_id = ?";
    $stmt_cancel = $conn->prepare($cancel_query);
    $stmt_cancel->bind_param("ii", $booking_id, $client_id);
    
    if ($stmt_cancel->execute()) {
        echo "<script>alert('Booking cancelled successfully!'); window.location.href='client_dashboard.php';</script>";
    } else {
        echo "<script>alert('Failed to cancel booking.');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Client Dashboard</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            background: #e3f2fd;
            display: flex;
        }

        .sidebar {
            width: 250px;
            height: 100vh;
            background: #0d47a1;
            color: white;
            position: fixed;
            padding: 20px;
        }

        .sidebar h2 {
            text-align: center;
        }

        .sidebar ul {
            list-style: none;
            padding: 0;
            margin-top: 20px;
        }

        .sidebar ul li {
            margin: 10px 0;
        }

        .sidebar ul li a {
            color: white;
            text-decoration: none;
            font-size: 18px;
            display: block;
            padding: 10px;
            border-radius: 5px;
        }

        .sidebar ul li a:hover {
            background: #1565c0;
        }

        .main-content {
            margin-left: 270px;
            padding: 20px;
            width: 100%;
        }

        h1 {
            color: #0d47a1;
        }

        .dashboard-card {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0px 0px 10px rgba(0,0,0,0.1);
            margin-bottom: 20px;
            text-align: center;
        }

        .dashboard-card h3 {
            margin: 0 0 10px;
            color: #1976d2;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        table, th, td {
            border: 1px solid #ddd;
        }

        th, td {
            padding: 10px;
            text-align: center;
        }

        th {
            background: #1976d2;
            color: white;
        }

        .status {
            padding: 5px 10px;
            border-radius: 5px;
            color: white;
        }

        .status.pending {
            background: orange;
        }

        .status.accepted {
            background: green;
        }

        .status.completed {
            background: blue;
        }

        .status.cancelled {
            background: gray;
        }

        .no-bookings {
            text-align: center;
            color: gray;
            padding: 10px;
        }

        .cancel-btn {
            background: red;
            color: white;
            padding: 5px 10px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .cancel-btn:hover {
            background: darkred;
        }
    </style>
</head>
<body>
    <div class="sidebar">
        <h2>Welcome, <?php echo htmlspecialchars($client['name'] ?? 'Guest'); ?></h2>
        <ul>
            <li><a href="client_dashboard.php">Dashboard</a></li>
            <li><a href="book_services.php">Book a Service</a></li>
            <li><a href="user_editprofile.php">Edit Profile</a></li>
            <li><a href="logout.php">Logout</a></li>
        </ul>
    </div>

    <div class="main-content">
        <h1>Client Dashboard</h1>

        <?php if ($unread_notifications > 0) { ?>
            <div class="notification">🔔 <?php echo $unread_notifications; ?> new updates</div>
        <?php } ?>

        <div class="dashboard-card">
            <h3>Your Bookings</h3>
            <table>
                <tr>
                    <th>Service</th>
                    <th>Provider</th>
                    <th>Date</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
                <?php if (!empty($bookings)) { 
                    foreach ($bookings as $booking) { ?>
                        <tr>
                            <td><?php echo htmlspecialchars($booking['service'] ?? 'N/A'); ?></td>
                            <td><?php echo htmlspecialchars($booking['provider_name'] ?? 'N/A'); ?></td>
                            <td><?php echo htmlspecialchars($booking['date'] ?? 'N/A'); ?></td>
                            <td>
                                <span class="status <?php echo strtolower($booking['status']); ?>">
                                    <?php echo ucfirst($booking['status']); ?>
                                </span>
                            </td>
                            <td>
                                <?php if ($booking['status'] == 'pending') { ?>
                                    <form method="POST">
                                        <input type="hidden" name="booking_id" value="<?php echo $booking['booking_id']; ?>">
                                        <button type="submit" name="cancel_booking" class="cancel-btn">Cancel</button>
                                    </form>
                                <?php } else { ?>
                                    <span style="color: gray;">N/A</span>
                                <?php } ?>
                            </td>
                        </tr>
                <?php } } else { ?>
                    <tr><td colspan="5" class="no-bookings">No bookings found.</td></tr>
                <?php } ?>
            </table>
        </div>
    </div>
</body>
</html>
