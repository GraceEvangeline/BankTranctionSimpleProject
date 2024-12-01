<?php
session_start();
include 'db.php'; // Include the database connection file

if (!isset($_SESSION['user_id'])) {
    header("Location: login.html");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $recipient_account = $_POST['recipient'];
    $amount = $_POST['amount'];
    $user_id = $_SESSION['user_id'];

    // Fetch the user's balance
    $sql = "SELECT balance, account_number FROM Users WHERE id = '$user_id'";
    $result = $conn->query($sql);
    $user = $result->fetch_assoc();

    if ($user['balance'] >= $amount) {
        // Deduct amount from user's account
        $new_balance = $user['balance'] - $amount;
        $conn->query("UPDATE Users SET balance = '$new_balance' WHERE id = '$user_id'");
// Add amount to recipient's account
$conn->query("UPDATE Users SET balance = balance + '$amount' WHERE account_number = '$recipient_account'");

// Log the transaction
$description = "Transfer to Account $recipient_account";
$conn->query("INSERT INTO Transactions (user_id, description, amount, balance_after) VALUES ('$user_id', '$description', -$amount, '$new_balance')");

echo "Transfer successful!";
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
    <title>Banking System - Transfer Funds</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <header>
            <h1>Transfer Funds</h1>
            <nav>
                <a href="dashboard.html">Dashboard</a>
                <a href="transfer.html">Transfer Funds</a>
                <a href="paybills.html">Pay Bills</a>
                <a href="history.html">Transaction History</a>
                <a href="logout.html">Logout</a>
            </nav>
        </header>
        <main>
            <form action="transfer.php" method="POST">
                <label for="recipient">Recipient Account Number</label>
                <input type="text" id="recipient" name="recipient" required>
                
                <label for="amount">Amount</label>
                <input type="number" id="amount" name="amount" required>
      
                <button type="submit">Transfer</button>
            </form>
        </main>
    </div>
</body>
</html>

