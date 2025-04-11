<?php
session_start();
require_once 'database.php'; // Ensure database connection
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}


// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Initialize database connection
$db = new Database();
$conn = $db->conn;

// Fetch user details
$sql = "SELECT * FROM users WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if (!$user) {
    echo "User not found.";
    exit();
}

// Handle profile update
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $email = $_POST['email'];

    // Update password if provided
    if (!empty($_POST['password'])) {
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $update_sql = "UPDATE users SET name=?, email=?, password=? WHERE id=?";
        $stmt = $conn->prepare($update_sql);
        $stmt->bind_param("sssi", $name, $email, $password, $user_id);
    } else {
        $update_sql = "UPDATE users SET name=?, email=? WHERE id=?";
        $stmt = $conn->prepare($update_sql);
        $stmt->bind_param("ssi", $name, $email, $user_id);
    }

    if ($stmt->execute()) {
        $_SESSION['success'] = "Profile updated successfully!";
        header("Location: edit_user_profile.php");
        exit();
    } else {
        $_SESSION['error'] = "Something went wrong. Please try again.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #000; /* Black Background */
            color: white;
            text-align: center;
            margin: 0;
            padding: 0;
        }
        .container {
            width: 50%;
            margin: auto;
            padding: 20px;
            background: #0a0f1a; /* Dark Blue Background */
            border-radius: 10px;
            margin-top: 50px;
            box-shadow: 0px 0px 10px rgba(0, 150, 255, 0.5);
        }
        h2 {
            color: #0096FF; /* Light Blue Heading */
        }
        input {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #0096FF; /* Blue Border */
            border-radius: 5px;
            background: #111;
            color: white;
        }
        button {
            background: #0096FF; /* Blue Button */
            color: black;
            padding: 10px 15px;
            border: none;
            cursor: pointer;
            border-radius: 5px;
            font-size: 16px;
            font-weight: bold;
        }
        button:hover {
            background: #0073CC; /* Darker Blue on Hover */
        }
        .error {
            color: red;
        }
        .success {
            color: #00FF99; /* Green Success Message */
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Edit Profile</h2>

    <?php if (isset($_SESSION['success'])) { echo "<p class='success'>".$_SESSION['success']."</p>"; unset($_SESSION['success']); } ?>
    <?php if (isset($_SESSION['error'])) { echo "<p class='error'>".$_SESSION['error']."</p>"; unset($_SESSION['error']); } ?>

    <form method="POST">
        <label>Name:</label>
        <input type="text" name="name" value="<?php echo htmlspecialchars($user['name']); ?>" required>

        <label>Email:</label>
        <input type="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>

        <label>Password (leave blank to keep current):</label>
        <input type="password" name="password">

        <button type="submit">Update Profile</button>
    </form>
</div>

</body>
</html>
