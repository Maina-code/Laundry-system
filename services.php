<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Our Services - Laundry System</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: url('backgroundimg.jpg') no-repeat center center/cover;
            height: 100vh;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
            color: white;
        }
        .header-title {
            font-size: 3rem;
            font-weight: bold;
            margin-top: 20px;
        }
        .services-container {
            background: rgba(0, 0, 0, 0.7);
            padding: 20px;
            border-radius: 10px;
            width: 80%;
            max-width: 800px;
            margin-top: 20px;
        }
        .service {
            border-bottom: 1px solid white;
            padding: 15px 0;
        }
        .service:last-child {
            border-bottom: none;
        }
        .service h3 {
            color: #00aaff;
        }
        .btn {
            display: inline-block;
            background: #007bff;
            color: white;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 5px;
            margin-top: 15px;
            font-size: 1rem;
            transition: background 0.3s ease;
        }
        .btn:hover {
            background: #0056b3;
        }
        nav {
            position: absolute;
            top: 20px;
            right: 20px;
        }
        nav ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        nav ul li {
            display: inline;
            margin: 0 15px;
        }
        nav ul li a {
            color: white;
            text-decoration: none;
            font-size: 1.2rem;
            font-weight: bold;
            transition: color 0.3s ease;
        }
        nav ul li a:hover {
            color: #00aaff;
        }
    </style>
</head>
<body>
    <nav>
        <ul>
            <li><a href="index.php">Home</a></li>
            <li><a href="signup.php">Sign Up</a></li>
            <li><a href="login.php">Login</a></li>
            <li><a href="services.php">Services</a></li>
            <li><a href="contact.php">Contact</a></li>
        </ul>
    </nav>

    <h1 class="header-title">Our Laundry Services</h1>

    <div class="services-container">
        <div class="service">
            <h3>🧺 Standard Wash & Fold</h3>
            <p>Get your clothes washed, dried, and neatly folded. Perfect for everyday laundry needs.</p>
            <button class="btn" onclick="showMessage('Standard Wash & Fold booked!')">Book Now</button>
        </div>

        <div class="service">
            <h3>👕 Dry Cleaning</h3>
            <p>Professional dry cleaning for delicate fabrics, suits, and special garments.</p>
            <button class="btn" onclick="showMessage('Dry Cleaning booked!')">Book Now</button>
        </div>

        <div class="service">
            <h3>🛏️ Bedding & Linen Cleaning</h3>
            <p>Deep cleaning for bed sheets, blankets, and comforters to ensure a fresh sleep.</p>
            <button class="btn" onclick="showMessage('Bedding & Linen Cleaning booked!')">Book Now</button>
        </div>

        <div class="service">
            <h3>👶 Baby Clothes Special Care</h3>
            <p>Gentle cleaning for baby clothes using hypoallergenic detergents.</p>
            <button class="btn" onclick="showMessage('Baby Clothes Special Care booked!')">Book Now</button>
        </div>

        <div class="service">
            <h3>⏳ Express 24-Hour Service</h3>
            <p>Need it fast? Our express service guarantees laundry within 24 hours.</p>
            <button class="btn" onclick="showMessage('Express 24-Hour Service booked!')">Book Now</button>
        </div>
    </div>

    <script>
        function showMessage(message) {
            alert(message);
        }
    </script>
</body>
</html>
