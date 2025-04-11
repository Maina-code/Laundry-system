<?php
session_start();
require_once 'database.php';
require_once 'Provider.php';

$error = ""; // Initialize error message

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $provider = new Provider();
    $loggedInProvider = $provider->loginProvider($email, $password);

    if ($loggedInProvider === "not_approved") {
        $error = "Your account is not approved yet. Please wait for admin approval.";
    } elseif ($loggedInProvider) {
        $_SESSION['provider_id'] = $loggedInProvider['id'];
        $_SESSION['provider_name'] = $loggedInProvider['name'];
        $_SESSION['provider_status'] = $loggedInProvider['status'];

        // Redirect to dashboard
        header("Location: provider_dashboard.php");
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
    <title>Provider Login</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: url('backgroundimg.jpg') no-repeat center center/cover;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            text-align: center;
            color: white;
        }

        .container {
            width: 40%;
            background: rgba(255, 255, 255, 0.1);
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.3);
            backdrop-filter: blur(10px);
        }

        h2 {
            font-size: 2rem;
            margin-bottom: 20px;
        }

        .form-group {
            margin: 15px 0;
            position: relative;
        }

        .form-group input {
            width: 100%;
            padding: 12px;
            background: transparent;
            border: none;
            border-bottom: 2px solid white;
            color: white;
            font-size: 1rem;
            outline: none;
        }

        .form-group input::placeholder {
            color: rgba(255, 255, 255, 0.7);
        }

        .btn {
            background: #007bff;
            color: white;
            border: none;
            padding: 12px 20px;
            cursor: pointer;
            width: 100%;
            font-size: 1.2rem;
            border-radius: 8px;
            margin-top: 20px;
            transition: background 0.3s ease;
        }

        .btn:hover {
            background: #0056b3;
        }

        .signup-link {
            margin-top: 15px;
            font-size: 1rem;
            color: white;
        }

        .signup-link a {
            color: #00aaff;
            text-decoration: none;
            font-weight: bold;
        }

        .signup-link a:hover {
            text-decoration: underline;
        }

        .error {
            color: red;
            font-size: 1rem;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Provider Login</h2>
        <?php if (!empty($error)) { echo "<p class='error'>$error</p>"; } ?>
        <form action="" method="POST">
            <div class="form-group">
                <input type="email" name="email" placeholder="Email Address" required>
            </div>
            <div class="form-group">
                <input type="password" name="password" placeholder="Password" required>
            </div>

            <button type="submit" class="btn">Login</button>
        </form>

        <p class="signup-link">Don't have an account? <a href="provider_signup.php">Sign up here</a></p>
    </div>
</body>
</html>
