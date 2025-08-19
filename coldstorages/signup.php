<?php
// Database configuration
$host = 'localhost'; // Replace with your database host
$dbname = 'coldstorages'; // Replace with your database name
$username = 'root'; // Replace with your database username
$password = ''; // Replace with your database password

// Create a new PDO instance
try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die('Connection failed: ' . $e->getMessage());
}

// Retrieve and sanitize form data
$name = trim($_POST['name']);
$email = trim($_POST['email']);
$phone = trim($_POST['phone']);
$password = trim($_POST['password']);

// Validate and sanitize inputs
if (empty($name) || empty($email) || empty($phone) || empty($password)) {
    echo 'All fields are required.';
    exit;
}

// Validate email format
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo 'Invalid email format.';
    exit;
}

// Validate phone number format
if (!preg_match('/^[0-9\+\-\(\) ]+$/', $phone)) {
    echo 'Invalid phone number format. Please enter a valid number.';
    exit;
}

// Check if email already exists
$sql = 'SELECT COUNT(*) FROM users WHERE email = :email';
$stmt = $pdo->prepare($sql);
$stmt->execute(['email' => $email]);
$emailExists = $stmt->fetchColumn();

if ($emailExists) {
    echo 'Email already registered.';
    exit;
}

// Hash the password
$hashedPassword = password_hash($password, PASSWORD_DEFAULT);

// Insert user into the database
$sql = 'INSERT INTO users (name, email, phone, password) VALUES (:name, :email, :phone, :password)';
$stmt = $pdo->prepare($sql);

try {
    $stmt->execute([
        'name' => $name,
        'email' => $email,
        'phone' => $phone,
        'password' => $hashedPassword
    ]);
    // Output HTML with JavaScript for alert and redirect
    echo '<!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Signup Success</title>
        <script>
            alert("Account has been created successfully.");
            window.location.href = "login.html"; // Redirect to the login page
        </script>
    </head>
    <body>
    </body>
    </html>';
} catch (PDOException $e) {
    echo 'Error: ' . $e->getMessage();
}
?>
