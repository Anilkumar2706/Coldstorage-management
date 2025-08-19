// Example to hash a password before storing it in the database
$password = 'yourpassword';
$hashedPassword = password_hash($password, PASSWORD_DEFAULT);

// Store $hashedPassword in the database
$password = 'user_password';
$hashedPassword = password_hash($password, PASSWORD_DEFAULT);

// Insert $hashedPassword into the database
<?php
include 'connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Sanitize and hash password
    $email = filter_var($email, FILTER_SANITIZE_EMAIL);
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    $stmt = $conn->prepare("INSERT INTO users (email, password) VALUES (?, ?)");
    $stmt->bind_param("ss", $email, $hashedPassword);

    if ($stmt->execute()) {
        echo "User created successfully";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>
