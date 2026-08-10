-- =============================================
-- DATABASE: doctor_db
-- Complete Database Script for Florida Medical Clinic
-- =============================================

-- Create database (if not exists)
CREATE DATABASE IF NOT EXISTS doctor_db;
USE doctor_db;

-- =============================================
-- TABLE 1: users (Patient Accounts)
-- =============================================
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    fullname VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- =============================================
-- TABLE 2: appointments (In-Person Appointments)
-- =============================================
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

-- =============================================
-- TABLE 3: telehealth_appointments (Virtual Appointments)
-- =============================================
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

-- =============================================
-- TABLE 4: medical_record_requests
-- =============================================
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

-- =============================================
-- TABLE 5: feedback
-- =============================================
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

-- =============================================
-- TABLE 6: contact_messages
-- =============================================
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

-- =============================================
-- TABLE 7: chat_sessions
-- =============================================
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

-- =============================================
-- TABLE 8: chat_messages
-- =============================================
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

-- =============================================
-- TABLE 9: impersonation_log (For security tracking)
-- =============================================
CREATE TABLE IF NOT EXISTS impersonation_log (
    id INT AUTO_INCREMENT PRIMARY KEY,
    admin_id INT NOT NULL,
    admin_name VARCHAR(100),
    impersonated_user_id INT NOT NULL,
    impersonated_user_name VARCHAR(100),
    action ENUM('start', 'stop') DEFAULT 'start',
    ip_address VARCHAR(45),
    user_agent TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- =============================================
-- SAMPLE USERS (Minimal - for testing)
-- Password for all users is: password123
-- Admin password: admin123 (stored in admin login logic, not in users table)
-- =============================================

-- Insert sample patient users (password: password123)
INSERT INTO users (fullname, email, password, created_at) VALUES
('John Doe', 'john@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NOW()),
('Jane Smith', 'jane@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NOW());

-- =============================================
-- OPTIONAL: Insert a test admin user in users table (if you want admin to also have a patient account)
-- This is optional - admin can still log in via admin_login.php with hardcoded credentials
-- =============================================
-- INSERT INTO users (fullname, email, password) VALUES 
-- ('Admin User', 'admin@floridamedical.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi');

-- =============================================
-- VIEW ALL TABLES (For verification)
-- =============================================
SELECT '=== USERS TABLE ===' as '';
SELECT * FROM users;

SELECT '=== TABLE STRUCTURES ===' as '';
SHOW TABLES;