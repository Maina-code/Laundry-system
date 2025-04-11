<?php
session_start();
require_once '../database.php'; 

// Initialize database connection
$db = new Database();
$conn = $db->conn;

// Handle booking approval and rejection
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['approve_booking'])) {
        $booking_id = $_POST['booking_id'];
        $conn->query("UPDATE bookings SET status='Approved' WHERE id=$booking_id");
    } elseif (isset($_POST['reject_booking'])) {
        $booking_id = $_POST['booking_id'];
        $conn->query("UPDATE bookings SET status='Rejected' WHERE id=$booking_id");
    }
    header("Location: manage_bookings.php");
    exit();
}

// Fetch updated bookings
$sql = "SELECT 
            b.id, 
            u.name AS customer_name, 
            p.services AS service, 
            p.name AS provider, 
            b.status 
        FROM bookings b
        LEFT JOIN users u ON b.client_id = u.id
        LEFT JOIN providers p ON b.provider_id = p.id";

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Bookings</title>
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
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #444;
            padding: 10px;
            text-align: left;
        }
        th {
            background: #32CD32;
            color: black;
        }
        tr:nth-child(even) {
            background: #222;
        }
        tr:nth-child(odd) {
            background: #333;
        }
        .btn {
            padding: 8px 15px;
            margin: 5px;
            border: none;
            cursor: pointer;
            font-size: 14px;
        }
        .approve { background: #32CD32; color: black; }
        .reject { background: #ff3333; color: white; }
        .approve:hover { background: #28a745; }
        .reject:hover { background: #cc0000; }
    </style>
</head>
<body>
    <div class="container">
        <h2>Manage Bookings</h2>

        <table>
            <tr>
                <th>ID</th>
                <th>Customer Name</th>
                <th>Service</th>
                <th>Provider</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
            <?php while ($row = $result->fetch_assoc()) { ?>
            <tr>
                <td><?php echo $row['id']; ?></td>
                <td><?php echo isset($row['customer_name']) ? $row['customer_name'] : 'Unknown'; ?></td>
                <td><?php echo isset($row['service']) ? $row['service'] : 'Unknown'; ?></td>
                <td><?php echo isset($row['provider']) ? $row['provider'] : 'Unknown'; ?></td>
                <td>
                    <strong style="color: <?php echo ($row['status'] == 'Approved') ? '#32CD32' : (($row['status'] == 'Rejected') ? '#ff3333' : '#FFD700'); ?>">
                        <?php echo $row['status']; ?>
                    </strong>
                </td>
                <td>
                    <?php if ($row['status'] == 'Pending') { ?>
                        <form method="POST" style="display:inline;">
                            <input type="hidden" name="booking_id" value="<?php echo $row['id']; ?>">
                            <button type="submit" name="approve_booking" class="btn approve">Approve</button>
                        </form>
                        <form method="POST" style="display:inline;">
                            <input type="hidden" name="booking_id" value="<?php echo $row['id']; ?>">
                            <button type="submit" name="reject_booking" class="btn reject">Reject</button>
                        </form>
                    <?php } ?>
                </td>
            </tr>
            <?php } ?>
        </table>
    </div>
</body>
</html>
