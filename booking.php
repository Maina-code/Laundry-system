<?php
require_once 'database.php'; // Database connection

class Booking {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    public function getAvailableProviders() {
        $sql = "SELECT * FROM service_providers WHERE availability = 'Available' ORDER BY rating DESC";
        return $this->db->query($sql);
    }
}

$booking = new Booking();
$providers = $booking->getAvailableProviders();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book a Service Provider</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: url('backgroundimg.jpg') no-repeat center center/cover;
            text-align: center;
            color: white;
        }
        .container {
            background: rgba(0, 0, 0, 0.8);
            padding: 20px;
            border-radius: 10px;
            width: 80%;
            max-width: 600px;
            margin: 50px auto;
        }
        .provider {
            border-bottom: 1px solid white;
            padding: 10px 0;
        }
        .btn {
            background: #007bff;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .btn:hover {
            background: #0056b3;
        }
    </style>
</head>
<body>

<h1>Available Service Providers</h1>
<div class="container">
    <?php while ($provider = $providers->fetch_assoc()): ?>
        <div class="provider">
            <h2><?= htmlspecialchars($provider['name']) ?></h2>
            <p>⭐ Rating: <?= number_format($provider['rating'], 1) ?></p>
            <p>🛠 Services: <?= htmlspecialchars($provider['services']) ?></p>
            <button class="btn" onclick="bookService(<?= $provider['id'] ?>, '<?= $provider['name'] ?>')">Book Now</button>
        </div>
    <?php endwhile; ?>
</div>

<script>
    function bookService(providerId, providerName) {
        if (confirm(`Confirm booking with ${providerName}?`)) {
            window.location.href = `confirm_booking.php?id=${providerId}`;
        }
    }
</script>

</body>
</html>
