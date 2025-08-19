<?php
session_start(); // Start the session

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

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.html');
    exit();
}

// Get the user ID from the session
$user_id = $_SESSION['user_id'];

// Get the form data
$name = trim($_POST['name']);
$email = trim($_POST['email']);
$phone = trim($_POST['phone']);

// Validate the input
if (empty($name) || empty($email) || empty($phone)) {
    echo 'All fields are required.';
    exit();
}

// Prepare and execute the update query
$sql = 'UPDATE users SET name = :name, email = :email, phone = :phone WHERE id = :user_id';
$stmt = $pdo->prepare($sql);

try {
    $stmt->execute([
        'name' => $name,
        'email' => $email,
        'phone' => $phone,
        'user_id' => $user_id
    ]);
    header('Location: my_account.php?update=success'); // Redirect with a success query parameter
    exit();
} catch (PDOException $e) {
    echo 'Update failed: ' . $e->getMessage();
}
?>
