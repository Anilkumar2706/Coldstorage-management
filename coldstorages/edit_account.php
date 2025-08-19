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

// Retrieve user details
$user_id = $_SESSION['user_id'];
$sql = 'SELECT * FROM users WHERE id = :user_id';
$stmt = $pdo->prepare($sql);
$stmt->execute(['user_id' => $user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

// Check if user exists
if (!$user) {
    header('Location: login.html');
    exit();
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve and validate form data
    $name = !empty($_POST['name']) ? trim($_POST['name']) : null;
    $email = !empty($_POST['email']) ? trim($_POST['email']) : null;
    $phone = !empty($_POST['phone']) ? trim($_POST['phone']) : null;

    // Update user details in the database
    $sql = 'UPDATE users SET name = :name, email = :email, phone = :phone WHERE id = :user_id';
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['name' => $name, 'email' => $email, 'phone' => $phone, 'user_id' => $user_id]);

    // Redirect to the account page after successful update
    header('Location: my_account.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Account</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            background-image: url('contactus.jpeg');
            background-position: center center;
            background-size: cover;
            background-repeat: no-repeat;
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            color: #333;
        }
        
        .container {
            width: 600px;
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            text-align: left;
        }
        
        .container h2 {
            margin-bottom: 20px;
            font-size: 24px;
            color: #4CAF50;
        }
        
        .field {
            margin-bottom: 15px;
        }
        
        .field label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }
        
        .field input {
            width: 100%;
            padding: 12px;
            border: 2px solid #4CAF50;
            border-radius: 5px;
            transition: border-color 0.3s;
        }
        
        .field input:focus {
            border-color: #45a049;
        }
        
        button {
            width: 100%;
            padding: 15px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            opacity: 0.9;
            transition: opacity 0.3s;
            margin-top: 20px;
            font-size: 16px;
        }
        
        button:hover {
            opacity: 1;
        }
    </style>
</head>

<body>
    <div class="container">
        <h2>Edit Account</h2>
        <form action="edit_account.php" method="post">
            <input type="hidden" name="user_id" value="<?php echo htmlspecialchars($user['id']); ?>">
            <div class="field">
                <label for="name">Name:</label>
                <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($user['name']); ?>" required>
            </div>
            <div class="field">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>
            </div>
            <div class="field">
                <label for="phone">Phone Number:</label>
                <input type="text" id="phone" name="phone" value="<?php echo htmlspecialchars($user['phone']); ?>" required>
            </div>
            <button type="submit">Update</button>
        </form>
    </div>
</body>

</html>
