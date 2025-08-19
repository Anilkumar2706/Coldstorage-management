<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get POST data
    $booking_id = isset($_POST['booking_id']) ? intval($_POST['booking_id']) : 0;
    $upi_id = isset($_POST['upi_id']) ? $_POST['upi_id'] : '';
    $customer_name = isset($_POST['name']) ? $_POST['name'] : '';
    $method = isset($_POST['method']) ? $_POST['method'] : '';

    // Basic validation
    if ($booking_id <= 0 || empty($upi_id) || empty($customer_name) || empty($method)) {
        echo '<script>alert("Invalid input."); window.location.href = "home.php";</script>';
        exit();
    }

    // Verify UPI ID (You need to implement actual verification logic)
    if (verifyUpiId($upi_id)) {
        // Store booking data in the database
        if (storeBooking($booking_id, $upi_id, $customer_name, $method)) {
            // Show success message and redirect to payment_success.php
            echo '<script>
                    alert("Your transaction was successful.");
                    window.location.href = "payment_success.php";
                  </script>';
            exit();
        } else {
            echo '<script>
                    alert("Failed to store booking data.");
                    window.location.href = "home.php";
                  </script>';
            exit();
        }
    } else {
        echo '<script>
                alert("UPI ID verification failed.");
                window.location.href = "home.php";
              </script>';
        exit();
    }
}

// Function for UPI ID verification
function verifyUpiId($upi_id) {
    // Replace with actual UPI ID verification logic
    return preg_match('/^[a-zA-Z0-9@.]+$/', $upi_id);
}

// Function for generating payment URL
function getPaymentUrl($method, $upi_id, $booking_id) {
    // Replace with actual payment URL generation logic
    $amount = 1000; // Example amount
    $name = urlencode('YourName'); // Encode for URL

    switch ($method) {
        case 'phonepe':
            return "upi://pay?pa={$upi_id}&pn={$name}&mc=1234&tid=1234567890&tr=1234567890&tn=Payment+for+Booking+ID+{$booking_id}&am={$amount}&cu=INR&url=https://yourwebsite.com";
        case 'gpay':
            return "upi://pay?pa={$upi_id}&pn={$name}&mc=1234&tid=1234567890&tr=1234567890&tn=Payment+for+Booking+ID+{$booking_id}&am={$amount}&cu=INR&url=https://yourwebsite.com";
        case 'paytm':
            return "upi://pay?pa={$upi_id}&pn={$name}&mc=1234&tid=1234567890&tr=1234567890&tn=Payment+for+Booking+ID+{$booking_id}&am={$amount}&cu=INR&url=https://yourwebsite.com";
        default:
            return '#';
    }
}

// Function to store booking data
function storeBooking($booking_id, $upi_id, $customer_name, $method) {
    // Database connection
    $conn = new mysqli('localhost', 'root', '', 'coldstorages');
    
    if ($conn->connect_error) {
        die('Connection failed: ' . $conn->connect_error);
    }

    // Prepare and execute query
    $stmt = $conn->prepare("UPDATE bookings SET payment_method = ?, upi_id = ?, customer_name = ? WHERE booking_id = ?");
    if ($stmt === false) {
        die('Prepare failed: ' . $conn->error);
    }

    $stmt->bind_param('sssi', $method, $upi_id, $customer_name, $booking_id);

    $result = $stmt->execute();
    if ($result === false) {
        die('Execute failed: ' . $stmt->error);
    }

    $stmt->close();
    $conn->close();

    return $result;
}
?>
