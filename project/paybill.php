<?php
session_start();
include 'db.php'; // Include the database connection file

if (!isset($_SESSION['user_id'])) {
    header("Location: login.html");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $bill_type = $_POST['bill-type'];
    $amount = $_POST['amount'];
    $user_id = $_SESSION['user_id'];

    // Fetch the user's balance
    $sql = "SELECT balance FROM Users WHERE id = '$user_id'";
    $result = $conn->query($sql);
    $user = $result->fetch_assoc();

    if ($user['balance'] >= $amount) {
        // Deduct amount from user's account
        $new_balance = $user['balance'] - $amount;
        $conn->query("UPDATE Users SET balance = '$new_balance' WHERE id = '$user_id'");

        // Log the transaction
        $description = "Payment for $bill_type";
        $conn->query("INSERT INTO Transactions (user_id, description, amount, balance_after) VALUES ('$user_id', '$description', -$amount, '$new_balance')");

        echo "Bill payment successful!";
    } else {
        echo "Insufficient funds!";
    }
}
?>




<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Banking System - Pay Bills</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <header>
            <h1>Pay Bills</h1>
            <nav>
                <a href="dashboard.html">Dashboard</a>
                <a href="transfer.html">Transfer Funds</a>
                <a href="paybills.html">Pay Bills</a>
                <a href="history.html">Transaction History</a>
                <a href="logout.html">Logout</a>
            </nav>
        </header>
        <main>
            <form action="paybill.php" method="POST">
                <label for="bill-type">Bill Type</label>
                <select id="bill-type" name="bill-type" required>
                    <option value="electricity">Electricity</option>
                    <option value="water">Water</option>
                    <option value="internet">Internet</option>
                </select>
                <label for="amount">Amount</label>
                <input type="number" id="amount" name="amount" required>
                
                <button type="submit">Pay Bill</button>
            </form>
        </main>
    </div>
</body>
</html>

