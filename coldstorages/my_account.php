<?php
session_start(); // Start the session

// Database configuration
$host = 'localhost'; // Your database host
$dbname = 'coldstorages'; // Your database name
$username = 'root'; // Your database username
$password = ''; // Your database password

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

// Retrieve user details
$user_id = $_SESSION['user_id'];
$sql = 'SELECT name, email, phone FROM users WHERE id = :user_id';
$stmt = $pdo->prepare($sql);
$stmt->execute(['user_id' => $user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

// Check if user exists
if (!$user) {
    header('Location: login.html');
    exit();
}

// Retrieve user bookings
$sqlBookings = 'SELECT * FROM bookings WHERE user_id = :user_id';
$stmtBookings = $pdo->prepare($sqlBookings);
$stmtBookings->execute(['user_id' => $user_id]);
$bookings = $stmtBookings->fetchAll(PDO::FETCH_ASSOC);

// Retrieve user payments
$sqlPayments = 'SELECT * FROM bookings WHERE user_id = :user_id';
$stmtPayments = $pdo->prepare($sqlPayments);
$stmtPayments->execute(['user_id' => $user_id]);
$payments = $stmtPayments->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Account</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            background: #f4f4f4 url('contactus.jpeg') center/cover no-repeat;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            color: #333;
        }

        .container {
            width: 90%;
            max-width: 800px;
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            margin-top: 20px;
        }

        h2 {
            margin-bottom: 20px;
            font-size: 24px;
            color: #4CAF50;
            text-align: center;
        }

        .field {
            margin-bottom: 15px;
        }

        .field label {
            font-weight: bold;
            display: block;
            margin-bottom: 5px;
        }

        .field p {
            padding: 10px;
            border: 1px solid #4CAF50;
            border-radius: 5px;
            background: #f9f9f9;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td {
            padding: 10px;
            border: 1px solid #ddd;
            text-align: left;
        }

        th {
            background: #4CAF50;
            color: white;
        }

        .button {
            display: block;
            width: 100%;
            padding: 15px;
            border: none;
            border-radius: 5px;
            color: white;
            font-size: 16px;
            text-align: center;
            cursor: pointer;
            transition: background 0.3s, opacity 0.3s;
            margin-top: 20px;
            text-decoration: none;
        }

        .button.primary {
            background: #4CAF50;
        }

        .button.primary:hover {
            background: #45a049;
        }

        .button.secondary {
            background: #f44336;
        }

        .button.secondary:hover {
            background: #e53935;
        }
    </style>
</head>

<body>
    <div class="container">
        <h2>My Account</h2>
        <div class="field">
            <label for="name">Name:</label>
            <p><?php echo htmlspecialchars($user['name']); ?></p>
        </div>
        <div class="field">
            <label for="email">Email:</label>
            <p><?php echo htmlspecialchars($user['email']); ?></p>
        </div>
        <div class="field">
            <label for="phone">Phone Number:</label>
            <p><?php echo htmlspecialchars($user['phone']); ?></p>
        </div>
        <a href="edit_account.php" class="button primary">Edit Account</a>
        
        <!-- Display Bookings -->
        <h2>My Bookings</h2>
        <table>
            <thead>
                <tr>
                    <th>Booking_ID</th>
                    <th>Booking Date</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($bookings): ?>
                    <?php foreach ($bookings as $booking): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($booking['booking_id']); ?></td>
                            <td><?php echo htmlspecialchars($booking['booking_date']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="3">No bookings found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>

        <!-- Display Payments -->
        <h2>My Payments</h2>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Payment Date</th>
                    <th>Amount</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($payments): ?>
                    <?php foreach ($payments as $payment): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($payment['booking_id']); ?></td>
                            <td><?php echo htmlspecialchars($payment['booking_date']); ?></td>
                            <td><?php echo htmlspecialchars($payment['total_cost']); ?></td>
                            <td><?php echo htmlspecialchars($payment['payment_status']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="4">No payments found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
        
        <a href="home.php" class="button secondary">Go Back</a>
    </div>
</body>

</html>
