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
    $account_id = $_POST["account_id"];

    if ($amount <= 0) {
        die("Amount must be positive.");
    }

    // Use a lock file as a semaphore
    $lockFile = fopen("/tmp/bank_withdraw.lock", "w+");

    if (flock($lockFile, LOCK_EX)) {
        // Start transaction
        $conn->begin_transaction();

        try {
            // Get current balance
            $sql = "SELECT balance FROM accounts WHERE id=? FOR UPDATE";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $account_id);
            $stmt->execute();
            $result = $stmt->get_result();
            $row = $result->fetch_assoc();
            $current_balance = $row["balance"];

            // Check if it's possible to withdraw the amount
            if ($current_balance >= $amount) {
                // Update the balance
                $new_balance = $current_balance - $amount;
                $sql = "UPDATE accounts SET balance=? WHERE id=?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("di", $new_balance, $account_id);
                if ($stmt->execute() === TRUE) {
                    // Record the transaction
                    $sql = "INSERT INTO transactions (account_id, amount, date) VALUES (?, ?, NOW())";
                    $stmt = $conn->prepare($sql);
                    $stmt->bind_param("id", $account_id, $amount);
                    $stmt->execute();

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

        // Release the lock
        flock($lockFile, LOCK_UN);
    } else {
        echo "Unable to obtain lock.";
    }

    fclose($lockFile);
}

$conn->close();
?>