<?php
session_start();

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$host = 'localhost';
$dbname = 'coldstorages';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "Database connected successfully.";
} catch (PDOException $e) {
    die('Connection failed: ' . $e->getMessage());
}

$email = trim($_POST['email']);
$password = trim($_POST['password']);
echo "Submitted email: $email";
echo "Submitted password: $password";

if (empty($email) || empty($password)) {
    echo json_encode(['status' => 'error', 'message' => 'Both fields are required.']);
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(['status' => 'error', 'message' => 'Invalid email format.']);
    exit;
}

$sql = 'SELECT * FROM users WHERE email = :email';
$stmt = $pdo->prepare($sql);
$stmt->execute(['email' => $email]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if ($user) {
    echo "User found: " . print_r($user, true);
    if (password_verify($password, $user['password'])) {
        echo "Password verified!";
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_email'] = $user['email'];
        echo "Session set for user ID: " . $_SESSION['user_id'];

        $sql = 'INSERT INTO login (user_id, email) VALUES (:user_id, :email)';
        $stmt = $pdo->prepare($sql);
        if ($stmt->execute(['user_id' => $user['id'], 'email' => $email])) {
            echo "Login details inserted successfully.";
            echo json_encode(['status' => 'success', 'message' => 'Login successful!']);
        } else {
            echo "Failed to insert login details.";
        }
    } else {
        echo "Password verification failed!";
        echo json_encode(['status' => 'error', 'message' => 'Invalid email or password.']);
    }
} else {
    echo "User not found";
    echo json_encode(['status' => 'error', 'message' => 'Invalid email or password.']);
}
?>
