<?php
session_start();

// Database configuration
$host = 'localhost';
$dbname = 'turnkey_db';
$username = 'root';  // Change this to your database username
$password = '';      // Change this to your database password

// Error reporting for development (remove in production)
error_reporting(E_ALL);
ini_set('display_errors', 1);

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    error_log("Database connection failed: " . $e->getMessage());
    header('Location: login.html?error=database_error');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate CSRF token
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        header('Location: login.html?error=invalid_request');
        exit();
    }

    // Sanitize and validate input
    $email = filter_var($_POST['email'] ?? '', FILTER_SANITIZE_EMAIL);
    $password = $_POST['password'] ?? '';

    if (empty($email) || empty($password)) {
        header('Location: login.html?error=empty_fields');
        exit();
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        header('Location: login.html?error=invalid_email');
        exit();
    }

    try {
        // Prepare and execute the query
        $stmt = $pdo->prepare("SELECT id, name, email, password, role, status FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if ($user) {
            if ($user['status'] === 'inactive') {
                header('Location: login.html?error=account_inactive');
                exit();
            }

            if (password_verify($password, $user['password'])) {
                // Login successful - set session variables
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['email'] = $user['email'];
                $_SESSION['role'] = $user['role'];
                $_SESSION['logged_in'] = true;
                $_SESSION['last_activity'] = time();

                // Set secure session cookie parameters
                $secure = true; // Only send cookie over HTTPS
                $httponly = true; // Prevent JavaScript access
                $samesite = 'Lax'; // Prevent CSRF attacks
                
                session_set_cookie_params([
                    'lifetime' => 0,
                    'path' => '/',
                    'domain' => '',
                    'secure' => $secure,
                    'httponly' => $httponly,
                    'samesite' => $samesite
                ]);
                
                // Update last login
                $updateStmt = $pdo->prepare("UPDATE users SET last_login = NOW() WHERE id = ?");
                $updateStmt->execute([$user['id']]);
                
                // Redirect to dashboard
                header('Location: dashboard.php');
                exit();
            }
        }
        
        // Login failed
        header('Location: login.html?error=invalid_credentials');
        exit();
    } catch(PDOException $e) {
        error_log("Login error: " . $e->getMessage());
        header('Location: login.html?error=system_error');
        exit();
    }
} else {
    // Not a POST request
    header('Location: login.html');
    exit();
}
?> 