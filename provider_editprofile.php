<?php
session_start();
require_once 'database.php'; // Ensure database connection

// Check if the provider is logged in
if (!isset($_SESSION['provider_id'])) {
    header("Location: login.php");
    exit();
}

$provider_id = $_SESSION['provider_id'];

// Initialize database connection
$db = new Database();
$conn = $db->conn;

// Fetch provider details
$sql = "SELECT * FROM providers WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $provider_id);
$stmt->execute();
$result = $stmt->get_result();
$provider = $result->fetch_assoc();

if (!$provider) {
    echo "Provider not found.";
    exit();
}

// Handle profile update
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $services = $_POST['services'];
    $charges = $_POST['charges'];
    $experience = $_POST['experience'];

    // Update password if provided
    if (!empty($_POST['password'])) {
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $update_sql = "UPDATE providers SET name=?, email=?, password=?, services=?, charges=?, experience=? WHERE id=?";
        $stmt = $conn->prepare($update_sql);
        $stmt->bind_param("ssssiii", $name, $email, $password, $services, $charges, $experience, $provider_id);
    } else {
        $update_sql = "UPDATE providers SET name=?, email=?, services=?, hourly_rate=?, experience=? WHERE id=?";
        $stmt = $conn->prepare($update_sql);
        $stmt->bind_param("sssiii", $name, $email, $services, $charges, $experience, $provider_id);
    }

    if ($stmt->execute()) {
        $_SESSION['success'] = "Profile updated successfully!";
        header("Location: provider_dashboard.php");
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
        input, select {
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
        <input type="text" name="name" value="<?php echo htmlspecialchars($provider['name']); ?>" required>

        <label>Email:</label>
        <input type="email" name="email" value="<?php echo htmlspecialchars($provider['email']); ?>" required>

        <label>Password (leave blank to keep current):</label>
        <input type="password" name="password">

        <label>Services Offered:</label>
        <input type="text" name="services" value="<?php echo htmlspecialchars($provider['services']); ?>" required>

        <label>Charges per Hour ($):</label>
        <input type="number" name="charges" value="<?php echo $provider['hourly_rate']; ?>" required>

        <label>Years of Experience:</label>
        <input type="number" name="experience" value="<?php echo $provider['experience']; ?>" required>

        <button type="submit">Update Profile</button>
    </form>
</div>

</body>
</html>
