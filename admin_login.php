<?php
session_start();
require_once '../database.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $db = new Database();
    $conn = $db->conn;

    $email = $conn->real_escape_string($_POST['email']);
    $password = $_POST['password'];

    $query = "SELECT * FROM admins WHERE email='$email'";
    $result = $conn->query($query);

    if ($result->num_rows == 1) {
        $admin = $result->fetch_assoc();
        if (password_verify($password, $admin['password'])) {
            $_SESSION['admin_id'] = $admin['id'];
            $_SESSION['admin_username'] = $admin['username'];
            header("Location: admin_dashboard.php");
            exit();
        } else {
            echo "<script>alert('Invalid Credentials');</script>";
        }
    } else {
        echo "<script>alert('Admin Not Found');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <style>
        body {
            background-color: #121212;
            color: white;
            font-family: Arial, sans-serif;
            text-align: center;
        }
        .container {
            margin-top: 100px;
        }
        input {
            padding: 10px;
            width: 300px;
            margin: 10px;
            border: 1px solid #4CAF50;
            background: #1c1c1c;
            color: white;
        }
        .btn {
            background: #4CAF50;
            padding: 10px 20px;
            border: none;
            color: white;
            cursor: pointer;
            margin-top: 10px;
        }
        .btn:hover {
            background: #388E3C;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Admin Login</h2>
        <form method="POST">
            <input type="email" name="email" placeholder="Email" required><br>
            <input type="password" name="password" placeholder="Password" required><br>
            <button type="submit" class="btn">Login</button>
        </form>
        <p>Don't have an account? <a href="admin_signup.php">Sign up here</a></p>
    </div>
</body>
</html>
