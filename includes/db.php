<?php
// Start session only if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Database connection with auto-creation
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "doctor_db";

// First connect without database to create it if needed
$conn = new mysqli($servername, $username, $password);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Create database if not exists
$sql = "CREATE DATABASE IF NOT EXISTS $dbname";
if ($conn->query($sql) === TRUE) {
    // Database created or already exists
} else {
    die("Error creating database: " . $conn->error);
}

// Now select the database
$conn->select_db($dbname);

// Set charset to UTF-8 for proper character encoding
$conn->set_charset("utf8mb4");

// Create tables if they don't exist
$tables_sql = "
-- Users table
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    fullname VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Appointments table
CREATE TABLE IF NOT EXISTS appointments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    fullname VARCHAR(100),
    email VARCHAR(100),
    appointment_type VARCHAR(50) DEFAULT 'in_person',
    appointment_date DATE NOT NULL,
    appointment_time VARCHAR(10) NOT NULL,
    department VARCHAR(50),
    doctor VARCHAR(100),
    reason TEXT,
    status ENUM('pending', 'confirmed', 'completed', 'cancelled') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Telehealth appointments table
CREATE TABLE IF NOT EXISTS telehealth_appointments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    fullname VARCHAR(100),
    email VARCHAR(100),
    phone VARCHAR(20),
    appointment_date DATE NOT NULL,
    appointment_time VARCHAR(10) NOT NULL,
    reason TEXT,
    video_link VARCHAR(255),
    status ENUM('pending', 'confirmed', 'completed', 'cancelled') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Medical record requests table
CREATE TABLE IF NOT EXISTS medical_record_requests (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    record_type VARCHAR(50) NOT NULL,
    date_from DATE,
    date_to DATE,
    delivery_method VARCHAR(50) NOT NULL,
    purpose VARCHAR(50) NOT NULL,
    status ENUM('pending', 'processing', 'completed', 'cancelled') DEFAULT 'pending',
    admin_reply TEXT,
    admin_message TEXT,
    message_sent_at TIMESTAMP NULL,
    attachment VARCHAR(255),
    attachment_type VARCHAR(50),
    attachment_name VARCHAR(255),
    request_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
);

-- Feedback table
CREATE TABLE IF NOT EXISTS feedback (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NULL,
    fullname VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    phone VARCHAR(20),
    feedback_type ENUM('accessibility', 'general', 'complaint', 'suggestion', 'assistance') DEFAULT 'general',
    message TEXT NOT NULL,
    status ENUM('pending', 'read', 'replied', 'resolved') DEFAULT 'pending',
    admin_reply TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Contact messages table
CREATE TABLE IF NOT EXISTS contact_messages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    fullname VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    phone VARCHAR(20),
    subject VARCHAR(100) NOT NULL,
    message TEXT NOT NULL,
    status ENUM('unread', 'read', 'replied', 'archived') DEFAULT 'unread',
    admin_reply TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Chat sessions table
CREATE TABLE IF NOT EXISTS chat_sessions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NULL,
    session_token VARCHAR(100) NOT NULL,
    visitor_name VARCHAR(100),
    visitor_email VARCHAR(100),
    status ENUM('active', 'closed', 'pending') DEFAULT 'active',
    started_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    ended_at TIMESTAMP NULL,
    INDEX idx_token (session_token),
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
);

-- Chat messages table
CREATE TABLE IF NOT EXISTS chat_messages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    session_id INT NOT NULL,
    sender_type ENUM('user', 'admin') NOT NULL,
    sender_name VARCHAR(100),
    message TEXT NOT NULL,
    is_read BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_session (session_id),
    FOREIGN KEY (session_id) REFERENCES chat_sessions(id) ON DELETE CASCADE
);

-- Nurse applications table
CREATE TABLE IF NOT EXISTS nurse_applications (
    id INT AUTO_INCREMENT PRIMARY KEY,
    fullname VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    phone VARCHAR(20) NOT NULL,
    qualification VARCHAR(50) NOT NULL,
    experience VARCHAR(20) NOT NULL,
    message TEXT,
    diploma_file VARCHAR(255),
    status ENUM('pending', 'reviewing', 'approved', 'rejected') DEFAULT 'pending',
    admin_reply TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);
";

// Execute multiple queries
if ($conn->multi_query($tables_sql)) {
    do {
        // Consume all results
    } while ($conn->next_result());
}

// Insert default admin if not exists
$check_admin = $conn->query("SELECT id FROM users WHERE email = 'admin@floridamedical.com'");
if ($check_admin->num_rows == 0) {
    $hashed_password = password_hash('admin123', PASSWORD_DEFAULT);
    $conn->query("INSERT INTO users (fullname, email, password) VALUES ('Admin User', 'admin@floridamedical.com', '$hashed_password')");
}

/**
 * Get user details by ID
 */
function getUserById($conn, $user_id) {
    $user_id = intval($user_id);
    $query = "SELECT id, fullname, email FROM users WHERE id = $user_id";
    $result = $conn->query($query);
    
    if ($result && $result->num_rows > 0) {
        return $result->fetch_assoc();
    }
    return null;
}

/**
 * Get all patients (for admin impersonation)
 */
function getAllPatients($conn) {
    $query = "SELECT id, fullname, email FROM users ORDER BY fullname ASC";
    $result = $conn->query($query);
    
    $patients = [];
    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $patients[] = $row;
        }
    }
    return $patients;
}

/**
 * Search patients by name or email
 */
