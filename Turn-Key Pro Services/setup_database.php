<?php
// Database configuration
$host = 'localhost';
$dbname = 'turnkey_db';
$username = 'root';
$password = '';

try {
    // Connect to MySQL without selecting a database
    $pdo = new PDO("mysql:host=$host", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Create database if it doesn't exist
    $pdo->exec("CREATE DATABASE IF NOT EXISTS $dbname");
    $pdo->exec("USE $dbname");
    
    // Create users table
    $pdo->exec("CREATE TABLE IF NOT EXISTS users (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(100) NOT NULL,
        email VARCHAR(100) NOT NULL UNIQUE,
        password VARCHAR(255) NOT NULL,
        role ENUM('admin', 'user') DEFAULT 'user',
        status ENUM('active', 'inactive') DEFAULT 'active',
        last_login DATETIME,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )");
    
    // Check if any users exist
    $stmt = $pdo->query("SELECT COUNT(*) FROM users");
    $userCount = $stmt->fetchColumn();
    
    if ($userCount == 0) {
        // Create a test admin user
        $name = "Admin User";
        $email = "admin@example.com";
        $password = password_hash("admin123", PASSWORD_DEFAULT);
        $role = "admin";
        
        $stmt = $pdo->prepare("INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, ?)");
        $stmt->execute([$name, $email, $password, $role]);
        
        echo "Database and test user created successfully!<br>";
        echo "Test user credentials:<br>";
        echo "Email: admin@example.com<br>";
        echo "Password: admin123<br>";
    } else {
        echo "Database already exists and contains users.<br>";
        echo "Please check the users using check_users.php<br>";
    }
    
} catch(PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?> 