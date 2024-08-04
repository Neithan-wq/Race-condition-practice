<!DOCTYPE html>
<html>
<head>
    <title>Bank - Withdraw Money</title>
</head>
<body>
    <h1>Bank - Withdraw Money</h1>
    <form action="withdraw.php" method="POST">
        <label for="amount">Amount to withdraw:</label>
        <input type="number" id="amount" name="amount" required>
        <input type="submit" value="Withdraw">
    </form>

    <h2>Transaction Records</h2>
    <table border="1">
        <tr>
            <th>ID</th>
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

        $sql = "SELECT id, amount, date FROM transactions";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                echo "<tr><td>" . $row["id"]. "</td><td>" . $row["amount"]. "<>
            }
        } else {
            echo "<tr><td colspan='3'>No transactions</td></tr>";
        }

        $conn->close();
        ?>
    </table>
</body>
</html>