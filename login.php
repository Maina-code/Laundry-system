<?php
session_start();
require_once 'database.php';
require_once 'userfun.php';

$error = ""; // Initialize error message

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $user = new User();
    $loginResult = $user->login($email, $password);

    if ($loginResult === "Login successful!") {
        $_SESSION['user_id'] = $user->getUserId($email); // Correct way to set session
        header("Location: client_dashboard.php"); // Redirect to client dashboard
        exit();
    } else {
        $error = "Invalid email or password.";
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Client Login - Laundry System</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: url('backgroundimg.jpg') no-repeat center center/cover;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            text-align: center;
            margin: 0;
            padding: 0;
        }
        .login-container {
            background: rgba(0, 0, 0, 0.7);
            padding: 20px;
            border-radius: 10px;
            width: 350px;
            color: white;
            box-shadow: 2px 2px 10px rgba(0, 0, 0, 0.3);
        }
        h2 {
            margin-bottom: 20px;
            font-size: 2rem;
        }
        input {
            width: 90%;
            padding: 10px;
            margin: 10px 0;
            border: none;
            border-radius: 5px;
        }
        button {
            width: 100%;
            padding: 10px;
            background: #007bff;
            border: none;
            color: white;
            font-size: 1.2rem;
            border-radius: 5px;
            cursor: pointer;
            transition: 0.3s;
        }
        button:hover {
            background: #0056b3;
        }
        .error {
            color: red;
            font-size: 1rem;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h2>Client Login</h2>
        <?php if (!empty($error)) { echo "<p class='error'>$error</p>"; } ?>
        <form action="" method="POST">
            <input type="email" name="email" placeholder="Enter Email" required>
            <input type="password" name="password" placeholder="Enter Password" required>
            <button type="submit">Login</button>
        </form>
        <p>Don't have an account? <a href="signup.php">Sign Up</a></p>
    </div>
</body>
</html>
