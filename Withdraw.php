<?php
$servername = "localhost";
$username = "admin";
$password = "F4keb4nk.8";
$dbname = "Bank";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $amount = $_POST["amount"];

    // Start transaction
    $conn->begin_transaction();

    try {
        // Get current balance
        $sql = "SELECT balance FROM accounts WHERE id=1 FOR UPDATE";
        $result = $conn->query($sql);
        $row = $result->fetch_assoc();
$current_balance = $row["balance"];

        // Check if it's possible to withdraw the amount
        if ($current_balance >= $amount) {
            // Update the balance
            $new_balance = $current_balance - $amount;
            $sql = "UPDATE accounts SET balance=$new_balance WHERE id=1";
            if ($conn->query($sql) === TRUE) {
                // Record the transaction
                $sql = "INSERT INTO transactions (amount, date) VALUES ($amoun>
                $conn->query($sql);

                // Commit transaction
                $conn->commit();
                echo "Withdrawal successful. New balance: " . $new_balance;
            } else {
                throw new Exception("Error updating balance: " . $conn->error);
            }
        } else {
            throw new Exception("Insufficient funds.");
        }
    } catch (Exception $e) {
        // Rollback transaction
$conn->rollback();
        echo "Transaction failed: " . $e->getMessage();
    }
}

$conn->close();
?>