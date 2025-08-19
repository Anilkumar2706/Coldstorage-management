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

// Retrieve booking ID from query string
$booking_id = isset($_GET['booking_id']) ? intval($_GET['booking_id']) : 0;
if ($booking_id <= 0) {
    echo 'Invalid booking ID.';
    exit();
}

// Retrieve booking details
$sql = 'SELECT * FROM bookings WHERE booking_id = :booking_id AND user_id = :user_id';
$stmt = $pdo->prepare($sql);
$stmt->execute(['booking_id' => $booking_id, 'user_id' => $_SESSION['user_id']]);
$booking = $stmt->fetch(PDO::FETCH_ASSOC);

// Check if booking exists
if (!$booking) {
    echo 'Booking not found.';
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Page</title>
    <style>
        * {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    font-family: 'Arial', sans-serif;
    background-color: #f7f7f7;
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
    padding: 20px;
}

.payment-container {
    width: 100%;
    max-width: 600px;
    background: white;
    border-radius: 10px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    padding: 20px;
    text-align: center;
}

h2 {
    color: #4A148C;
    margin-bottom: 20px;
    font-size: 24px;
    font-weight: bold;
}

.details {
    margin-bottom: 20px;
    text-align: left;
}

.details p {
    margin-bottom: 12px;
    font-size: 16px;
}

.total {
    font-size: 20px;
    font-weight: bold;
    margin-top: 20px;
    color: #333;
}

.qr-code-section {
    margin-top: 20px;
    text-align: center;
}

.qr-code-section img {
    max-width: 100px; /* Adjust as needed */
    height: auto;
    margin: 0 auto;
}

.upi-transaction-id {
    margin-top: 20px;
    text-align: left;
}

.upi-transaction-id label {
    margin-bottom: 5px;
    color: #555;
    display: block;
    font-size: 16px;
}

.upi-transaction-id input {
    width: 100%;
    padding: 10px;
    border: 2px solid #9C27B0;
    border-radius: 5px;
    transition: border-color 0.3s;
    font-size: 16px;
    margin-top: 5px;
}

.make-payment-button {
    display: inline-block;
    padding: 12px 20px;
    background-color: #4CAF50;
    color: white;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    transition: background-color 0.3s, transform 0.2s;
    font-size: 16px;
    text-decoration: none;
    text-align: center;
}

.make-payment-button:hover {
    background-color: #45a049;
    transform: scale(1.05);
}

.make-payment-button:active {
    background-color: #397d3a;
    transform: scale(0.98);
}

    </style>
</head>
<body>
    <div class="payment-container">
        <h2>Payment Page</h2>
        <p>Booking ID: <?php echo htmlspecialchars($booking['booking_id']); ?></p>
        <p>First Name: <?php echo htmlspecialchars($booking['first_name']); ?></p>
        <p>Email: <?php echo htmlspecialchars($booking['email']); ?></p>
        <p>Storage Type: <?php echo htmlspecialchars($booking['storage_type']); ?></p>
        <p>Crop Type: <?php echo htmlspecialchars($booking['crop_type']); ?></p>
        <p>Starting Date: <?php echo htmlspecialchars($booking['starting_date']); ?></p>
        <p>Ending Date: <?php echo htmlspecialchars($booking['ending_date']); ?></p>
        <p>Total Cost: Rs. <?php echo htmlspecialchars($booking['total_cost']); ?></p>

        <!-- Add payment form or integration here -->
        <a href="confirm_payment.php?booking_id=<?php echo htmlspecialchars($booking['booking_id']); ?>" class="make-payment-button">Confirm Payment</a>
    </div>
</body>
</html>
