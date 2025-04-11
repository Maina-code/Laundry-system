<?php
session_start();
require_once '../database.php'; // Ensure database connection

$db = new Database();
$conn = $db->conn;
$sql = "SELECT * FROM providers";
$result = $conn->query($sql);

// Handle approval, rejection, and deletion of providers
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['approve'])) {
        $provider_id = $_POST['provider_id'];
        $conn->query("UPDATE providers SET status='Approved' WHERE id=$provider_id");
    } elseif (isset($_POST['reject'])) {
        $provider_id = $_POST['provider_id'];
        $conn->query("UPDATE providers SET status='Rejected' WHERE id=$provider_id");
    } elseif (isset($_POST['delete'])) {
        $provider_id = $_POST['provider_id'];
        $conn->query("DELETE FROM providers WHERE id=$provider_id");
    }
    header("Location: manage_providers.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Providers</title>
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
        .delete { background: #ff9900; color: black; }
        .approve:hover { background: #28a745; }
        .reject:hover { background: #cc0000; }
        .delete:hover { background: #cc6600; }
    </style>
</head>
<body>
    <div class="container">
        <h2>Manage Service Providers</h2>
        <table>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Experience</th>
                <th>Services</th>
                <th>Rate ($/hr)</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
            <?php while ($row = $result->fetch_assoc()) { ?>
            <tr>
                <td><?php echo $row['id']; ?></td>
                <td><?php echo $row['name']; ?></td>
                <td><?php echo $row['email']; ?></td>
                <td><?php echo $row['experience']; ?> years</td>
                <td><?php echo $row['services']; ?></td>
                <td><?php echo $row['hourly_rate']; ?></td>
                <td><?php echo isset($row['status']) ? $row['status'] : 'Pending'; ?></td>
                <td><?php echo isset($row['status']) ? $row['status'] : 'Pending'; ?></td>
                <td>
                    <form method="POST">
                        <input type="hidden" name="provider_id" value="<?php echo $row['id']; ?>">
                        <?php if (!isset($row['status']) || $row['status'] == 'Pending') { ?>
                            <button type="submit" name="approve" class="btn approve">Approve</button>
                            <button type="submit" name="reject" class="btn reject">Reject</button>
                        <?php } ?>
                        <button type="submit" name="delete" class="btn delete">Delete</button>
                    </form>
                </td>

            </tr>
            <?php } ?>
        </table>
    </div>
</body>
</html>
