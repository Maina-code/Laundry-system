<?php
require_once '../database.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $db = new Database();
    $conn = $db->conn;

    $username = $conn->real_escape_string($_POST['username']);
    $email = $conn->real_escape_string($_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $query = "INSERT INTO admins (username, email, password) VALUES ('$username', '$email', '$password')";

    if ($conn->query($query) === TRUE) {
        echo "<script>alert('Admin Registered Successfully!'); window.location='admin_login.php';</script>";
    } else {
        echo "Error: " . $query . "<br>" . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Signup</title>
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
        <h2>Admin Signup</h2>
        <form method="POST">
            <input type="text" name="username" placeholder="Username" required><br>
            <input type="email" name="email" placeholder="Email" required><br>
            <input type="password" name="password" placeholder="Password" required><br>
            <button type="submit" class="btn">Sign Up</button>
        </form>
        <p>Already have an account? <a href="admin_login.php">Login here</a></p>
    </div>
</body>
</html>
