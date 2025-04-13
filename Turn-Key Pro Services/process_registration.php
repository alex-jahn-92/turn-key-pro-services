<?php
// Start session
session_start();

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "turnkey_pro_services";

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

// Get form data
$firstName = $_POST['firstName'] ?? '';
$lastName = $_POST['lastName'] ?? '';
$email = $_POST['email'] ?? '';
$phone = $_POST['phone'] ?? '';
$address = $_POST['address'] ?? '';
$password = $_POST['password'] ?? '';
$confirmPassword = $_POST['confirmPassword'] ?? '';
$subscriptionPlan = $_POST['subscriptionPlan'] ?? '';

// Validate required fields
if (empty($firstName) || empty($lastName) || empty($email) || empty($phone) || 
    empty($address) || empty($password) || empty($confirmPassword) || empty($subscriptionPlan)) {
    $_SESSION['error'] = "All fields are required";
    header("Location: register.html");
    exit();
}

// Validate passwords match
if ($password !== $confirmPassword) {
    $_SESSION['error'] = "Passwords do not match";
    header("Location: register.html");
    exit();
}

// Validate password strength
if (strlen($password) < 8 || !preg_match("/[A-Za-z]/", $password) || !preg_match("/[0-9]/", $password)) {
    $_SESSION['error'] = "Password must be at least 8 characters long and contain both letters and numbers";
    header("Location: register.html");
    exit();
}

// Hash password
$hashedPassword = password_hash($password, PASSWORD_DEFAULT);

try {
    // Check if email already exists
    $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->execute([$email]);
    if ($stmt->rowCount() > 0) {
        $_SESSION['error'] = "Email already registered";
        header("Location: register.html");
        exit();
    }

    // Insert new user
    $stmt = $conn->prepare("INSERT INTO users (first_name, last_name, email, phone, address, password, subscription_plan) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->execute([$firstName, $lastName, $email, $phone, $address, $hashedPassword, $subscriptionPlan]);

    // Set session variables
    $_SESSION['user_id'] = $conn->lastInsertId();
    $_SESSION['user_name'] = $firstName;
    $_SESSION['user_email'] = $email;
    $_SESSION['subscription_plan'] = $subscriptionPlan;

    // Redirect to dashboard
    header("Location: dashboard.html");
    exit();

} catch(PDOException $e) {
    $_SESSION['error'] = "Registration failed: " . $e->getMessage();
    header("Location: register.html");
    exit();
}
?> 