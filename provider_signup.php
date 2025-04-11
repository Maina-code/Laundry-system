<?php
require_once 'database.php';
require_once 'Provider.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $provider = new Provider();

    $providerId = null; // ID is auto-generated in the database
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Encrypt password
    $experience = $_POST['experience'];
    $hourlyRate = $_POST['charges_per_hour'];

    // Get selected services and custom services
    $services = isset($_POST['services']) ? $_POST['services'] : [];
    $customServices = isset($_POST['custom_services']) ? explode(',', $_POST['custom_services']) : [];
    $allServices = array_merge($services, $customServices);

    // Save provider profile
    if ($provider->saveProfile($providerId, $name, $email, $password, $experience, $allServices, $hourlyRate)) {
        // Redirect to home page with a success message
        header("Location: home.php?message=Your application has been received. The admin will review your request.");
        exit();
    } else {
        echo "Error saving profile.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Provider Signup</title>
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

        label {
            display: block;
            margin: 10px 0;
            font-weight: bold;
        }

        .checkbox-group {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 10px;
        }

        .checkbox-group label {
            display: flex;
            align-items: center;
            background: rgba(255, 255, 255, 0.2);
            padding: 5px 10px;
            border-radius: 5px;
            cursor: pointer;
        }

        .checkbox-group input {
            margin-right: 5px;
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
    </style>
</head>
<body>
    <div class="container">
        <h2>Provider Registration</h2>
        <form action="" method="POST">
            <div class="form-group">
                <input type="text" name="name" placeholder="Full Name" required>
            </div>
            <div class="form-group">
                <input type="email" name="email" placeholder="Email Address" required>
            </div>
            <div class="form-group">
                <input type="password" name="password" placeholder="Password" required>
            </div>
            <div class="form-group">
                <input type="number" name="experience" placeholder="Years of Experience" min="0" required>
            </div>

            <label>Select Services You Offer:</label>
            <div class="checkbox-group">
                <label><input type="checkbox" name="services[]" value="Dry Cleaning"> Dry Cleaning</label>
                <label><input type="checkbox" name="services[]" value="Ironing"> Ironing</label>
                <label><input type="checkbox" name="services[]" value="Stain Removal"> Stain Removal</label>
                <label><input type="checkbox" name="services[]" value="Fold & Pack"> Fold & Pack</label>
            </div>

            <div class="form-group">
                <input type="text" name="custom_services" placeholder="Enter other services...">
            </div>

            <div class="form-group">
                <input type="number" name="charges_per_hour" placeholder="Charges per Hour ($)" min="0" required>
            </div>

            <button type="submit" class="btn">Register</button>
        </form>
    </div>
</body>
</html>
