<?php
session_start();
require_once '../database.php'; // Ensure database connection

// Fetch all services
$sql = "SELECT * FROM services";
$result = $conn->query($sql);

// Handle adding, editing, and deleting services
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['add_service'])) {
        $service_name = $_POST['service_name'];
        $conn->query("INSERT INTO services (name) VALUES ('$service_name')");
    } elseif (isset($_POST['edit_service'])) {
        $service_id = $_POST['service_id'];
        $service_name = $_POST['service_name'];
        $conn->query("UPDATE services SET name='$service_name' WHERE id=$service_id");
    } elseif (isset($_POST['delete_service'])) {
        $service_id = $_POST['service_id'];
        $conn->query("DELETE FROM services WHERE id=$service_id");
    }
    header("Location: manage_services.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Services</title>
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
        input {
            padding: 10px;
            width: 80%;
            margin: 10px 0;
            border: 1px solid #555;
            background: #222;
            color: white;
        }
        .btn {
            padding: 8px 15px;
            margin: 5px;
            border: none;
            cursor: pointer;
            font-size: 14px;
        }
        .add { background: #32CD32; color: black; }
        .edit { background: #ff9900; color: black; }
        .delete { background: #ff3333; color: white; }
        .add:hover { background: #28a745; }
        .edit:hover { background: #cc6600; }
        .delete:hover { background: #cc0000; }
    </style>
</head>
<body>
    <div class="container">
        <h2>Manage Services</h2>

        <!-- Add Service Form -->
        <form method="POST">
            <input type="text" name="service_name" placeholder="Enter new service name" required>
            <button type="submit" name="add_service" class="btn add">Add Service</button>
        </form>

        <table>
            <tr>
                <th>ID</th>
                <th>Service Name</th>
                <th>Actions</th>
            </tr>
            <?php while ($row = $result->fetch_assoc()) { ?>
            <tr>
                <td><?php echo $row['id']; ?></td>
                <td><?php echo $row['name']; ?></td>
                <td>
                    <form method="POST" style="display:inline;">
                        <input type="hidden" name="service_id" value="<?php echo $row['id']; ?>">
                        <input type="text" name="service_name" value="<?php echo $row['name']; ?>" required>
                        <button type="submit" name="edit_service" class="btn edit">Edit</button>
                    </form>
                    <form method="POST" style="display:inline;">
                        <input type="hidden" name="service_id" value="<?php echo $row['id']; ?>">
                        <button type="submit" name="delete_service" class="btn delete">Delete</button>
                    </form>
                </td>
            </tr>
            <?php } ?>
        </table>
    </div>
</body>
</html>
