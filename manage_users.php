<?php 
session_start();
require_once '../database.php'; // Ensure database connection

// Initialize database connection
$db = new Database();
$conn = $db->conn;

// Fetch all users
$sql = "SELECT * FROM users";
$result = $conn->query($sql);

// Handle user actions
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_POST['user_id'];

    if (isset($_POST['block_user'])) {
        $conn->query("UPDATE users SET status='Blocked' WHERE id=$user_id");
    } elseif (isset($_POST['unblock_user'])) {
        $conn->query("UPDATE users SET status='Active' WHERE id=$user_id");
    } elseif (isset($_POST['delete_user'])) {
        $conn->query("DELETE FROM users WHERE id=$user_id");
    }

    header("Location: manage_users.php");
    exit();
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Users</title>
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
        .block { background: #ff3333; color: white; }
        .unblock { background: #32CD32; color: black; }
        .delete { background: #cc0000; color: white; }
        .block:hover { background: #cc0000; }
        .unblock:hover { background: #28a745; }
        .delete:hover { background: #990000; }
    </style>
</head>
<body>
    <div class="container">
        <h2>Manage Users</h2>

        <table>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
            <?php while ($row = $result->fetch_assoc()) { ?>
            <tr>
                <td><?php echo $row['id']; ?></td>
                <td><?php echo $row['name']; ?></td>
                <td><?php echo $row['email']; ?></td>
                <td><?php echo $row['status']; ?></td>
                <td>
                    <?php if ($row['status'] == 'Active') { ?>
                        <form method="POST" style="display:inline;">
                            <input type="hidden" name="user_id" value="<?php echo $row['id']; ?>">
                            <button type="submit" name="block_user" class="btn block">Block</button>
                        </form>
                    <?php } else { ?>
                        <form method="POST" style="display:inline;">
                            <input type="hidden" name="user_id" value="<?php echo $row['id']; ?>">
                            <button type="submit" name="unblock_user" class="btn unblock">Unblock</button>
                        </form>
                    <?php } ?>
                    <form method="POST" style="display:inline;">
                        <input type="hidden" name="user_id" value="<?php echo $row['id']; ?>">
                        <button type="submit" name="delete_user" class="btn delete">Delete</button>
                    </form>
                </td>
            </tr>
            <?php } ?>
        </table>
    </div>
</body>
</html>
