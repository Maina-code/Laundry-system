<?php 
require_once 'database.php';

class User {
    private $db;
    private $conn; // ✅ Define the connection property

    public function __construct() {
        $this->db = new Database();
        $this->conn = $this->db->conn; // ✅ Assign the database connection properly
    }

    // User Registration
    public function register($name, $email, $password) {
        if ($this->emailExists($email)) {
            return "Email already exists!";
        }

        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $sql = "INSERT INTO users (name, email, password) VALUES (?, ?, ?)";
        
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("sss", $name, $email, $hashedPassword);
        
        if ($stmt->execute()) {
            return "Registration successful!";
        } else {
            return "Error: " . $this->conn->error;
        }
    }

    // User Login
    public function login($email, $password) {
        $sql = "SELECT * FROM users WHERE email = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();

        if ($user && password_verify($password, $user['password'])) {
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }
            $_SESSION['client_id'] = $user['id'];
            $_SESSION['client_name'] = $user['name'];

            return "Login successful!";
        }
        return "Invalid email or password!";
    }

    // Get User ID (FIXED)
    public function getUserId($email) {
        $sql = "SELECT id FROM users WHERE email = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();

        return $user ? $user['id'] : null;
    }

    // Update User Profile
    public function updateProfile($userId, $name, $email, $password = null) {
        if ($password) {
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            $sql = "UPDATE users SET name = ?, email = ?, password = ? WHERE id = ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("sssi", $name, $email, $hashedPassword, $userId);
        } else {
            $sql = "UPDATE users SET name = ?, email = ? WHERE id = ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("ssi", $name, $email, $userId);
        }

        if ($stmt->execute()) {
            return "Profile updated successfully!";
        } else {
            return "Error: " . $this->conn->error;
        }
    }

    // Delete User Account
    public function deleteUser($userId) {
        $sql = "DELETE FROM users WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $userId);

        if ($stmt->execute()) {
            return "Account deleted successfully!";
        } else {
            return "Error: " . $this->conn->error;
        }
    }

    // Check if Email Exists
    private function emailExists($email) {
        $sql = "SELECT * FROM users WHERE email = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        return $result->num_rows > 0;
    }

    // Logout Function
    public function logout() {
        session_start();
        session_unset();
        session_destroy();
        return "Logged out successfully!";
    }
}
?>