function searchPatients($conn, $search) {
    $search = mysqli_real_escape_string($conn, $search);
    $query = "SELECT id, fullname, email FROM users 
              WHERE fullname LIKE '%$search%' OR email LIKE '%$search%' 
              ORDER BY fullname ASC";
    $result = $conn->query($query);
    
    $patients = [];
    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $patients[] = $row;
        }
    }
    return $patients;
}

/**
 * Log impersonation activity
 */
function logImpersonation($conn, $admin_id, $admin_name, $user_id, $user_name, $action) {
    $admin_id = intval($admin_id);
    $admin_name = mysqli_real_escape_string($conn, $admin_name);
    $user_id = intval($user_id);
    $user_name = mysqli_real_escape_string($conn, $user_name);
    $action = mysqli_real_escape_string($conn, $action);
    $ip_address = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
    $user_agent = mysqli_real_escape_string($conn, $_SERVER['HTTP_USER_AGENT'] ?? 'unknown');
    
    // Create impersonation_log table if not exists
    $conn->query("CREATE TABLE IF NOT EXISTS impersonation_log (
        id INT AUTO_INCREMENT PRIMARY KEY,
        admin_id INT NOT NULL,
        admin_name VARCHAR(100),
        impersonated_user_id INT NOT NULL,
        impersonated_user_name VARCHAR(100),
        action ENUM('start', 'stop') DEFAULT 'start',
        ip_address VARCHAR(45),
        user_agent TEXT,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )");
    
    $query = "INSERT INTO impersonation_log (admin_id, admin_name, impersonated_user_id, impersonated_user_name, action, ip_address, user_agent, created_at) 
              VALUES ($admin_id, '$admin_name', $user_id, '$user_name', '$action', '$ip_address', '$user_agent', NOW())";
    
    return $conn->query($query);
}

/**
 * Check if current user is impersonating
 */
function isImpersonating() {
    return isset($_SESSION['impersonating']) && $_SESSION['impersonating'] === true;
}

/**
 * Get original admin info when impersonating
 */
function getOriginalAdminInfo() {
    if (isImpersonating() && isset($_SESSION['original_admin_id'])) {
        return [
            'id' => $_SESSION['original_admin_id'],
            'username' => $_SESSION['original_admin_username'] ?? '',
            'fullname' => $_SESSION['original_admin_fullname'] ?? 'Admin'
        ];
    }
    return null;
}

/**
 * Get active session count for admin (for notification badge)
 */
function getActiveSessionCount($conn) {
    $query = "SELECT COUNT(*) as count FROM chat_sessions WHERE status = 'active'";
    $result = $conn->query($query);
    if ($result) {
        return $result->fetch_assoc()['count'];
    }
    return 0;
}

/**
 * Get unread feedback count for admin
 */
function getUnreadFeedbackCount($conn) {
    $query = "SELECT COUNT(*) as count FROM feedback WHERE status = 'pending'";
    $result = $conn->query($query);
    if ($result) {
        return $result->fetch_assoc()['count'];
    }
    return 0;
}

/**
 * Get unread contact messages count for admin
 */
function getUnreadContactCount($conn) {
    $query = "SELECT COUNT(*) as count FROM contact_messages WHERE status = 'unread'";
    $result = $conn->query($query);
    if ($result) {
        return $result->fetch_assoc()['count'];
    }
    return 0;
}

/**
 * Get pending medical record requests count for admin
 */
function getPendingMedicalRequestsCount($conn) {
    $query = "SELECT COUNT(*) as count FROM medical_record_requests WHERE status = 'pending'";
    $result = $conn->query($query);
    if ($result) {
        return $result->fetch_assoc()['count'];
    }
    return 0;
}

/**
 * Get pending nurse applications count for admin
 */
function getPendingNurseApplicationsCount($conn) {
    $query = "SELECT COUNT(*) as count FROM nurse_applications WHERE status = 'pending'";
    $result = $conn->query($query);
    if ($result) {
        return $result->fetch_assoc()['count'];
    }
    return 0;
}

/**
 * Get all nurse applications
 */
function getAllNurseApplications($conn) {
    $query = "SELECT * FROM nurse_applications ORDER BY 
              CASE 
                WHEN status = 'pending' THEN 1 
                WHEN status = 'reviewing' THEN 2 
                WHEN status = 'approved' THEN 3 
                WHEN status = 'rejected' THEN 4 
              END, 
              created_at DESC";
    $result = $conn->query($query);
    
    $applications = [];
    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $applications[] = $row;
        }
    }
    return $applications;
}

/**
 * Update nurse application status
 */
function updateNurseApplicationStatus($conn, $id, $status, $admin_reply = '') {
    $id = intval($id);
    $status = mysqli_real_escape_string($conn, $status);
    $admin_reply = mysqli_real_escape_string($conn, $admin_reply);
    
    $query = "UPDATE nurse_applications SET status = '$status', admin_reply = '$admin_reply' WHERE id = $id";
    
    return $conn->query($query);
}

/**
 * Get nurse application by email
 */
function getNurseApplicationByEmail($conn, $email) {
    $email = mysqli_real_escape_string($conn, $email);
    $query = "SELECT * FROM nurse_applications WHERE email = '$email' ORDER BY created_at DESC LIMIT 1";
    $result = $conn->query($query);
    
    if ($result && $result->num_rows > 0) {
        return $result->fetch_assoc();
    }
    return null;
}

/**
 * Check if nurse application exists
 */
function nurseApplicationExists($conn, $email) {
    $email = mysqli_real_escape_string($conn, $email);
    $query = "SELECT id FROM nurse_applications WHERE email = '$email'";
    $result = $conn->query($query);
    
    return $result && $result->num_rows > 0;
}

?>