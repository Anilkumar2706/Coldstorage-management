<?php
session_start();
// Include database connection
require 'db_connection.php'; // Adjust the path as necessary

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_SESSION['user_id'];
    $booking_id = $_POST['booking_id']; // Example booking ID from form
    $payment_date = date('Y-m-d'); // Or use the date from a form
    $amount = $_POST['amount']; // Example amount from form
    $status = $_POST['status']; // Example status from form
    
    $sql = 'INSERT INTO payments (user_id, booking_id, payment_date, amount, status) VALUES (:user_id, :booking_id, :payment_date, :amount, :status)';
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        'user_id' => $user_id,
        'booking_id' => $booking_id,
        'payment_date' => $payment_date,
        'amount' => $amount,
        'status' => $status
    ]);

    header('Location: my_account.php'); // Redirect after successful insertion
    exit();
}
?>
