<?php
require_once 'userfun.php'; 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    $user = new User(); 
    $result = $user->register($name, $email, $password);

    if ($result) {
        echo "<script>alert('Registration successful! You can now log in.'); window.location.href='login.php';</script>";
    } else {
        echo "<script>alert('Registration failed. Email might be already used.');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up - Laundry Service</title>
    <!-- <link rel="stylesheet" href="styles.css"> -->
     <style>
    body {
    margin: 0;
    padding: 0;
    font-family: Arial, sans-serif;
    background: url('backgroundimg.jpg') no-repeat center center/cover;
    height: 100vh;
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    text-align: center;
    color: white;
}

.header-title {
    font-size: 3.5rem;
    font-family: 'Georgia', serif;
    font-weight: bold;
    text-shadow: 2px 2px 5px rgba(0, 0, 0, 0.5);
    margin-bottom: 30px;
    position: absolute;
    top: 10%;
    left: 50%;
    transform: translateX(-50%);
}

.signup-container {
    background: rgba(0, 0, 0, 0.7);
    padding: 30px;
    border-radius: 10px;
    width: 90%;
    max-width: 400px;
    box-shadow: 2px 2px 10px rgba(0, 0, 0, 0.5);
}

.signup-container h2 {
    margin-bottom: 20px;
    font-size: 2rem;
}

.input-group {
    text-align: left;
    margin-bottom: 15px;
}

.input-group label {
    display: block;
    font-weight: bold;
    margin-bottom: 5px;
}

.input-group input {
    width: 100%;
    padding: 10px;
    border-radius: 5px;
    border: none;
    font-size: 1rem;
}

.btn {
    display: block;
    width: 100%;
    background: #007bff;
    color: white;
    padding: 12px;
    border: none;
    border-radius: 5px;
    font-size: 1.2rem;
    cursor: pointer;
    transition: background 0.3s ease;
}

.btn:hover {
    background: #0056b3;
}

p {
    margin-top: 15px;
    font-size: 1rem;
}

p a {
    color: #00aaff;
    text-decoration: none;
}

p a:hover {
    text-decoration: underline;
}
</style>
</head>
<body>

    <div class="signup-container">
        <form action="signup.php" method="POST">
            
            <div class="input-group">
                <label for="name">Full Name</label>
                <input type="text" name="name" id="name" placeholder="Enter your full name" required>
            </div>
            <div class="input-group">
                <label for="email">Email</label>
                <input type="email" name="email" id="email" placeholder="Enter your email" required>
            </div>
            <div class="input-group">
                <label for="password">Password</label>
                <input type="password" name="password" id="password" placeholder="Create a password" required>
            </div>
            <div class="input-group">
                <label for="confirm-password">Confirm Password</label>
                <input type="password" id="confirm-password" placeholder="Confirm your password" required>
            </div>
            <button type="submit" class="btn">Sign Up</button>
        </form>
        <p>Already have an account? <a href="login.php">Login here</a></p>
    </div>
    <script>
        document.querySelector("form").addEventListener("submit", function(event) {
    let password = document.getElementById("password").value;
    let confirmPassword = document.getElementById("confirm-password").value;

    if (password !== confirmPassword) {
        alert("Passwords do not match!");
        event.preventDefault();
    }
});

    </script>
</body>
</html>
