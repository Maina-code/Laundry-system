<?php
require_once 'database.php';

class Provider {
    private $conn;

    public function __construct() {
        $database = new Database();
        $this->conn = $database->conn;
    }

    public function saveProfile($id, $name, $email, $password, $experience, $services, $hourlyRate) {
        // Check if email already exists
        $checkSql = "SELECT id FROM providers WHERE email = ?";
        $checkStmt = $this->conn->prepare($checkSql);
        $checkStmt->bind_param("s", $email);
        $checkStmt->execute();
        $checkStmt->store_result();
    
        if ($checkStmt->num_rows > 0) {
            return "Email already registered. Please use a different email.";
        }
    
        $servicesList = implode(", ", $services);
        $sql = "INSERT INTO providers (name, email, password, experience, services, hourly_rate, status) 
                VALUES (?, ?, ?, ?, ?, ?, 'Pending')";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("sssiss", $name, $email, $password, $experience, $servicesList, $hourlyRate);
    
        return $stmt->execute() ? true : false;
    }
    

    public function getProfile($providerId) {
        $sql = "SELECT * FROM providers WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $providerId);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public function calculateRating($experience, $serviceCount) {
        return min(5, round(($experience * 0.5) + ($serviceCount * 0.5), 1));
    }

    public function loginProvider($email, $password) {
        $sql = "SELECT * FROM providers WHERE email = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        $provider = $result->fetch_assoc();

        if (!$provider) {
            return false; // No provider found with this email
        }

        // Check if password matches
        if (!password_verify($password, $provider['password'])) {
            return false; // Incorrect password
        }

        // Check if the provider is approved
        if ($provider['status'] !== "Approved") {
            return "not_approved";
        }

        return $provider; 
    
    }
    
    
    public function getProviderBookings($provider_id) {
        $sql = "SELECT id, client_name, email, phone, booking_date, notes, status, service_id 
                FROM bookings
                WHERE provider_id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $provider_id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }
    
    
    public function updateBookingStatus($booking_id, $status) {
        $stmt = $this->conn->prepare("UPDATE bookings SET status = ? WHERE id = ?");
        $stmt->bind_param("si", $status, $booking_id);
        $stmt->execute();
        $stmt->close();
    }
    
}
?>
