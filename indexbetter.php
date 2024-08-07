<!DOCTYPE html>
<html>
<head>
    <title>Bank - Withdraw Money</title>
</head>
<body>
    <h1>Bank - Withdraw Money</h1>
    <form action="withdraw.php" method="POST">
        <label for="account_id">From Account ID:</label>
        <select id="account_id" name="account_id" required>
            <?php
            $servername = "localhost";
            $username = "admin";
            $password = "F4keb4nk.8";
            $dbname = "Bank";

            $conn = new mysqli($servername, $username, $password, $dbname);

            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }

            $sql = "SELECT id FROM accounts";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    echo "<option value='" . $row["id"] . "'>" . $row["id"] . "</option>";
                }
            } else {
                echo "<option value=''>No accounts available</option>";
            }

            $conn->close();
            ?>
        </select>
        <br>
        <label for="amount">Amount to withdraw:</label>
        <input type="number" id="amount" name="amount" min="0.01" step="0.01" required>
        <br>
        <input type="submit" value="Withdraw">
    </form>

    <h2>Transaction Records</h2>
    <table border="1">
        <tr>
            <th>ID</th>
            <th>Account ID</th>
            <th>Amount</th>
            <th>Date</th>
        </tr>
        <?php
        $servername = "localhost";
        $username = "admin";
        $password = "F4keb4nk.8";
        $dbname = "Bank";

        $conn = new mysqli($servername, $username, $password, $dbname);

        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        $sql = "SELECT id, account_id, amount, date FROM transactions";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                echo "<tr><td>" . $row["id"] . "</td><td>" . $row["account_id"] . "</td><td>" . $row["amount"] . "</td><td>" . $row["date"] . "</td></tr>";
            }
        } else {
            echo "<tr><td colspan='4'>No transactions</td></tr>";
        }

        $conn->close();
        ?>
    </table>
</body>
</html>