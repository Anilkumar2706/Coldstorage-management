<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get POST data
    $booking_id = isset($_POST['booking_id']) ? intval($_POST['booking_id']) : 0;
    $customer_name = isset($_POST['card_name']) ? $_POST['card_name'] : '';
    $card_number = isset($_POST['card_number']) ? $_POST['card_number'] : '';
    $expiry_date = isset($_POST['expiry_date']) ? $_POST['expiry_date'] : '';
    $cvv = isset($_POST['cvv']) ? $_POST['cvv'] : '';

    // Basic validation
    if ($booking_id <= 0 || empty($customer_name) || empty($card_number) || empty($expiry_date) || empty($cvv)) {
        echo '<script>alert("Invalid input."); window.location.href = "payment_page.php";</script>';
        exit();
    }

    // Process card payment (Integrate with payment gateway here)
    if (processCardPayment($customer_name, $card_number, $expiry_date, $cvv)) {
        // Store booking data in the database
        if (storeBooking($booking_id, $customer_name, $card_number, $expiry_date)) {
            echo '<script>alert("Card payment processed successfully."); window.location.href = "payment_success.php";</script>';
        } else {
            echo '<script>alert("Failed to store booking data."); window.location.href = "payment_page.php";</script>';
        }
    } else {
        echo '<script>alert("Card payment failed."); window.location.href = "payment_page.php";</script>';
    }
}

// Mock function to process card payment
function processCardPayment($customer_name, $card_number, $expiry_date, $cvv) {
    // Replace with actual card payment processing logic
    return true; // Simulate successful payment
}

// Function to store booking data
function storeBooking($booking_id, $customer_name, $card_number, $expiry_date) {
    // Database connection
    $conn = new mysqli('localhost', 'root', '', 'coldstorages');
    
    if ($conn->connect_error) {
        die('Connection failed: ' . $conn->connect_error);
    }

    // Prepare and execute query
    $stmt = $conn->prepare("UPDATE bookings SET customer_name = ?, card_number = ?, expiry_date = ? WHERE booking_id = ?");
    $stmt->bind_param('sssi', $customer_name, $card_number, $expiry_date, $booking_id);

    if ($stmt->execute()) {
        $stmt->close();
        $conn->close();
        return true;
    } else {
        $stmt->close();
        $conn->close();
        return false;
    }
}
?>
