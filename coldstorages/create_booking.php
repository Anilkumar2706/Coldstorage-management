<?php
session_start();
// Include database connection
require 'db_connection.php'; // Adjust the path as necessary

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_SESSION['user_id'];
    $booking_date = date('Y-m-d'); // Or use the date from a form
    $status = $_POST['status']; // Example status from form
    
    $sql = 'INSERT INTO bookings (user_id, booking_date, status) VALUES (:user_id, :booking_date, :status)';
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        'user_id' => $user_id,
        'booking_date' => $booking_date,
        'status' => $status
    ]);

    header('Location: my_account.php'); // Redirect after successful insertion
    exit();
}
?>
