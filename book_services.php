<?php
session_start();
require_once 'database.php';

if (!isset($_SESSION['client_id'])) {
    header('Location: login.php');
    exit();
}

$db = new Database();
$conn = $db->getConnection();

$service = isset($_GET['service']) ? trim($_GET['service']) : '';

if (!empty($service)) {
    $query = "SELECT * FROM providers WHERE JSON_CONTAINS(services, '\"$service\"') AND status = 'Approved' ORDER BY experience DESC";
} else {
    $query = "SELECT * FROM providers WHERE status = 'Approved' ORDER BY experience DESC";
}

$stmt = $conn->prepare($query);
if (!$stmt) {
    die("Query Error: " . $conn->error);
}
$stmt->execute();
$result = $stmt->get_result();
if (!$result) {
    die("Execution Error: " . $conn->error);
}

$providers = $result->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book a Service</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            text-align: center;
        }
        .provider-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 20px;
        }
        .provider-card {
            width: 280px;
            padding: 15px;
            border: 1px solid #ddd;
            background: black;
            color: white;
            border-radius: 8px;
            box-shadow: 2px 2px 10px rgba(0,0,0,0.1);
            text-align: center;
        }
        .services-list {
            background: rgba(255,255,255,0.1);
            padding: 10px;
            border-radius: 5px;
            margin: 10px 0;
        }
        .btn {
            padding: 10px 15px;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            background: #1976d2;
        }
        .btn:hover {
            opacity: 0.9;
        }
        /* Modal Styling */
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.5);
            justify-content: center;
            align-items: center;
            opacity: 0;
            visibility: hidden;
            transition: opacity 0.3s ease-in-out;
        }
        .modal-content {
            background: black;
            padding: 20px;
            border-radius: 10px;
            text-align: center;
            box-shadow: 0px 0px 10px rgba(0,0,0,0.2);
            width: 300px;
        }
        .modal input, .modal textarea {
            width: 100%;
            padding: 10px;
            margin: 5px 0;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        .modal button {
            padding: 10px;
            background: #1976d2;
            color: white;
            border: none;
            cursor: pointer;
            width: 100%;
            border-radius: 5px;
        }
        .modal button:hover {
            background: #1258a3;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Select a Provider</h2>
        <div class="service-filter">
            <label for="serviceSelect">Filter by Service:</label>
            <select id="serviceSelect" onchange="filterByService()">
                <option value="">All Services</option>
                <option value="Dry Cleaning" <?= ($service === "Dry Cleaning") ? 'selected' : '' ?>>Dry Cleaning</option>
                <option value="Ironing" <?= ($service === "Ironing") ? 'selected' : '' ?>>Ironing</option>
                <option value="Washing" <?= ($service === "Washing") ? 'selected' : '' ?>>Washing</option>
            </select>
        </div>

        <div class="provider-container">
            <?php if (empty($providers)) { ?>
                <p>No approved providers available.</p>
            <?php } else { 
                foreach ($providers as $provider) { ?>
                    <div class="provider-card">
                        <h3><?php echo htmlspecialchars($provider['name']); ?></h3>
                        <p><strong>Experience:</strong> <?php echo $provider['experience']; ?> years</p>
                        <p><strong>Rating:</strong> <?php echo number_format($provider['rating'], 1); ?> ⭐</p>
                        <p><strong>Charges:</strong> $<?php echo $provider['hourly_rate']; ?>/hr</p>
                        
                        <!-- Display Services -->
                        <div class="services-list">
                            <strong>Services Offered:</strong>
                            <ul style="list-style: none; padding: 0;">
                                <?php 
                                $services = json_decode($provider['services'], true);

                                if (json_last_error() === JSON_ERROR_NONE && !empty($services) && is_array($services)) {
                                    foreach ($services as $serv) {
                                        echo "<li>✔ " . htmlspecialchars($serv) . "</li>";
                                    }
                                } else {
                                    $fallback_services = explode(", ", $provider['services']);
                                    if (!empty($fallback_services[0])) {
                                        foreach ($fallback_services as $serv) {
                                            echo "<li>✔ " . htmlspecialchars($serv) . "</li>";
                                        }
                                    } else {
                                        echo "<li>No services listed</li>";  
                                    }
                                }
                                ?>
                            </ul>
                        </div>

                        <button class="btn" onclick="openBookingForm('<?php echo $provider['id']; ?>', '<?php echo $provider['name']; ?>')">Book Now</button>
                    </div>
            <?php } } ?>
        </div>
    </div>

    <!-- Booking Form Modal -->
    <div id="bookingForm" class="modal">
        <div class="modal-content">
            <h3>Book <span id="providerName"></span></h3>
            <form method="POST" action="submit_booking.php">
                <input type="hidden" name="provider_id" id="providerId">
                <label>Email:</label><br>
                <input type="email" name="email" required><br><br>
                <label>Phone Number:</label><br>
                <input type="text" name="phone" required><br><br>
                <label>Date:</label><br>
                <input type="date" name="date" required><br><br>
                <label>Additional Notes:</label><br>
                <textarea name="notes" placeholder="Any specific requirements"></textarea><br><br>
                <button type="submit" class="btn">Confirm Booking</button>
                <button type="button" onclick="closeBookingForm()" class="btn">Cancel</button>
            </form>
        </div>
    </div>

    <script>
        function openBookingForm(providerId, providerName) {
            document.getElementById('providerId').value = providerId;
            document.getElementById('providerName').textContent = providerName;
            let modal = document.getElementById('bookingForm');
            modal.style.display = 'flex';
            modal.style.opacity = "1";
            modal.style.visibility = "visible";
        }

        function closeBookingForm() {
            let modal = document.getElementById('bookingForm');
            modal.style.display = 'none';
            modal.style.opacity = "0";
            modal.style.visibility = "hidden";
        }
    </script>
</body>
</html>
