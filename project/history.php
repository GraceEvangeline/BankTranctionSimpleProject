<?php
session_start();
include 'db.php'; // Include the database connection file

if (!isset($_SESSION['user_id'])) {
    header("Location: login.html");
    exit();
}

$user_id = $_SESSION['user_id'];
$sql = "SELECT * FROM Transactions WHERE user_id = '$user_id' ORDER BY date DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Banking System - Transaction History</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <header>
            <h1>Transaction History</h1>
            <nav>
            <a href="dashboard.html">Dashboard</a>
                <a href="transfer.html">Transfer Funds</a>
                <a href="paybills.html">Pay Bills</a>
                <a href="history.html">Transaction History</a>
                <a href="logout.php">Logout</a>
            </nav>
        </header>
        <main>
            <table>
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Description</th>
                        <th>Amount</th>
                        <th>Balance After</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()) { ?>
                    <tr>
                        <td><?php echo $row['date']; ?></td>
                        <td><?php echo $row['description']; ?></td>
                        <td><?php echo $row['amount']; ?></td>
                        <td><?php echo $row['balance_after']; ?></td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </main>
    </div>
</body>
</html>
