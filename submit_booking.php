<?php
session_start();
require_once 'database.php';

if (!isset($_SESSION['client_id'])) {
    $_SESSION['error'] = "You need to log in to book a service.";
    header('Location: login.php');
    exit();
}

$db = new Database();
$conn = $db->getConnection();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $provider_id = isset($_POST['provider_id']) ? trim($_POST['provider_id']) : '';
    $client_id = $_SESSION['client_id'];
    $email = isset($_POST['email']) ? trim($_POST['email']) : '';
    $phone = isset($_POST['phone']) ? trim($_POST['phone']) : '';
    $date = isset($_POST['date']) ? trim($_POST['date']) : '';
    $notes = isset($_POST['notes']) ? trim($_POST['notes']) : '';

    // Validate required fields
    if (empty($provider_id) || empty($email) || empty($phone) || empty($date)) {
        $_SESSION['error'] = "All fields are required!";
        header("Location: book_service.php");
        exit();
    }

    // Check if client exists
    $clientQuery = "SELECT id FROM users WHERE id = ?";
    $stmt = $conn->prepare($clientQuery);
    $stmt->bind_param("i", $client_id);
    $stmt->execute();
    $clientResult = $stmt->get_result();

    if ($clientResult->num_rows === 0) {
        $_SESSION['error'] = "Invalid client!";
        header("Location: book_service.php");
        exit();
    }

    // Check if provider exists and is approved
    $providerQuery = "SELECT * FROM providers WHERE id = ? AND status = 'Approved'";
    $stmt = $conn->prepare($providerQuery);
    $stmt->bind_param("i", $provider_id);
    $stmt->execute();
    $providerResult = $stmt->get_result();
    
    if ($providerResult->num_rows === 0) {
        $_SESSION['error'] = "Selected provider is not available.";
        header("Location: book_service.php");
        exit();
    }

    // Insert booking into the database
    $insertQuery = "INSERT INTO bookings (client_id, provider_id, email, phone, created_at, notes, status) 
                VALUES (?, ?, ?, ?, NOW(), ?, 'Pending')";
    $stmt = $conn->prepare($insertQuery);
    $stmt->bind_param("iisss", $client_id, $provider_id, $email, $phone, $notes);


    if ($stmt->execute()) {
        // Add notification for provider
        $notificationMessage = "You have a new booking request from a client.";
        $notificationQuery = "INSERT INTO notifications (provider_id, message, is_read) VALUES (?, ?, 0)";
        $stmt = $conn->prepare($notificationQuery);
        $stmt->bind_param("is", $provider_id, $notificationMessage);
        $stmt->execute();

        $_SESSION['success'] = "Booking request sent successfully!";
    } else {
        $_SESSION['error'] = "Failed to submit booking.";
    }

    header("Location: client_dashboard.php");
    exit();
} else {
    $_SESSION['error'] = "Invalid request!";
    header("Location: book_service.php");
    exit();
}
?>
