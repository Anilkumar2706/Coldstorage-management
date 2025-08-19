<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: login.html');
    exit();
}

$booking_id = isset($_GET['booking_id']) ? intval($_GET['booking_id']) : 0;
if ($booking_id <= 0) {
    echo 'Invalid booking ID.';
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UPI Payment Options</title>
    <style>
        /* Your existing styles */
        body {
            font-family: 'Arial', sans-serif;
            background: linear-gradient(to right, #4A148C, #AB47BC);
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .payment-container {
            width: 100%;
            max-width: 600px;
            background: #ffffff;
            border-radius: 12px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            padding: 30px;
            text-align: center;
            animation: fadeIn 1s ease-in-out;
        }

        h2 {
            color: #4A148C;
            margin-bottom: 20px;
            font-size: 24px;
            font-weight: bold;
        }

        .option-group {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 20px;
        }

        .option {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 80%;
            max-width: 400px;
            padding: 15px;
            background-color: #E91E63;
            color: white;
            border-radius: 8px;
            cursor: pointer;
            transition: background-color 0.3s, transform 0.2s;
            font-size: 18px;
        }

        .option img {
            width: 40px;
            height: 40px;
            margin-right: 10px;
        }

        .option:hover {
            background-color: #D81B60;
            transform: scale(1.05);
        }

        .option:nth-child(2) {
            background-color: #4CAF50;
        }

        .option:nth-child(2):hover {
            background-color: #388E3C;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Modal styles */
        .modal {
            display: none;
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0,0,0,0.4);
            padding-top: 60px;
        }

        .modal-content {
            background-color: #fefefe;
            margin: 5% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
            max-width: 500px;
            border-radius: 8px;
        }

        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }

        .close:hover,
        .close:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-group label {
            display: block;
            margin-bottom: 5px;
        }

        .form-group input {
            width: 95%;
            padding: 10px;
            border-radius: 5px;
            border: 1px solid #ccc;
        }

        .form-group button {
            width: 100%;
            padding: 10px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .form-group button:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <div class="payment-container">
        <h2>Select Your UPI Payment Method</h2>
        <div class="option-group">
            <div class="option" onclick="openModal('phonepe')">
                <img src="phonepe_icon.jpeg" alt="PhonePe"> PhonePe
            </div>
            <div class="option" onclick="openModal('gpay')">
                <img src="gpay_icon.png" alt="Google Pay"> Google Pay
            </div>
            <div class="option" onclick="openModal('paytm')">
                <img src="paytm_icon.png" alt="Paytm"> Paytm
            </div>
        </div>
    </div>

    <!-- The Modal -->
    <div id="paymentModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal()">&times;</span>
            <h2>Enter Your UPI Details</h2>
            <form id="upiForm" method="POST" action="payment_redirect.php">
                <input type="hidden" name="booking_id" value="<?php echo htmlspecialchars($booking_id); ?>">
                <input type="hidden" name="method" id="paymentMethod">
                <div class="form-group">
                    <label for="upi_id">UPI ID:</label>
                    <input type="text" id="upi_id" name="upi_id" required>
                </div>
                <div class="form-group">
                    <label for="name">Name:</label>
                    <input type="text" id="name" name="name" required>
                </div>
                <div class="form-group">
                    <button type="submit">Submit</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openModal(paymentMethod) {
            document.getElementById('paymentMethod').value = paymentMethod;
            document.getElementById('paymentModal').style.display = 'block';
        }

        function closeModal() {
            document.getElementById('paymentModal').style.display = 'none';
        }

        window.onclick = function(event) {
            if (event.target === document.getElementById('paymentModal')) {
                closeModal();
            }
        }
    </script>
</body>
</html>
