<?php 
session_start();
require_once 'database.php';
require_once 'provider.php';

if (!isset($_SESSION['provider_id'])) {
    header('Location: provider_login.php');
    exit();
}

$provider_id = $_SESSION['provider_id'];
$user = new Provider();
$provider = $user->getProfile($provider_id);
$provider_name = isset($provider['name']) ? $provider['name'] : 'Unknown';

// Fetch provider's bookings
$bookings = $user->getProviderBookings($provider_id);

// Decode the services JSON
$services = json_decode($provider['services'], true);
if (!is_array($services)) {
    $services = [];
}

// Calculate rating
$serviceCount = count($services);
$rating = $user->calculateRating($provider['experience'], $serviceCount);

// Handle Accept or Reject Booking
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['booking_id'], $_POST['action'])) {
    $booking_id = $_POST['booking_id'];
    $action = $_POST['action'];

    if ($action === 'accept') {
        $user->updateBookingStatus($booking_id, 'Accepted');
    } else {
        $user->updateBookingStatus($booking_id, 'Rejected');
    }

    header("Location: provider_dashboard.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Provider Dashboard</title>
    <style>
        body { font-family: Arial, sans-serif; background: #e3f2fd; margin: 0; padding: 0; }
        .sidebar { width: 250px; height: 100vh; background: #1976D2; color: white; position: fixed; padding: 20px; }
        .sidebar ul { list-style: none; padding: 0; }
        .sidebar ul li { padding: 10px 0; }
        .sidebar ul li a { color: white; text-decoration: none; font-weight: bold; }
        .content { margin-left: 270px; padding: 20px; }
        .dashboard-card { background: white; padding: 20px; border-radius: 10px; box-shadow: 0 0 10px rgba(0,0,0,0.1); margin-bottom: 20px; }
        .btn { padding: 5px 10px; border: none; cursor: pointer; margin: 5px; border-radius: 5px; }
        .accept { background: #4CAF50; color: white; }
        .reject { background: #D32F2F; color: white; }
        .view { background: #0288D1; color: white; }
        .price-btn { background: #FF9800; color: white; }
        .modal { display: none; position: fixed; z-index: 1; left: 0; top: 0; width: 100%; height: 100%; background: rgba(0, 0, 0, 0.5); justify-content: center; align-items: center; }
        .modal-content { background: white; padding: 20px; border-radius: 10px; width: 50%; text-align: center; }
        .close { color: red; float: right; font-size: 20px; cursor: pointer; }
    </style>
</head>
<body>
    <div class="sidebar">
        <h2>Welcome, <?php echo htmlspecialchars($provider_name); ?></h2>
        <ul>
            <li><a href="#">Dashboard</a></li>
            <li><a href="provider_editprofile.php">Edit Profile</a></li>
            <li><a href="logout.php">Logout</a></li>
        </ul>
    </div>

    <div class="content">
        <h1>Provider Dashboard</h1>
        <div class="dashboard-card">
            <h3>Profile Details</h3>
            <p><strong>Name:</strong> <?php echo htmlspecialchars($provider_name); ?></p>
            <p><strong>Experience:</strong> <?php echo htmlspecialchars($provider['experience']); ?> years</p>
            <p><strong>Services Offered:</strong> <?php echo !empty($services) ? implode(", ", $services) : "None"; ?></p>
            <p><strong>Rating:</strong> <?php echo number_format($rating, 1); ?> ⭐</p>
        </div>

        <div class="dashboard-card">
            <h3>Bookings</h3>
            <ul>
                <?php if (!empty($bookings)) { 
                    foreach ($bookings as $booking) { ?>
                        <li>
                            <?php 
                            $serviceName = isset($booking['email']) ? $booking['email'] : 'Unknown Service';
                            echo htmlspecialchars($booking['client_name']) . " - " . $serviceName . 
                                 " (" . $booking['status'] . ")"; 
                            ?>
                            <button class="btn view" onclick="openModal('<?php echo htmlspecialchars(json_encode($booking)); ?>')">View Details</button>
                            
                            <?php if ($booking['status'] === 'Pending') { ?>
                                <form method="POST" style="display: inline;">
                                    <input type="hidden" name="booking_id" value="<?php echo $booking['id']; ?>">
                                    <button type="submit" name="action" value="accept" class="btn accept">Accept</button>
                                    <button type="submit" name="action" value="reject" class="btn reject">Reject</button>
                                </form>
                                <a href="set_price.php?booking_id=<?php echo $booking['id']; ?>" class="btn price-btn">Set Price</a>
                            <?php } ?>
                        </li>
                    <?php } 
                } else { ?>
                    <li>No bookings yet.</li>
                <?php } ?>
            </ul>
        </div>
    </div>

    <!-- Booking Details Modal -->
    <div id="bookingModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal()">&times;</span>
            <h2>Booking Details</h2>
            <p><strong>Client Name:</strong> <span id="modalClientName"></span></p>
            <p><strong>Email:</strong> <span id="modalEmail"></span></p>
            <p><strong>Phone:</strong> <span id="modalPhone"></span></p>
            <p><strong>Service:</strong> <span id="modalService"></span></p>
            <p><strong>Date:</strong> <span id="modalDate"></span></p>
            <p><strong>Notes:</strong> <span id="modalNotes"></span></p>
        </div>
    </div>

    <script>
        function openModal(bookingData) {
            let booking = JSON.parse(bookingData);
            document.getElementById("modalClientName").textContent = booking.client_name;
            document.getElementById("modalEmail").textContent = booking.email;
            document.getElementById("modalPhone").textContent = booking.phone;
            document.getElementById("modalService").textContent = booking.service_id || "Unknown Service";
            document.getElementById("modalDate").textContent = booking.booking_date;
            document.getElementById("modalNotes").textContent = booking.notes || "No additional notes";
            document.getElementById("bookingModal").style.display = "flex";
        }

        function closeModal() {
            document.getElementById("bookingModal").style.display = "none";
        }
    </script>
</body>
</html>
