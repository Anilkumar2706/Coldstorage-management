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

// Retrieve form data
$user_id = $_SESSION['user_id'];
$first_name = trim($_POST['first_name']);
$email = trim($_POST['email']);
$storage_type = trim($_POST['storage_type']);
$crop_type = trim($_POST['crop_type']);
$starting_date = trim($_POST['starting_date']);
$ending_date = trim($_POST['ending_date']);

// Validate and sanitize inputs
if (empty($first_name) || empty($email) || empty($storage_type) || empty($crop_type) || empty($starting_date) || empty($ending_date)) {
    echo 'All fields are required.';
    exit();
}

// Calculate the number of days and total cost
$start = new DateTime($starting_date);
$end = new DateTime($ending_date);
$interval = $start->diff($end);
$numberOfDays = $interval->days + 1; // including the starting day
$dailyRate = $storage_type;
$totalCost = ($dailyRate / 2) * $numberOfDays; // As per your formula

// Insert booking details into the database
$sql = 'INSERT INTO bookings (user_id, first_name, email, storage_type, crop_type, starting_date, ending_date, total_cost) VALUES (:user_id, :first_name, :email, :storage_type, :crop_type, :starting_date, :ending_date, :total_cost)';
$stmt = $pdo->prepare($sql);
$stmt->execute([
    'user_id' => $user_id,
    'first_name' => $first_name,
    'email' => $email,
    'storage_type' => $storage_type,
    'crop_type' => $crop_type,
    'starting_date' => $starting_date,
    'ending_date' => $ending_date,
    'total_cost' => $totalCost
]);

// Redirect to payment page with booking ID
$booking_id = $pdo->lastInsertId();
header('Location: payment.php?booking_id=' . $booking_id);
exit();
?>
