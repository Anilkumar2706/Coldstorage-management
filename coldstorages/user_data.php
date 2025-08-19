<?php
// fetch_user_data.php
session_start();
include 'connection.php'; // Ensure you have this file with database connection setup

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['error' => 'No user is logged in']);
    exit();
}

$user_id = $_SESSION['user_id'];

// Prepare and execute the SQL query to get user details
$stmt = $conn->prepare("SELECT name, email, phone_number FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if ($user) {
    echo json_encode($user);
} else {
    echo json_encode(['error' => 'User not found']);
}

$stmt->close();
$conn->close();
?>
