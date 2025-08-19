<?php
// paymentdata.php
session_start();
include 'connection.php'; // Ensure you have this file with database connection setup

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['error' => 'Not logged in']);
    exit();
}

$user_id = $_SESSION['user_id'];

// Prepare and execute the SQL query to get payment details
$stmt = $conn->prepare("SELECT date, amount FROM payments WHERE user_id = ? ORDER BY date DESC LIMIT 1");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$payment = $result->fetch_assoc();

if ($payment) {
    echo json_encode($payment);
} else {
    echo json_encode(['date' => 'No payment history', 'amount' => 'No payment history']);
}

$stmt->close();
$conn->close();
?>
